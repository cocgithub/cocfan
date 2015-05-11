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
$optionlist = '';
$ismyanswer = 0;
$questopic = DB::fetch_first ( "SELECT * FROM " . DB::table ( 'ques_topic' ) . " WHERE `topicid`='$topicid' LIMIT 1" );
$sysmode = $questopic ['ques_mode'];
$questopic ['dateline'] = dgmdate ( $questopic ['dateline'] );
if ($questopic ['postmust']) {
	$t_author = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_user' ) . " WHERE `topicid`='$topicid' AND `authorid`='$_G[uid]' LIMIT 1" ), 0 );
	if (! $t_author && $questopic ['authorid'] != $_G ['uid'] && $_G ['adminid'] != 1 && $_G ['adminid'] != 2 && $questopic ['opcheckauthorid'] != $_G ['uid'])
		showmessage ( 'nds_up_ques:see' );
}
if ($questopic ['secret'] && ! $hook) {
	$t2_author = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_user' ) . " WHERE `qid` = '$qid' AND `topicid`='$topicid' AND `authorid`='$_G[uid]' LIMIT 1" ), 0 );
	if (! $t2_author && $questopic ['authorid'] != $_G ['uid'] && $_G ['adminid'] != 1 && $_G ['adminid'] != 2)
		showmessage ( 'nds_up_ques:secret' );
}
$isguest = empty ( $_G ['uid'] ) ? $questopic ['isguest'] : 0;
if ($hook) {
	if ($isguest) {
		if ($guestrp == 1) {
			$qid = DB::result ( DB::query ( "SELECT qid FROM " . DB::table ( 'ques_user' ) . " WHERE  authorid = 0 AND  `postip`='" . $_G ['clientip'] . "' AND `topicid`='$topicid' LIMIT 1" ), 0 );
		} elseif ($guestrp == 2) {
			if (! empty ( $_G ['cookie'] ['nds_guestrp'] )) {
				$qid = DB::result ( DB::query ( "SELECT qid FROM " . DB::table ( 'ques_user' ) . " WHERE  authorid = 0 AND `isguest`='" . $_G ['cookie'] ['nds_guestrp'] . "' AND `topicid`='$topicid' LIMIT 1" ), 0 );
			}
		}
	} else {
		$qid = DB::result ( DB::query ( "SELECT qid FROM " . DB::table ( 'ques_user' ) . " WHERE `topicid`='$topicid' AND `authorid`='$_G[uid]' LIMIT 1" ), 0 );
	}
}
if (! $qid)
	showmessage ( 'nds_up_ques:secret' );
$data = DB::fetch_first ( "SELECT * FROM " . DB::table ( 'ques_user' ) . " WHERE `topicid`='$topicid' AND `qid`='$qid' LIMIT 1" );
$data ['dateline'] = dgmdate ( $data ['dateline'] );
if ($questopic ['nwds'] == 2 || $questopic ['nwds'] == 4) {
	$ismark = ( int ) $data ['mark'];
	$strategy = getgz ( $ismark, $questopic ['strategy'] );
	$postdo = discuzcode ( $strategy [1], 0, 0, 0 );
}
//  if($questopic['nwds']==4 && !empty($postdo)){
//    showmessage('nds_up_ques:subok',$postdo.'&topicid='.$topicid.'&qid='.$qid,array(),array('header' => true));  
//  } //强制返回定制页面专用
$tmcmarks = array ();
if ($questopic ['ques_mode'] == 2) {
	$tmcmarksarr = explode ( ",", $data ['tmclassmark'] );
	foreach ( $tmcmarksarr as $tmcmarks2 ) {
		$tmcm = explode ( "=", $tmcmarks2 );
		$tmcmarks [trim ( $tmcm [0] )] = $tmcm [1];
	}
}
$tmclass = array ();
$tmcid = 0;
if ($questopic ['tmclass'] && ! empty ( $questopic ['tmtypetext'] )) {
	$tmclassarr = explode ( ",", $questopic ['tmtypetext'] );
	foreach ( $tmclassarr as $tmclass2 ) {
		$tmc = explode ( "=", $tmclass2 );
		$tmclass [trim ( $tmc [0] )] = $tmc [1];
	}
}

