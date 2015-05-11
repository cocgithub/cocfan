<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

//utf-8转码函数
 function ic($text) {
 	global $_G;

 	if($_G['charset'] == 'utf-8'){
 		return $text;
 	}else{
    $serial_str = iconv('utf-8',$_G['charset'],$text);
    return $serial_str;
  }
}

  $getid = $_GET['getid'];
  $sqtb = $_G['config']['db']['1']['tablepre'];
  
  		$sl = $_G['cache']['plugin']['yhty_adtz']['sl'];
		if($sl <= 2){
			$sl = "";
		}else{
			$sl = "LIMIT ".$sl;
		}
		
  if($getid == ''){
   $result = mysql_query("SELECT * FROM ".$sqtb."forum_announcement ORDER BY id DESC ".$sl);
   $row = mysql_fetch_array($result);
   $rowtime=date('Y-m-d H:i:s',$row[starttime]);
   echo $row["id"];
  }else{ 
  $result = mysql_query("SELECT * FROM ".$sqtb."forum_announcement WHERE id=".$getid);
  $row = mysql_fetch_array($result);
  $rowtime=date('Y-m-d H:i:s',$row[starttime]);
  $tex=$tex.'<p class="ccxtitlep1">'.$row["subject"].'</p><p class="ccxtitlep2">'.ic("作者:").'<a style="margin-right: 10px;" href="home.php?mod=space&username='.$row["author"].'">'.$row["author"].'</a>'.$rowtime.'</p> ';
  $tex=$tex.nl2br($row["message"]);
  echo $tex;
  }
?>
