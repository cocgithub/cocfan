<?php
/*
 *	nimba_spider (C)2012 AiLab Inc.
 *	nimba_spider Made By Nimba, Team From AiLab.CN
 *	Id: admincp.inc.php  AiLab.CN 2013-02-28 09:11$
 */
if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
$lang=array(
	'search'=>'����',
	'type'=>'֩�����',
	'ip'=>'֩��IP',
	'back'=>'�������',
	'delete'=>'ɾ��',
	'deleted'=>'�����ɹ���',
	'dateline'=>'����ʱ��',
	'url'=>'ץȡҳ��',
	'deleteall'=>'���ͳ��',
	'tip'=>'�鿴������֩��ץȡ�б�:',
	'all'=>'�鿴ȫ��',
	'baidu'=>'�ٶ�',
	'google'=>'�ȸ�',
	'soso'=>'����',
	'sogou'=>'�ѹ�',
	'yahoo'=>'�Ż�',
	'bing'=>'��Ӧ',
	'youdao'=>'�е�',
	'alexa'=>'Alexa',
	's360'=>'360',
);
if(strtolower(CHARSET) == 'utf-8') $lang=auto_charset($lang,'GB2312','UTF-8');
$op=addslashes($_GET['op']);
if($op=='deleteall'){
	$query = DB::query("TRUNCATE TABLE ".DB::table('nimba_spider')."");
	ajaxshowheader();
	echo $lang['deleted'];
	ajaxshowfooter();
}elseif($op=='delete'){
	DB::delete('nimba_spider',array('id'=>intval($_GET['id'])));
	ajaxshowheader();
	echo $lang['deleted'];
	ajaxshowfooter();
}else{
	$pagenum = 20;
	$page=max(1,intval($_GET['page']));
	$i=1;
	$resultempty = FALSE;
	$where=$extra='';
	if(!empty($_GET['spider'])) {
		$spider = addslashes($_GET['spider']);
		$where = " AND spidername='$spider'";
		$extra = '&spider='.$spider;
	}
	echo '<table class="tb tb2 " id="tips">
	<tr>
	<th  class="partition">'.$lang['tip'].' 
	<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=nimba_spider&pmod=admincp">'.$lang['all'].'</a> | 
	<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=nimba_spider&pmod=admincp&spider=baidu">'.$lang['baidu'].'</a> | 
	<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=nimba_spider&pmod=admincp&spider=google">'.$lang['google'].'</a> | 
	<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=nimba_spider&pmod=admincp&spider=sogou">'.$lang['sogou'].'</a> | 
	<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=nimba_spider&pmod=admincp&spider=soso">'.$lang['soso'].'</a> | 
	<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=nimba_spider&pmod=admincp&spider=yahoo">'.$lang['yahoo'].'</a> | 
	<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=nimba_spider&pmod=admincp&spider=bing">'.$lang['bing'].'</a> | 
	<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=nimba_spider&pmod=admincp&spider=youdao">'.$lang['youdao'].'</a> | 
	<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=nimba_spider&pmod=admincp&spider=Alexa">'.$lang['alexa'].'</a> | 
	<a href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=nimba_spider&pmod=admincp&spider=so">'.$lang['s360'].'</a></th>
	</tr>
	</table>';
	showtableheader();
	if(!$resultempty){
		$count = DB::result_first("SELECT COUNT(*) FROM ".DB::table('nimba_spider')." WHERE 1 $where");
		$query = DB::query("SELECT * FROM ".DB::table('nimba_spider')." WHERE 1 $where ORDER BY id DESC LIMIT ".(($page - 1) * $pagenum).",$pagenum");
		echo '<tr class="header"><th>'.$lang['type'].'</th><th>'.$lang['ip'].'</th><th>'.$lang['dateline'].'</th><th>'.$lang['url'].'</th><th><a id="p'.$i.'" onclick="ajaxget(this.href, this.id, \'\');return false" href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=nimba_spider&pmod=admincp&op=deleteall">'.$lang['deleteall'].'</a></th><th></th></tr>';
		while($data = DB::fetch($query)) {
			$i++;
			$data['dateline'] = empty($data['dateline'])? '':dgmdate($data['dateline']);
			echo '<tr><td>'.$data['spidername'].'</td>'.
				 '<td>'.$data['spiderip'].'</td>'.
				 '<td>'.$data['dateline'].'</td>'.
				 '<td><a href='.$data['url'].' target="_blank">'.$data['url'].'</a></td>'.
				 '<td><a id="s'.$i.'" onclick="ajaxget(this.href, this.id, \'\');return false" href="'.ADMINSCRIPT.'?action=plugins&operation=config&do='.$pluginid.'&identifier=nimba_spider&pmod=admincp&op=delete&id='.$data['id'].'">'.$lang['delete'].'</a></td></tr>';
		}
		if(empty($count)) echo '<tr><td>��������</td></tr>';
	}
}
showtablefooter();
echo multi($count, $pagenum, $page, ADMINSCRIPT."?action=plugins&operation=config&do=$pluginid&identifier=nimba_spider&pmod=admincp$extra");

function auto_charset($fContents, $from='gbk', $to='utf-8') {
    $from = strtoupper($from) == 'UTF8' ? 'utf-8' : $from;
    $to = strtoupper($to) == 'UTF8' ? 'utf-8' : $to;
    if (strtoupper($from) === strtoupper($to) || empty($fContents) || (is_scalar($fContents) && !is_string($fContents))) {
        return $fContents;
    }
    if (is_string($fContents)) {
        if (function_exists('mb_convert_encoding')) {
            return mb_convert_encoding($fContents, $to, $from);
        } elseif (function_exists('iconv')) {
            return iconv($from, $to, $fContents);
        } else {
            return $fContents;
        }
    } elseif (is_array($fContents)) {
        foreach ($fContents as $key => $val) {
            $_key = auto_charset($key, $from, $to);
            $fContents[$_key] = auto_charset($val, $from, $to);
            if ($key != $_key)
                unset($fContents[$key]);
        }
        return $fContents;
    }
    else {
        return $fContents;
    }
}
?>