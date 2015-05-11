<?php
/**
 * 		Copyright£ºdxksst
 * 		  WebSite£ºwww.dxksst.com
 *             QQ:2811931192
 */
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class plugin_dxksst_n {} 
class plugin_dxksst_n_forum extends plugin_dxksst_n{	
 function index_top(){  //Function
 global $_G;$search=array();
 $dxksst = $_G['cache']['plugin']['dxksst_n'];
 $encode=$_G['charset'];$today_type=$dxksst['today_type'];
 $title=$dxksst['title'];$local_key=$dxksst['local_key'];
 $fcolor=$dxksst['fcolor'];$ltime=$dxksst['ltime'];
 $forbide_fid=$dxksst['forbide_fid']; $image_num=$dxksst['image_num'];
 $today_name=$dxksst['today_name'];$describe=$dxksst['describe'];
 require_once './source/plugin/dxksst_n/history/get_history.php';
 require_once libfile('getinfo','plugin/dxksst_n');
 require_once libfile('cache','plugin/dxksst_n');
 $thread_height = 10*20;
 $dxksst_title=explode("|",$title);
 $file='./source/plugin/dxksst_n/cache/time.dxksst';
 $fp=fopen($file,'r');
 $lasttime=(int)(fgets($fp));
 $ntime=strtotime(date("H:i:s"));$dtime=$ntime-$lasttime;
 if($dtime>=$ltime){$fp=fopen($file,'w');
 fwrite($fp,$ntime);fclose($fp);
 $history_post=get_history(10,1,$today_type,$describe,$forbide_fid);  	 
 $new_post=get_post_by_type('new',10,1,$forbide_fid);
 $hot_post=get_post_by_type('hot',10,1,$forbide_fid);
 $newreply_post=get_post_by_type('newreply',10,1,$forbide_fid);
 $new_user=get_user('new',10,1);$view_post=get_post_by_type('view',10,1,$forbide_fid);
 $pic=get_pic($image_num,$_G['setting']['attachurl']);
 dxksst_wcache("history",$history_post);	dxksst_wcache("new",$new_post);	
 dxksst_wcache("hot",$hot_post);	dxksst_wcache("newreply",$newreply_post);	
 dxksst_wcache("new_user",$new_user);	dxksst_wcache("pic",$pic);dxksst_wcache("view",$view_post);	
 }
 else{ $history_post=dxksst_rcache("history");$new_post=dxksst_rcache("new");	
 $hot_post=dxksst_rcache("hot");$newreply_post=dxksst_rcache("newreply");	
 $new_user=dxksst_rcache("new_user");$pic=dxksst_rcache("pic");fclose($fp);}
 include template('dxksst_n:dxksst');
$scipt= <<<SCRIPT
<script type="text/javascript" src="http://int.dpool.sina.com.cn/iplookup/iplookup.php?format=js"></script>
<script>
runslideshow();
var ip=remote_ip_info.start;
dxksst_ajax("./plugin.php?id=dxksst_n:ajax"+"&ip="+ip,"dxksst_recommend");
</script> 
SCRIPT;
?><?php
$_G['setting']['pluginhooks']['index_middle']=$_G['setting']['pluginhooks']['index_middle'].$scipt;
return $return;
}//END Function
}
?>