$questopic ['message'] = discuzcode ( $questopic ['message'], 0, 0, 0 );
$query1 = DB::query ( " SELECT * FROM " . DB::table ( 'ques_result' ) . " WHERE `topicid`='$topicid' AND `qid`='$qid'" );
$myanswer = array ();
$mymark = array ();
WHILE ( $quesoptions = DB::fetch ( $query1 ) ) {
	$sheet [$quesoptions ['oid']] = $quesoptions ['answer'];
	$othinput [$quesoptions ['oid']] = $quesoptions ['othinput'] ? $quesoptions ['othinput'] : '';
	if ($quesoptions ['authorid'] == $_G ['uid']) {
		$ismyanswer = 1;
		$myanswer [$quesoptions ['oid']] = $quesoptions ['answer'];
	}
}
$order = $questopic ['tmtypehd'] ? 'ORDER by `tmclass`,`order`' : 'ORDER by `order`';
$query2 = DB::query ( " SELECT * FROM " . DB::table ( 'ques_option' ) . " WHERE `topicid`='$topicid' " . $order );
$nid = 1;
WHILE ( $quesoption = DB::fetch ( $query2 ) ) {
	if ($sheet [$quesoption ['oid']]) {
		$mark = 0;
		$mymark = - 1;
		$mykey = array ();
		if ($myanswer [$quesoption ['oid']]) {
			$mykey = explode ( "\n", $quesoption ['key'] );
			$mymark = 0;
		}
		foreach ( explode ( "\n", $quesoption ['mark'] ) as $key => $value ) {
			$ismark = intval ( trim ( $value ) );
			if ($ismark > $mark)
				$mark = $ismark;
			$iskey = trim ( $mykey [$key] );
			$ismyanswer = trim ( $myanswer [$quesoption ['oid']] );
			if ($ismark && ! empty ( $ismyanswer ) && $iskey == $ismyanswer) {
				$mymark = $ismark;
			}
		}
		$keyarray = $quesoption ['type'] == 9 || $quesoption ['type'] == 10 ? $quesoption ['key'] : '';
		$dajxnty = '';
		if ($questopic ['ques_mode'] == 2 && $questopic ['seeanswer'] && $ismyanswer) {
			$quesoption ['type'] == 6 ? $keyarray = $quesoption ['key'] : $keyarray = $mykey [0];
			$dajxnty = empty ( $quesoption ['answer'] ) ? '' : discuzcode ( $quesoption ['answer'], 0, 0, 0 );
		}
		$quesoption ['title'] = discuzcode ( $quesoption ['title'], 0, 0, 0 );
		$quesoption ['desp'] = discuzcode ( stripslashes ( $quesoption ['desp'] ), 0, 0, 0 );
		if ($questopic ['tmtypehd'] && ! empty ( $tmclass ) && $quesoption ['tmclass']) {
			if ($tmcid != $quesoption ['tmclass'] && array_key_exists ( $quesoption ['tmclass'], $tmclass )) {
				$tmcid = $quesoption ['tmclass'];
				$optionlist .= '<tr class="fl_row2"><td><b class="btmc">' . $tmclass [$quesoption ['tmclass']] . ' ' . $tmcmarks [$quesoption ['tmclass']] . lang ( 'plugin/nds_up_ques', 'mark2' ) . '<b></td></tr>';
			}
		}
		$optionlist .= makeoption ( $quesoption ['oid'], $quesoption ['title'], $quesoption ['desp'], $quesoption ['option'], $quesoption ['type'], $quesoption ['least'], $quesoption ['chmin'], $quesoption ['chmax'], $quesoption ['othinput'], $quesoption ['textsize'], $quesoption ['textareawidth'], $sheet [$quesoption ['oid']], 'disabled', $stats = 1, $data2 = $othinput [$quesoption ['oid']], $keyarray, $dajxnty, 0, $questopic ['ndsabc'], $nid ++, $sysmode, $mark, $mymark, 0 );
	} //if($sheet[$quesoption['oid']]) {  
}
$navtitle = lang ( 'plugin/nds_up_ques', 'action_9' ) . ' - ' . $navtitle;
if ($hook == 1) {
	include template ( 'nds_up_ques:ques_scan_hook' );
} else {
	include template ( 'nds_up_ques:ques_scan' );
}

?>