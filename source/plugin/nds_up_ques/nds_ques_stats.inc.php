<?PHP
/*  nds_up_ques  v3.1
 *  Plugin FOR Discuz! X 
 *	WWW.NWDS.CN | NDS.西域数码工作室 
 *  Plugin update 20121212 BY singcee
 */
if (! defined ( 'IN_DISCUZ' )) {
	exit ( 'Access Denied' );
}

include_once DISCUZ_ROOT . './source/function/function_discuzcode.php';
include_once DISCUZ_ROOT . './source/plugin/nds_up_ques/nds_ques_function.php';

$postmust = 0;
$optionlist = '';
$isprivate = 0;
$questopic = DB::fetch_first ( "SELECT * FROM " . DB::table ( 'ques_topic' ) . " WHERE`topicid`='$topicid' LIMIT 1" );
$questopic ['message'] = discuzcode ( $questopic ['message'], 0, 0, 0 );
$secret = $questopic ['secret'];
$isguest = empty ( $_G ['uid'] ) ? $questopic ['isguest'] : 0;
if ($questopic ['postmust']) {
	if (empty ( $_G ['uid'] ) && ! $isguest) {
		if ($hook) {
			$postmust = 1;
		} else {
			showmessage ( 'nds_up_ques:see' );
		}
	} else {
		if ($isguest) {
			if ($guestrp == 1) {
				$count = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_user' ) . " WHERE  authorid = 0 AND `postip`='" . $_G ['clientip'] . "' AND `topicid`='$topicid' LIMIT 1" ), 0 );
			} elseif ($guestrp == 2) {
				if (! empty ( $_G ['cookie'] ['nds_guestrp'] )) {
					$count = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_user' ) . " WHERE  authorid = 0 AND `isguest`='" . $_G ['cookie'] ['nds_guestrp'] . "' AND `topicid`='$topicid' LIMIT 1" ), 0 );
				}
			}
		} else {
			$count = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_user' ) . " WHERE `topicid`='$topicid' AND `authorid`='$_G[uid]' LIMIT 1" ), 0 );
		}
		$havedone = $count ? 1 : 0;
		
		if (! $havedone && $questopic ['authorid'] != $_G ['uid'] && $_G ['adminid'] != 1 && $_G ['adminid'] != 2) {
			if ($hook) {
				$postmust = 1;
			} else {
				showmessage ( 'nds_up_ques:see' );
			}
		}
	} //else 
}
// 统计结果保密
if ($questopic ['isprivate']) {
	if (empty ( $_G ['uid'] )) {
		if ($hook) {
			$isprivate = 1;
		} else {
			showmessage ( $questopic ['isprivatetext'] );
		}
	}
	if ($questopic ['authorid'] != $_G ['uid'] && $_G ['adminid'] != 1 && $questopic ['opcheckauthorid'] != $_G ['uid']) {
		if ($hook) {
			$isprivate = 1;
		} else {
			showmessage ( $questopic ['isprivatetext'] );
		}
	}
}

$magiccount = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_user' ) . " WHERE `topicid`='$topicid'" ), 0 );

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
		$nwds [$quesoptions ['oid']] [$quesoptions ['qid']] = $quesoptions ['answer'];
	}
}

$query2 = DB::query ( " SELECT * FROM " . DB::table ( 'ques_option' ) . " WHERE `topicid`='$topicid' AND `type` NOT IN (6,7) ORDER by `order`" );
$nid = 1;
WHILE ( $quesoption = DB::fetch ( $query2 ) ) {
	$k = $rtarray = array ();
	$total = 0;
	$ndssum = 0;
	if (is_array ( $uu [$quesoption ['oid']] )) {
		if ($quesoption ['type'] == 8) {
			foreach ( $nwds [$quesoption ['oid']] as $kles ) {
				$ndssum += intval ( $kles );
			}
		} elseif ($quesoption ['type'] == 10) {
			foreach ( $uu10 [$quesoption ['oid']] as $key => $keya1 ) {
				foreach ( $keya1 as $dis ) {
					$k [$key] [$dis] = $cc10 [$quesoption ['oid']] [$key] [$dis];
				}
			}
		} else {
			foreach ( $uu [$quesoption ['oid']] as $dis => $kles ) {
				$k [$dis] = $cc [$quesoption ['oid']] [$dis];
			}
		}
	
	}
	$total = $tt [$quesoption ['oid']];
	$quesoption ['title'] = discuzcode ( $quesoption ['title'], 0, 0, 0 );
	$quesoption ['desp'] = discuzcode ( stripslashes ( $quesoption ['desp'] ), 0, 0, 0 );
	$quesoption ['type'] == 10 ? $rtarray = explode ( "\n", $quesoption ['key'] ) : '';
	$optionlist .= makereport ( $quesoption ['oid'], $quesoption ['title'], $quesoption ['desp'], $quesoption ['option'], $quesoption ['type'], $quesoption ['least'], $quesoption ['chmin'], $quesoption ['chmax'], $ndssum, 1, $k, $quesoption ['option'], $rtarray, $total, $nid ++, $questopic ['ndsabc'] );
}
$navtitle = lang ( 'plugin/nds_up_ques', 'action_4' ) . ' - ' . $navtitle;
if (! $hook) {
	include template ( 'nds_up_ques:ques_stats' );
} else {
	include template ( 'nds_up_ques:ques_stats_hook' );
}

?>