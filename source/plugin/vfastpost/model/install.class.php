<?php
/**
 *	[Discuz!] (C)2001-2099 Comsenz Inc.
 *	This is NOT a freeware, use is subject to license terms
 *
 *	$Id: install.class.php 2011-11-15 17:04:33 Ian - Zhouxingming $
 */
if(!defined('IN_DISCUZ') || !defined('PLUGIN_ID')) {
	exit('Access Denied');
}

/**
 * 插件安装类
 * 使用此类需要保证
 * 1 插件目录下有installsql.php文件, 文件内容为$installsql=''; 内容为建表的字符串
 */
class pluginInstall {
	var $installsql = '';		//插件表结构sql
	var $newtables = array();	//新表表明
	var $newsqls = array();		//新表的表结构sql
	var $config = array();
	function pluginInstall() {
		if(include_once DISCUZ_ROOT.'./source/plugin/'.PLUGIN_ID.'/installsql.php') {
			$this->installsql = $installsql;
		}
		preg_match_all("/CREATE\s+TABLE.+?pre\_(.+?)\s*\((.+?)\)\s*(ENGINE|TYPE)\s*\=/is", $this->installsql, $matches);
		$this->newtables = empty($matches[1])?array():$matches[1];
		$this->newsqls = empty($matches[0])?array():$matches[0];
		$this->_getconfig();
		if(empty($this->newtables) || empty($this->newsqls)) {
			exit('Installsql.php file is empty.');
		}
	}
	function install() {
		return $this->upgrade();
	}

	/**
	 * 主要方法
	 * 通过对当前的数据表结构和installsql.php中的对比来更新数据库.
	 */
	function upgrade() {
		$newtables = $this->newtables;
		$newsqls = $this->newsqls;
		$config = $this->config;
		foreach($newtables as $i => $newtable) {


			$newcols = getcolumn($newsqls[$i]);

			if(!$query = DB::query("SHOW CREATE TABLE ".DB::table($newtable), 'SILENT')) {
				preg_match("/(CREATE TABLE .+?)\s*(ENGINE|TYPE)\s*\=/is", $newsqls[$i], $maths);

				$type = mysql_get_server_info() > '4.1' ? " ENGINE=MYISAM".(empty($config['dbcharset'])?'':" DEFAULT CHARSET=$config[dbcharset]" ): " TYPE=MYISAM";
				$usql = $maths[1].$type;

				$usql = str_replace("CREATE TABLE IF NOT EXISTS pre_", 'CREATE TABLE IF NOT EXISTS '.$config['tablepre'], $usql);
				$usql = str_replace("CREATE TABLE pre_", 'CREATE TABLE '.$config['tablepre'], $usql);
				if(!DB::query($usql, 'SILENT')) {
					exit('Error:'.DB::errno().'<br><br>'.dhtmlspecialchars($usql));
				}
			} else {
				$value = DB::fetch($query);
				$oldcols = getcolumn($value['Create Table']);

				$updates = array();
				foreach ($newcols as $key => $value) {
					if($key == 'PRIMARY') {
						if($value != $oldcols[$key]) {
							if(!empty($oldcols[$key])) {
								$usql = "RENAME TABLE ".DB::table($newtable)." TO ".DB::table($newtable.'_bak');
								if(!DB::query($usql, 'SILENT')) {
									exit('Error:'.DB::errno().'<br><br>'.dhtmlspecialchars($usql));
								}
							}
							$updates[] = "ADD PRIMARY KEY $value";
						}
					} elseif ($key == 'KEY') {
						foreach ($value as $subkey => $subvalue) {
							if(!empty($oldcols['KEY'][$subkey])) {
								if($subvalue != $oldcols['KEY'][$subkey]) {
									$updates[] = "DROP INDEX `$subkey`";
									$updates[] = "ADD INDEX `$subkey` $subvalue";
								}
							} else {
								$updates[] = "ADD INDEX `$subkey` $subvalue";
							}
						}
					} elseif ($key == 'UNIQUE') {
						foreach ($value as $subkey => $subvalue) {
							if(!empty($oldcols['UNIQUE'][$subkey])) {
								if($subvalue != $oldcols['UNIQUE'][$subkey]) {
									$updates[] = "DROP INDEX `$subkey`";
									$updates[] = "ADD UNIQUE INDEX `$subkey` $subvalue";
								}
							} else {
								$usql = "ALTER TABLE  ".DB::table($newtable)." DROP INDEX `$subkey`";
								DB::query($usql, 'SILENT');
								$updates[] = "ADD UNIQUE INDEX `$subkey` $subvalue";
							}
						}
					} else {
						if(!empty($oldcols[$key])) {
							if(strtolower($value) != strtolower($oldcols[$key])) {
								$updates[] = "CHANGE `$key` `$key` $value";
							}
						} else {
							$updates[] = "ADD `$key` $value";
						}
					}
				}

				if(!empty($updates)) {
					$usql = "ALTER TABLE ".DB::table($newtable)." ".implode(', ', $updates);
					if(!DB::query($usql, 'SILENT')) {
						exit('Error:'.DB::errno().'<br><br>'.dhtmlspecialchars($usql));
					}
				}
			}

		}
	}

	/**
	 * 卸载表.
	 */
	function uninstall() {
		$sql = 'DROP TABLE ';
		foreach($this->newtables as $tablename) {
			$sql .= $this->config['tablepre'].$tablename.',';
		}
		$sql = rtrim($sql, ',');
		DB::query($sql);
	}
	/**
	 * 获得表前缀和字符集
	 */
	function _getconfig() {
		@include DISCUZ_ROOT.'./config/config_global.php';
		if($_config) {
			$this->config['tablepre'] = $_config['db'][1]['tablepre'];
			$this->config['dbcharset'] = $_config['db'][1]['dbcharset'];
		} else {
			system_error('config_notfound');
		}
	}
}

function getcolumn($creatsql) {

	$creatsql = preg_replace("/ COMMENT '.*?'/i", '', $creatsql);
	preg_match("/\((.+)\)\s*(ENGINE|TYPE)\s*\=/is", $creatsql, $matchs);

	$cols = explode("\n", $matchs[1]);
	$newcols = array();
	foreach ($cols as $value) {
		$value = trim($value);
		if(empty($value)) continue;
		$value = remakesql($value);
		if(substr($value, -1) == ',') $value = substr($value, 0, -1);

		$vs = explode(' ', $value);
		$cname = $vs[0];

		if($cname == 'KEY' || $cname == 'INDEX' || $cname == 'UNIQUE') {

			$name_length = strlen($cname);
			if($cname == 'UNIQUE') $name_length = $name_length + 4;

			$subvalue = trim(substr($value, $name_length));
			$subvs = explode(' ', $subvalue);
			$subcname = $subvs[0];
			$newcols[$cname][$subcname] = trim(substr($value, ($name_length+2+strlen($subcname))));

		}  elseif($cname == 'PRIMARY') {

			$newcols[$cname] = trim(substr($value, 11));

		}  else {

			$newcols[$cname] = trim(substr($value, strlen($cname)));
		}
	}
	return $newcols;
}
function remakesql($value) {
	$value = trim(preg_replace("/\s+/", ' ', $value));
	$value = str_replace(array('`',', ', ' ,', '( ' ,' )', 'mediumtext'), array('', ',', ',','(',')','text'), $value);
	return $value;
}
?>
