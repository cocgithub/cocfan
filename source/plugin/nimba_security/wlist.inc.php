<?php
loadcache('plugin');
require_once DISCUZ_ROOT.'./source/discuz_version.php';
if(DISCUZ_VERSION=='X2') $filepath=DISCUZ_ROOT.'./data/cache/cache_nimba_security_wid.php';
if(DISCUZ_VERSION=='X2.5'||DISCUZ_VERSION=='X3 Beta'||DISCUZ_VERSION=='X3 RC'||DISCUZ_VERSION=='X3') $filepath=DISCUZ_ROOT.'./data/sysdata/cache_nimba_security_wid.php';
if(file_exists($filepath)) @include_once $filepath;
$pagenum=20;
$num =count($wdata);
$page = max(1, intval($_GET['page']));
$start_limit = ($page - 1) * $pagenum;
$wdata=array_slice($wdata,$start_limit,$pagenum);
  foreach($wdata as $k=>$uid){
	if($uid){
		if(userdetail($uid)) $u=userdetail($uid);
		else continue;
		$u['uid']=$uid;
		$userlist[]=$u;
		$uids[$u['uid']]=$u['uid'];
		$stats[$u['uid']]=$u['status'];	 
	}

}

$multipage = multi($num, $pagenum, $page, "admin.php?action=plugins&operation=config&do=".$pluginid."&identifier=nimba_security&pmod=wlist"); 

$lang=array(
'appname' => '防水墙拓展',
'tips' => '提示信息',
'tip1' => '1、选择“确认封禁”即将该用户永久加入黑名单！',
'tip2' => '2、选择“解禁”即将该用户恢复至正常用户组,并将该用户加入白名单！',
'tip3' => '3、切记请勿同时选中"解禁"和"确认封禁"，否则将不做任何处理！',
'item1' => '用户名',
'item2' => 'UID',
'item3' => '用户组',
'item4' => '注册日期',
'item5' => '注册邮箱',
'item6' => '活动地址',
'item7' => '活动IP',	
'item8' => '解禁',
'item9' => '确认封禁',
'sub1' => '删?',
'sub2' => '按 Enter 键可随时提交您的修改',
'sub3' => '提交',
'nodata' => '暂无数据',	
);
if(strtolower(CHARSET) == 'utf-8') $lang=auto_charset($lang,'GBK','UTF-8');

include template('nimba_security:wlist');

function userdetail($uid){
	$mquery=DB::query("SELECT username,email,regdate,groupid FROM ".DB::table('common_member')." where uid=$uid and groupid!='4'");//获取用户信息
	$detail=DB::fetch($mquery);
	if(empty($detail)) return false;
	else{
		$detail['regdate']=date('Y-m-d H:i:s',$detail['regdate']);
		$detail['group']=DB::result_first("SELECT grouptitle FROM ".DB::table('common_usergroup')." where groupid=".$detail['groupid']." ");
		$detail['ip']=DB::result_first("SELECT lastip FROM ".DB::table('common_member_status')." WHERE uid=$uid");
		require_once libfile('function/misc');
		$detail['addr']=convertip($detail['ip']);
		$detail['addr']=str_replace('-','',$detail['addr']);
		$detail['addr']=str_replace(' ','',$detail['addr']);
		return $detail;
	}
}
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