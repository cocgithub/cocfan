<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: static.php 2011-11-11 15:14:52Z Ian - Zhouxingming $
 */
if(!defined('IN_DISCUZ') || !defined('PLUGIN_ID')) {
	exit('Access Denied');
}

/**
 * 统计操作类
 */
class pluginStat {
	var $tablename = '';
	var $tablefiles = array();
	/**
	 * 构造函数, 初始化相关信息
	 * @param string $tablename		统计表名称
	 * @param array $tablefields 		统计表字段
	 *	$tabelfields
	 */
	function pluginStat() {
	
		$this->tablename = 'plugin_vfastpost_stat';
		$this->tablefields = array(
			'daytime',
			'nt',
			'nt_index',
			'nr',
			'nr_vf',
			'nr_vf_float',
			'cl_f',
			'cl_v_t',
			'cl_v_r',
			'cl_vf_float',
			'cl_vf_first',
		);
	}

	/**
	 * 更新统计表
	 * @param string $field			修改的行的字段名称
	 * @param string $field			修改的行的字段值
	 * @param array $params			数组,键表示字段名,值为修改成为的值
	 *		+N	增加N
	 *		-N	减少N
	 *		string	改为某个值
	 *
	 * @return true				成功, false 失败 
	 */
	function updateStat($field, $value, $params) {
		if(!is_array($params) || !in_array($field, $this->tablefields)) {
			return false;
		}
		$params = daddslashes($params);
		$exists = DB::result_first("SELECT COUNT(*) FROM ".DB::table($this->tablename)." WHERE $field='{$value}'");

		if($exists) {
			$sql = 'UPDATE '.DB::table($this->tablename).' SET ';
			foreach($params as $k => $v) {
				$sql .= $v{0} == '-' ?
						"$k=$k-'".abs($v)."'" :
						($v{0} == '+' ?
						"$k=$k+'".abs($v)."'" : "$k='$v'");
				$sql .= ',';
			}
			$sql = rtrim($sql, ',')." WHERE $field='$value'";
		} else {
			$sql = 'INSERT INTO '.DB::table($this->tablename).' ';
			$sql_field = $field.',';
			$sql_value = "'$value',";
			foreach($params as $k => $v) {
				$v = abs($v);
				$sql_field .= $k.',';
				$sql_value .= "'$v',";
			}
			$sql_field = rtrim($sql_field, ',');
			$sql_value = rtrim($sql_value, ',');
			$sql .= "({$sql_field}) VALUES ($sql_value)";
		}
		DB::query($sql);
		return true;
	}

