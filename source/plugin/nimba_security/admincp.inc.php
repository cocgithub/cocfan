<?php
loadcache('plugin');
require_once DISCUZ_ROOT.'./source/discuz_version.php';
if(DISCUZ_VERSION=='X2') $filepath=DISCUZ_ROOT.'./data/cache/cache_nimba_security_time.php';
if(DISCUZ_VERSION=='X2.5'||DISCUZ_VERSION=='X3 Beta'||DISCUZ_VERSION=='X3 RC'||DISCUZ_VERSION=='X3') $filepath=DISCUZ_ROOT.'./data/sysdata/cache_nimba_security_time.php';
@include_once $filepath;
$pagenum=20;
$num =count($data);
$page = max(1, intval($_GET['page']));
$start_limit = ($page - 1) * $pagenum;
$udata=array_slice($data,$start_limit,$pagenum);
foreach($udata as $k=>$u){
	if($u['uid']){
 		$u['dateline']=date('Y-m-d H:i:s',$u['time']);
		if(userdetail($u['uid'])){
			$detail=userdetail($u['uid']);
			$u['regdate']=$detail['regdate'];
			$u['email']=$detail['email'];
			$u['ip']=$detail['ip'];
			$u['addr']=$detail['addr'];
			$userlist[]=$u;
			$uids[$u['uid']]=$u['uid'];
		}else continue;
	}
}
if($_POST){
	$tag=array(
	'del' => '删除选项,未定义操作!',
	'error' => '设置出错，解禁和确认封禁不能同时选中！',
	'ok' => '信息已更新！',
	'admintip' => '插件后台的解封操作不适用于管理组(版主、管理员等)和特殊用户组(自定义VIP组等)，此类用户请手动解封！',
	);
	if(strtolower(CHARSET) == 'utf-8') $tag=auto_charset($tag,'GBK','UTF-8');
	$wid=array();
	$bid=array();
	$del=array();
	foreach($uids as $k=>$uid){
		if($uid==1){
			echo "<script>alert('".$tag['admintip']."');window.location.href='admin.php?action=plugins&operation=config&do=".$pluginid."&identifier=nimba_security&pmod=admincp&page=$page';</script>";
			exit();		
		}
		if($_POST['delete_'.$uid]){
			unset($data[$uid]); //从列表中删除
			//echo "<script>alert('".$tag['del']."');window.location.href='admin.php?action=plugins&operation=config&do=".$pluginid."&identifier=nimba_security&pmod=admincp';</script>";
			$del[]=$uid;
		}else{
			if($_POST['portal_'.$uid]&&$_POST['forum_'.$uid]){
				echo "<script>alert('UID:$uid ".$tag['error']."');window.location.href='admin.php?action=plugins&operation=config&do=".$pluginid."&identifier=nimba_security&pmod=admincp&page=$page';</script>";
				exit();
			}else{
				if($_POST['portal_'.$uid]){//确认封禁
					$bid[]=$uid;
					unset($data[$uid]); 
				}
				if($_POST['forum_'.$uid]){//解禁
					get_group($uid);//恢复普通用户组
					$wid[]=$uid;
					unset($data[$uid]); 
				}
			}
		}
	}
	if(!empty($bid)) rerule($bid,'b');
	if(!empty($wid)) rerule($wid,'w');
	if(!empty($bid)||!empty($wid)||!empty($del)){
		$data['num']=count($data)-2;
		@require_once libfile('function/cache');
		$cacheArray .= "\$data=".arrayeval($data).";\n";
		writetocache('nimba_security_time', $cacheArray);	
	}
	echo "<script>alert('".$tag['ok']."');window.location.href='admin.php?action=plugins&operation=config&do=".$pluginid."&identifier=nimba_security&pmod=admincp&page=$page';</script>";
}  
$multipage = multi($num, $pagenum, $page, "admin.php?action=plugins&operation=config&do=".$pluginid."&identifier=nimba_security&pmod=admincp"); 

$lang=array(
'appname' => '防水墙拓展',
'tips' => '提示信息',
'tip1' => '1、选择“确认封禁”即将该用户永久加入黑名单！',
'tip2' => '2、选择“解禁”即将该用户恢复至正常用户组,并将该用户加入白名单！',
'tip3' => '3、切记请勿同时选中"解禁"和"确认封禁"，否则将不做任何处理！',
'item1' => '用户名',
'item2' => 'UID',
'item3' => '注册日期',
'item4' => '注册邮箱',
'item5' => '活动地址',
'item6' => '活动IP',
'item7' => '封禁时间',	
'item8' => '解禁',
'item9' => '确认封禁',
'sub1' => '清除?',
'sub2' => '按 Enter 键可随时提交您的修改',
'sub3' => '提交',
'nodata' => '暂无数据',
'admintip' => '4、<font color="red">插件后台的解封操作不适用于管理组(版主、管理员等)和特殊用户组(自定义VIP组等)，此类用户请手动解封！</font>',		
);
if(strtolower(CHARSET) == 'utf-8') $lang=auto_charset($lang,'GBK','UTF-8');

include template('nimba_security:admincp');

function get_group($uid){
	$mquery=DB::query("SELECT credits,groupid,adminid FROM ".DB::table('common_member')." where uid=$uid");//获取用户信息
	$m=DB::fetch($mquery);
	$groupid=DB::result_first("SELECT groupid FROM ".DB::table('common_usergroup')." where creditshigher<".$m['credits']." and creditslower>".$m['credits']." ");
	DB::query("UPDATE ".DB::table('common_member')." SET groupid=$groupid WHERE uid=".$uid);//更新用户组
} 
function userdetail($uid){
	$mquery=DB::query("SELECT email,regdate FROM ".DB::table('common_member')." where uid=$uid and groupid='4'");//获取用户信息,过滤掉已经手动解封的
	$detail=DB::fetch($mquery);
	if(empty($detail)) return false;
	else{
		$detail['regdate']=date('Y-m-d H:i:s',$detail['regdate']);
		$detail['ip']=DB::result_first("SELECT lastip FROM ".DB::table('common_member_status')." WHERE uid=$uid");
		require_once libfile('function/misc');
		$detail['addr']=convertip($detail['ip']);
		$detail['addr']=str_replace('-','',$detail['addr']);
		$detail['addr']=str_replace(' ','',$detail['addr']);
		return $detail;
	}
}
function rerule($arr,$tag){
	require_once DISCUZ_ROOT.'./source/discuz_version.php';
	if(DISCUZ_VERSION=='X2') $filepath=DISCUZ_ROOT.'./data/cache/cache_nimba_security_'.$tag.'id.php';
	if(DISCUZ_VERSION=='X2.5'||DISCUZ_VERSION=='X3 Beta'||DISCUZ_VERSION=='X3') $filepath=DISCUZ_ROOT.'./data/sysdata/cache_nimba_security_'.$tag.'id.php';
	@include_once $filepath;
	if($tag=='w'){
		$wdata=empty($wdata)? $arr:array_merge ($wdata,$arr);
		@require_once libfile('function/cache');
		$cacheArray .= "\$wdata=".arrayeval($wdata).";\n";
		writetocache('nimba_security_wid', $cacheArray);		
	}
	if($tag=='b'){
		$bdata=empty($bdata)? $arr:array_merge ($bdata,$arr);
		@require_once libfile('function/cache');
		$cacheArray .= "\$bdata=".arrayeval($bdata).";\n";
		writetocache('nimba_security_bid', $cacheArray);		
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