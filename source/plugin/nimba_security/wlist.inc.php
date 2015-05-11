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
'appname' => '��ˮǽ��չ',
'tips' => '��ʾ��Ϣ',
'tip1' => '1��ѡ��ȷ�Ϸ�����������û����ü����������',
'tip2' => '2��ѡ�񡰽�����������û��ָ��������û���,�������û������������',
'tip3' => '3���м�����ͬʱѡ��"���"��"ȷ�Ϸ��"�����򽫲����κδ���',
'item1' => '�û���',
'item2' => 'UID',
'item3' => '�û���',
'item4' => 'ע������',
'item5' => 'ע������',
'item6' => '���ַ',
'item7' => '�IP',	
'item8' => '���',
'item9' => 'ȷ�Ϸ��',
'sub1' => 'ɾ?',
'sub2' => '�� Enter ������ʱ�ύ�����޸�',
'sub3' => '�ύ',
'nodata' => '��������',	
);
if(strtolower(CHARSET) == 'utf-8') $lang=auto_charset($lang,'GBK','UTF-8');

include template('nimba_security:wlist');

function userdetail($uid){
	$mquery=DB::query("SELECT username,email,regdate,groupid FROM ".DB::table('common_member')." where uid=$uid and groupid!='4'");//��ȡ�û���Ϣ
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