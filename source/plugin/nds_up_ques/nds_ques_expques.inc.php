<?PHP
/*  nds_up_ques  v3.2
 *  Plugin FOR Discuz! X 
 *	WWW.NWDS.CN | NDS.西域数码工作室 
 *  Plugin update 20130917 BY singcee
 */
if (! defined ( 'IN_DISCUZ' )) {
	exit ( 'Access Denied' );
}
set_time_limit ( 0 );
if (function_exists ( ini_set )) {
	ini_set ( 'memory_limit', '256M' );
}
define ( 'FOOTERDISABLED', 1 );
$questopic = DB::fetch_first ( "SELECT * FROM " . DB::table ( 'ques_topic' ) . " WHERE`topicid`='$topicid' LIMIT 1" );
if ($questopic ['authorid'] != $_G ['uid'] && $_G ['adminid'] != 1 && $_G ['adminid'] != 2) {
	showmessage ( 'nds_up_ques:noopa' );
}
if (! $hook) {
	$ndsdetail = $questopic ['subject'];
	$ndsdetail .= "\n";
	$ndsdetail .= str_replace ( ",", "，", $questopic ['message'] );
	$ndsdetail .= "\n";
	$keys = '';
	$magiccount = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_user' ) . " WHERE `topicid`='$topicid'" ), 0 );
	$ndsdetail .= lang ( 'plugin/nds_up_ques', 'datas' ) . $magiccount . lang ( 'plugin/nds_up_ques', 'datas1' );
	$ndsdetail .= "\n";
	
	$query1 = DB::query ( " SELECT * FROM " . DB::table ( 'ques_option' ) . " WHERE `topicid`='$topicid'  ORDER by `order`" );
	$nid = 1;
	$tm = array ('uid', 'user_name' );
	$xx = array ('', '' );
	$qselect = $questopic ['ndsabc'] ? array ('A. ', 'B. ', 'C. ', 'D. ', 'E. ', 'F. ', 'G. ', 'H. ', 'I. ', 'J. ', 'K. ', 'L. ', 'M. ', 'N. ', 'O. ', 'P. ', 'Q. ', 'R. ', 'S. ', 'T. ', 'U. ', 'V. ', 'W. ', 'X. ', 'Y. ', 'Z. ' ) : array ();
	WHILE ( $quesoption = DB::fetch ( $query1 ) ) {
		$tm [] = $nid . '.' . str_replace ( ",", "，", $quesoption ['title'] );
		$xxid = 0;
		if (! in_array ( $quesoption ['type'], array (6, 7, 8 ) )) {
			$option = explode ( "\n", $quesoption ['option'] );
			if ($quesoption ['type'] == 10) {
				foreach ( explode ( "\n", $quesoption ['key'] ) as $key => $rt ) {
					foreach ( $option as $varoption ) {
						$xx [] = $qselect [$xxid] . str_replace ( ",", "，", trim ( $varoption ) );
						$xxid ? (($xxid == 2 && $key == 0) ? '' : $tm [] = '') : $tm [] = '___' . trim ( $rt );
						$useroption [$quesoption ['oid']] [$key] [$xxid] = trim ( $varoption );
						$optiontype [$quesoption ['oid']] = $quesoption ['type'];
						$xxid ++;
					}
					$xxid = 0;
				}
			} else {
				foreach ( $option as $varoption ) {
					$xx [] = $qselect [$xxid] . str_replace ( ",", "，", trim ( $varoption ) );
					if ($xxid)
						$tm [] = '';
					$useroption [$quesoption ['oid']] [$xxid] = trim ( $varoption );
					$optiontype [$quesoption ['oid']] = $quesoption ['type'];
					$xxid ++;
				}
			} //else 	
			if ($quesoption ['othinput'] == 2 || $quesoption ['othinput'] == 3) {
				$tm [] = '';
				$othinput [$quesoption ['oid']] = $quesoption ['othinput'];
				$xx [] = str_replace ( ",", "，", trim ( $varoption ) );
			}
		
		} else {
			$useroption [$quesoption ['oid']] [0] = 'NUL';
			$xx [] = 'NUL';
			$optiontype [$quesoption ['oid']] = $quesoption ['type'];
		}
		$nid ++;
	}
	$ndsdetail .= implode ( ',', $tm );
	$ndsdetail .= "\n";
	$ndsdetail .= implode ( ',', $xx );
	$ndsdetail .= "\n";
	unset ( $xx );
	$query2 = DB::query ( " SELECT * FROM " . DB::table ( 'ques_user' ) . " WHERE `topicid`='$topicid' ORDER by $orderby dateline " );
	while ( $quesuser = DB::fetch ( $query2 ) ) {
		$user = array ();
		$user [] = $quesuser ['authorid'];
		$user [] = $quesuser ['author'];
		foreach ( $useroption as $oid => $vararr ) {
			$answer = DB::fetch_first ( " SELECT answer,othinput FROM " . DB::table ( 'ques_result' ) . " WHERE `oid`='$oid' AND `qid`='$quesuser[qid]'" );
			$uidanswer = explode ( ",", $answer ['answer'] );
			switch ($optiontype [$oid]) {
				case 1 :
				case 2 :
				case 3 :
				case 4 :
				case 5 :
				case 9 :
					foreach ( $vararr as $varop ) {
						$varop = trim ( $varop );
						$nwdscn = 0;
						foreach ( $uidanswer as $varanswer ) {
							if ($varop == trim ( $varanswer )) {
								$user [] = '1';
								$nwdscn = 1;
							}
						}
						! $nwdscn ? $user [] = '' : '';
					}
					if ($othinput [$oid] == 2 || $othinput [$oid] == 3) {
						$user [] = str_replace ( ",", "，", $answer ['othinput'] );
					}
					break;
				case 10 :
					foreach ( $vararr as $key1 => $varopa ) {
						foreach ( $varopa as $varop ) {
							$varop = trim ( $varop );
							$nwdscn = 0;
							if ($varop == trim ( $uidanswer [$key1] )) {
								$user [] = '1';
								$nwdscn = 1;
							} else
								$user [] = '';
						}
					}
					break;
				case 6 :
				case 7 :
				case 8 :
					$user [] = str_replace ( ",", "，", $uidanswer [0] );
					break;
			} //switch
		} //foreach
		$ndsdetail .= implode ( ',', $user );
		unset ( $user );
		$ndsdetail .= "\n";
	} //while  
	$filename = 'ndsques' . $topicid . '_' . date ( 'Ymd', TIMESTAMP ) . '.csv';
} else {
	function makereport($oid, $title, $option, $type, $chmin, $chmax, $ndssum, $data = array(), $keyarray = '', $rtarray = array(), $total = 0, $nid, $ndsabc) {
		$qselect = array ('A. ', 'B. ', 'C. ', 'D. ', 'E. ', 'F. ', 'G. ', 'H. ', 'I. ', 'J. ', 'K. ', 'L. ', 'M. ', 'N. ', 'O. ', 'P. ', 'Q. ', 'R. ', 'S. ', 'T. ', 'U. ', 'V. ', 'W. ', 'X. ', 'Y. ', 'Z. ' );
		$list = '';
		$i = 1;
		$b = 0;
		$c = 0;
		$total = $total ? $total : 0;
		$optionarr = explode ( "\n", $option );
		$optlen = count ( $optionarr );
		$title = str_replace ( ",", "，", $title );
		if ($optlen > 25 || ! $ndsabc)
			$qselect = array ();
		$pp = '';
		switch ($type) {
			case 1 :
			case 2 :
			case 3 :
			case 4 :
			case 5 :
			case 6 :
			case 7 :
			case 9 :
				foreach ( explode ( "\n", $keyarray ) as $t ) {
					$t = trim ( $t );
					$t = str_replace ( ",", "，", $t );
					$pc = $total ? intval ( $data [$t] * 100 / $total ) : 0;
					$pp .= ',' . $qselect [$b ++] . $t . ',' . ($data [$t] ? $data [$t] : 0) . ',' . $pc . '%' . "\n";
				}
				break;
			case 8 :
				$pc = $ndssum / $total / ($chmax - $chmin + 1) * 100;
				$pp .= ',min:' . $chmin . '-- max:' . $chmax . ',' . $ndssum / $total . ',' . $pc . '%' . "\n";
				break;
			case 10 :
				$total2 = $total / count ( $rtarray );
				foreach ( $rtarray as $key => $rt ) {
					$pp .= trim ( $rt ) . ' ,' . ' ,' . $total2 . "\n";
					foreach ( explode ( "\n", $keyarray ) as $t ) {
						$t = trim ( $t );
						$t = str_replace ( ",", "，", $t );
						$pc = $total2 ? intval ( $data [$key] [$t] * 100 / $total2 ) : 0;
						$pp .= ',' . $qselect [$b ++] . $t . ',' . ($data [$key] [$t] ? $data [$key] [$t] : 0) . ',' . $pc . '%' . "\n";
					}
					$b = 0;
				}
				break;
		} // switch	 
		$list = $nid . '.' . $title . ', ,' . $total . "\n" . $pp;
		return $list;
	}
	$ndsdetail = $questopic ['subject'];
	$ndsdetail .= "\n";
	$ndsdetail .= str_replace ( ",", "，", $questopic ['message'] );
	$ndsdetail .= "\n";
	$magiccount = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_user' ) . " WHERE `topicid`='$topicid'" ), 0 );
	$ndsdetail .= lang ( 'plugin/nds_up_ques', 'datas' ) . $magiccount . lang ( 'plugin/nds_up_ques', 'datas1' );
	$ndsdetail .= "\n";
	$query3 = DB::query ( " SELECT * FROM " . DB::table ( 'ques_result' ) . " WHERE `topicid`='$topicid' ORDER BY `oid`" );
	$uu = $cc = $tt = array ();
	WHILE ( $quesoptions = DB::fetch ( $query3 ) ) {
		if ($quesoptions ['answer']) {
			Foreach ( explode ( ",", $quesoptions ['answer'] ) as $key => $vles ) {
				$vles = trim ( $vles );
				$uu [$quesoptions ['oid']] [$vles] = $vles;
				$uu10 [$quesoptions ['oid']] [$key] [$vles] = $vles;
				$cc [$quesoptions ['oid']] [$vles] ++;
				$cc10 [$quesoptions ['oid']] [$key] [$vles] ++;
				$tt [$quesoptions ['oid']] ++;
			}
		}
	}
	
	$ndsdetail .= 'Title,Option,Total/Avg,Proportion%';
	$ndsdetail .= "\n";
	
	$query2 = DB::query ( " SELECT * FROM " . DB::table ( 'ques_option' ) . " WHERE `topicid`='$topicid' AND `type` NOT IN (6,7) ORDER by `order`" );
	$nid = 1;
	WHILE ( $quesoption = DB::fetch ( $query2 ) ) {
		$k = $rtarray = array ();
		$total = 0;
		$ndssum = 0;
		if (is_array ( $uu [$quesoption ['oid']] )) {
			foreach ( $uu [$quesoption ['oid']] as $dis => $kles ) {
				if ($quesoption ['type'] == 8) {
					$ndssum += intval ( $kles );
				} elseif ($quesoption ['type'] == 10) {
					foreach ( $uu10 [$quesoption ['oid']] as $key => $keya1 ) {
						foreach ( $keya1 as $dis ) {
							$k [$key] [$dis] = $cc10 [$quesoption ['oid']] [$key] [$dis];
						}
					}
				} else {
					$dis = $dis;
					$k [$dis] = $cc [$quesoption ['oid']] [$dis];
				}
			} //foreach
		}
		$total = $tt [$quesoption ['oid']];
		$quesoption ['type'] == 10 ? $rtarray = explode ( "\n", $quesoption ['key'] ) : '';
		$ndsdetail .= makereport ( $quesoption ['oid'], $quesoption ['title'], $quesoption ['option'], $quesoption ['type'], $quesoption ['chmin'], $quesoption ['chmax'], $ndssum, $k, $quesoption ['option'], $rtarray, $total, $nid ++, $questopic ['ndsabc'] );
	}
	
	$filename = 'ndsstats' . $topicid . '_' . date ( 'Ymd', TIMESTAMP ) . '.csv';
	//!stats  
}
header ( "Content-type: application/vnd.ms-execl" );
header ( 'Content-Encoding: none' );
header ( 'Content-Disposition: attachment; filename=' . $filename );
header ( "Pragma: no-cache" );
header ( 'Expires: 0' );
if ($_G ['charset'] != 'gbk') {
	$ndsdetail = diconv ( $ndsdetail, $_G ['charset'], 'GBK' );
}
echo $ndsdetail;
exit ();
?>