	/**
	 * 获取相关信息
	 * @param array $fields			要查询的字段名称及对应的中文名称 如:array('field_1' => 'field中文名',...)
	 * @param string $condition		查询条件, 即 sql 的 where 语句
	 * @return string
	 * 
	 * @return array(
	 *	array('filed1' => 'value1','field2' => 'value2',...)
	 *	...
	 *	)
	 *
	 * todo
	 *
	function getStat($fields, $condition) {
		$query = DB::query("SELECT * FROM ".DB::table($this->tablename)." WHERE $condition");
		while($result = DB::fetch($query)) {
			
		}
	}
	*/
	/**
	 * 生成XML
	 * @param array $fields			要查询的字段名称及对应的中文名称 如:array('field_1' => 'field中文名',...)
	 * @param string $condition		查询条件 即 sql的 where 语句
	 * @param string $xmlkey		xml的行标记, 一般为日期
	 * @param string $charset		当前字符集
	 *
	 * @return string			返回XML 为UTF8编码
	 */
	function makeXML($fields, $condition, $xmlkey, $charset) {
		$query = DB::query("SELECT * FROM ".DB::table($this->tablename)." WHERE $condition");
		$j = 0;
		$x = '';
		for($i = 0, $c = count($fields) + 1; $i < $c; $i++) {
			${'g_'.$i} = '';
		}
		while($result = DB::fetch($query)) {
			$j++;
			$x .= '<value xid="'.$j.'">'.$result[$xmlkey].'</value>';
			for($i = 0, $c = count($fields) + 1; $i < $c; $i++) {
				${'g_'.$i} .= '<value xid="'.$j.'">'.$result[$fields[$i]].'</value>';
			}
		}
		$xml = '<chart><xaxis>';
		$xml .= $x;
		$xml .= '</xaxis><graphs>';
		for($i = 0, $c = count($fields); $i < $c; $i++) {
			$xml .= '<graph gid="'.($i+1).'" title="'.diconv(plang('stat_'.$fields[$i]), CHARSET, 'utf8').'">'.${'g_'.$i}.'</graph>';
		}
		$xml .= '</graphs></chart>';
		return $xml;
	}
	/**
	 * 后台生成统计信息表单函数
	 * @param string $begin		开始时间 Y-m-d
	 * @param string $end		结束时间 Y-m-d
	 * @param array $checked	勾选的项目		为空则使用构造函数中的名称
	 * @param array $skip		忽略的项目
	 *
	 * p.s.使用注意 使用此功能需要在语言包中定义如下的内容, 
	 *	'stat' => '统计信息',
	 *	'stat_options' => '查询数据',
	 *	'stat_date' => '统计时间',
	 *	'stat_field1' => 'field1的中文名称',
	 *	'stat_submit' => '查看',
	 */
	function showOptions($begin, $end, $checked = array(), $skip = array()) {
		$begin = !empty($begin) ? $begin : dgmdate(TIMESTAMP-604800, 'Y-m-d');
		$end = !empty($end) ? $end : dgmdate(TIMESTAMP, 'Y-m-d');
		$fields = $this->tablefields;

		echo '<script src="static/js/calendar.js" type="text/javascript"></script>';
		showtableheader(lang('plugin/'.PLUGIN_ID, 'stat'), '');
		$optionHTML = '';
		$i = 0;
		foreach($fields as $field) {
			$i++;
			if(!empty($skip) && in_array($field, $skip)) continue;
			$optionHTML .= '<input type="checkbox" class="checkbox"'.(in_array($field, $checked) ? 'checked="checked"' : '').' name="option[]" id="'.$field.'" value="'.$field.'"/><label for="'.$field.'">'.lang('plugin/'.PLUGIN_ID, 'stat_'.$field).'</label>'.($i%6 == 0?'<br/>':'');
		}

		showtablerow('', array('class="td23"', ''), array(lang('plugin/'.PLUGIN_ID, 'stat_options'), $optionHTML));
		showtablerow('', array('class="td23"', ''), array(lang('plugin/'.PLUGIN_ID, 'stat_date'), '<input type="text" class="txt" name="date_after" value="'.$begin.'" style="width: 108px; margin-right: 5px;" onclick="showcalendar(event, this)"> -- <input type="text" class="txt" name="date_before" value="'.$end.'" style="width: 108px; margin-left: 5px;" onclick="showcalendar(event, this)"><input type="submit" class="btn" id="submit_submit" name="submit" title="" value="'.lang('plugin/'.PLUGIN_ID, 'stat_submit').'">'));
		showtablefooter();
	}

	/**
	 * 生成option的短标记
	 * @param array $fields			选择的字段数组
	 * @return 返回短标记
	 */
	function makeShortOption($fields) {
		$option = 0;
		$i = 0;
		foreach($this->tablefields as $f) {
			$option += in_array($f, $fields) ? pow(2, $i) : 0;
			$i++;	
		}
		return $option;
	}
	/**
	 * 把option短标记解析成数组
	 * @param string $option			短标记
	 * @return array 返回字段数组
	 */
	function getShortOption($option) {
		$i = 0;
		$fields = array();
		foreach($this->tablefields as $f) {
			if($option & pow(2, $i)) {
				$fields[] = $f;
			}
			$i++;
		}
		return $fields;
	}

	/**
	 * 生成flash的调用代码
	 * @param string $hash		hash验证码
	 * @param int $option		字段的短标签
	 * @param string $begin		开始日期
	 * @param string $end		结束日期
	 * return string		返回调用的JS代码
	 */
	function makeFlash($hash, $option, $begin, $end, $siteurl) {
		global $_G;
		$flash = '';
		$xmlurl_number = $siteurl.'plugin.php?id='.PLUGIN_ID.':stat&subop=xml&option='.$option.'&begin='.$begin.'&end='.$end.'&hash='.$hash;
		$xmlurl_number = urlencode($xmlurl_number);
		$pluginid = PLUGIN_ID;
		$flash = <<<HTML
			<script type="text/javascript">
				document.write(AC_FL_RunContent(
					'width', '80%', 'height', '300',
					'src', './static/image/common/stat.swf?path=&settings_file=source/plugin/{$pluginid}/model/setting.xml&data_file=$xmlurl_number',
					'quality', 'high', 'wmode', 'transparent'
				));
			</script>
HTML;
		return $flash;
	}

}
?>
