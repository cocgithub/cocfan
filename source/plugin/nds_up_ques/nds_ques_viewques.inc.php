<?PHP
/*  nds_up_ques  v3.2
 *  Plugin FOR Discuz! X 
 *	WWW.NWDS.CN | NDS.西域数码工作室 
 *  Plugin update 20130712 BY singcee
 */
if (! defined ( 'IN_DISCUZ' )) {
	exit ( 'Access Denied' );
}
include_once DISCUZ_ROOT . './source/function/function_discuzcode.php';
include_once DISCUZ_ROOT . './source/plugin/nds_up_ques/nds_ques_function.php';
$nothook = 0;
$havedone = 0;
$timemm = 0;
$timess = 0;
$timermm = 0;
$timerss = 0;
$myqan = $rws ? 'ndsques_myqa-' . $topicid . '.html' : 'plugin.php?id=nds_up_ques&action=scan&topicid=' . $topicid . '&hook=2';
if (! empty ( $_G ['uid'] )) {
	$count = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_user' ) . " WHERE `topicid`='$topicid' AND `authorid`='$_G[uid]' LIMIT 1" ), 0 );
	$havedone = $count ? 1 : 0;
}
$questopic = DB::fetch_first ( "SELECT * FROM " . DB::table ( 'ques_topic' ) . " WHERE`topicid`='$topicid'" );
$sysmode = $questopic ['ques_mode'];
$questopic ['dateline'] = dgmdate ( $questopic ['dateline'] );
$secret = $questopic ['secret'];
if ($questopic ['stop'] && $_G ['adminid'] != 1 && $questopic ['authorid'] != $_G ['uid'] && ! in_array ( $_G ['groupid'], $ndsquesadmins )) {
	showmessage ( 'nds_up_ques:notice4', 'plugin.php?id=nds_up_ques', '', array ('alert' => 'error' ) );
}
$timeleft = $questopic ['timeleft'] ? ( int ) $questopic ['timeleft'] : 0;
if ($timeleft) {
	if (! isset ( $_G ['cookie'] ['nds_timerigh' . $topicid] )) {
		dsetcookie ( 'nds_timerigh' . $topicid, $_G ['timestamp'] + $timeleft * 60, $timeleft * 60 + 20 );
		$nds_timerigh = $_G ['timestamp'] + $timeleft * 60;
	} else {
		$nds_timerigh = $_G ['cookie'] ['nds_timerigh' . $topicid];
	}
	$timemm = intval ( ($nds_timerigh - $_G ['timestamp']) / 60 );
	$timess = ($nds_timerigh - $_G ['timestamp']) % 60;
	$timermm = $timeleft - $timemm - 1;
	$timerss = 60 - $timess;
} else {
	if (! isset ( $_G ['cookie'] ['nds_timerigh' . $topicid] )) {
		dsetcookie ( 'nds_timerigh' . $topicid, $_G ['timestamp'], 60 * 60 );
		$nds_timerigh = $_G ['timestamp'];
	} else {
		$nds_timerigh = $_G ['cookie'] ['nds_timerigh' . $topicid];
	}
	$timermm = intval ( ($_G ['timestamp'] - $nds_timerigh) / 60 );
	$timerss = ($_G ['timestamp'] - $nds_timerigh) % 60;
}
$isguest = empty ( $_G ['uid'] ) ? $questopic ['isguest'] : 0;
if ($isguest) {
	if ($guestrp == 1) {
		$count = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_user' ) . " WHERE  `postip`='" . $_G ['clientip'] . "' AND `topicid`='$topicid' LIMIT 1" ), 0 );
	} elseif ($guestrp == 2) {
		if (! empty ( $_G ['cookie'] ['nds_guestrp'] )) {
			$count = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_user' ) . " WHERE  authorid = 0 AND `isguest`='" . $_G ['cookie'] ['nds_guestrp'] . "' AND `topicid`='$topicid' LIMIT 1" ), 0 );
		}
	}
	$havedone = $count ? 1 : 0;
}
if ($ndsopcheck && ! $questopic ['opcheck'] && ! in_array ( $_G ['groupid'], $ndsquesadmins ) && $_G ['adminid'] != 1) {
	if ($hook) {
		$nothook = 1;
	} else {
		showmessage ( 'nds_up_ques:notopcheck', '', '', array ('alert' => 'error' ) );
	}
}
if ($questopic ['expenses'] && $_G ['adminid'] != 1 && $questopic ['authorid'] != $_G ['uid']) {
	$dexpenses = 0;
	if ($expensesmod == '1') {
		$count = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_countlog' ) . " WHERE `topicid`='$topicid' AND `uid`='$_G[uid]' and `tag` = 0 LIMIT 1" ), 0 );
		if ($count < 1) {
			$dexpenses = 1;
		}
	} elseif ($expensesmod == '2') {
		$count = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_countlog' ) . " WHERE `topicid`='$topicid' AND `uid`='$_G[uid]' LIMIT 1" ), 0 );
		if ($count < 1) {
			$dexpenses = 1;
		}
	}
	if (empty ( $_G ['uid'] ))
		$dexpenses = 1;
	$dexpenses ? showmessage ( 'nds_up_ques:nds_paymess', 'plugin.php?id=nds_up_ques', '', array ('alert' => 'error' ) ) : '';
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

if (! submitcheck ( 'viewquessubmit' )) {
	$quesoptionlist = '';
	$nid = 1;
	$optionlist = '';
	$tag = $questopic ['ndsflag'];
	$questopic ['exps'] = $questopic ['exp'] == 1 ? lang ( 'plugin/nds_up_ques', 'nds_explong' ) : dgmdate ( $questopic ['exp'] );
	$questopic ['message'] = discuzcode ( $questopic ['message'], 0, 0, 0 );
	$order = $questopic ['tmtypehd'] ? 'ORDER by `tmclass`,`order`' : 'ORDER by `order`';
	$query1 = DB::query ( " SELECT * FROM " . DB::table ( 'ques_option' ) . " WHERE `topicid`='$topicid' " . $order );
	WHILE ( $options = DB::fetch ( $query1 ) ) {
		if ($questopic ['tmtypehd'] && ! empty ( $tmclass ) && $options ['tmclass']) {
			if ($tmcid != $options ['tmclass'] && array_key_exists ( $options ['tmclass'], $tmclass )) {
				$tmcid = $options ['tmclass'];
				$optionlist .= '<tr class="fl_row2"><td><b class="btmc">' . $tmclass [$options ['tmclass']] . '<b></td></tr>';
			}
		}
		$mark = 0;
		foreach ( explode ( "\n", $options ['mark'] ) as $value ) {
			if (trim ( $value ) != '' && ( int ) $value > $mark)
				$mark = trim ( $value );
		}
		$showright = $questopic ['ndsright'];
		$dajxnty = $questopic ['seeanswer'];
		$flag = $options ['least'] ? 't' : 'f';
		$type = $options ['type'];
		$keyarray = $type == 9 || $type == 10 ? $options ['key'] : '';
		$desp = discuzcode ( stripslashes ( $options ['desp'] ), 0, 0, 0 );
		$options ['title'] = discuzcode ( $options ['title'], 0, 0, 0 );
		$type == 10 ? $options ['chmin'] = count ( explode ( "\n", $keyarray ) ) : '';
		$optionlist .= '<div id="ndsdiv"  name="ndsdiv" old="' . $options [oid] . '" flag ="' . $flag . '" istyep="' . $type . '" jchmax="' . $options ['chmin'] . '">';
		$optionlist .= makeoption ( $options ['oid'], $options ['title'], $desp, $options ['option'], $options ['type'], $options ['least'], $options ['chmin'], $options ['chmax'], $options ['othinput'], $options ['textsize'], $options ['textareawidth'], $input = '', $disable = '', $stats = 0, $data = '', $keyarray, $dajxnty, $showright, $questopic ['ndsabc'], $nid ++, $sysmode, $mark, - 1, $tag );
		$optionlist .= '</div>';
	}
	$mischeck2 = lang ( 'plugin/nds_up_ques', 'check2' );
} else {
	if ($ndsopcheck && ! $questopic [opcheck]) {
		showmessage ( 'nds_up_ques:notopcheck', '', '', array ('alert' => 'error' ) );
	}
	if ($havedone)
		showmessage ( 'nds_up_ques:notice1', '', '', array ('alert' => 'error' ) );
	if ($questopic ['stop'])
		showmessage ( 'nds_up_ques:notice4', '', '', array ('alert' => 'error' ) );
	if (! $questopic ['person'])
		showmessage ( 'nds_up_ques:notice3', '', '', array ('alert' => 'error' ) );
	if ($questopic ['exp'] != 1 && $questopic ['exp'] < $_G ['timestamp'])
		showmessage ( 'nds_up_ques:notice6', '', '', array ('alert' => 'error' ) );
	if (empty ( $_G ['uid'] ) && ! $isguest)
		showmessage ( lang ( 'plugin/nds_up_ques', 'notice9' ), 'member.php?mod=logging&action=login', array (), array ('showmsg' => true, 'login' => 1 ) );
	if (! in_array ( $_G ['groupid'], $allowsubmit ))
		showmessage ( 'nds_up_ques:notice2', '', '', array ('alert' => 'error' ) );
	
	$marks = 0;
	$query1 = DB::query ( " SELECT * FROM " . DB::table ( 'ques_option' ) . " WHERE `topicid`='$topicid' ORDER by `order`" );
	WHILE ( $currans = DB::fetch ( $query1 ) ) {
		$j = 0;
		$selectmust [$currans ['oid']] = trim ( $currans ['least'] );
		$optype [$currans ['oid']] = $currans ['type'];
		
		if ($currans ['type'] < 7) {
			foreach ( explode ( "\n", $currans ['key'] ) as $t ) {
				$j ++;
				if (trim ( $t )) {
					$currect [$currans ['oid']] [$j] = trim ( $t );
				} else {
					$currect [$currans ['oid']] [$j] = $t;
				}
			}
			$j = 0;
			foreach ( explode ( "\n", $currans ['mark'] ) as $t ) {
				$j ++;
				$cu [$currans ['oid']] [$currect [$currans ['oid']] [$j]] = $t;
			}
		} else {
			$currect [$currans ['oid']] [1] = $currans ['key'];
			$cu [$currans ['oid']] [$currect [$currans ['oid']] [1]] = $currans ['mark'];
		}
	
	}
	$timer = cutstr ( $_G ['gp_timerinput'], 6 );
	$author = $_G ['username'];
	$uid = $_G ['uid'];
	$guestsid = '0';
	if ($isguest) {
		$author = lang ( 'plugin/nds_up_ques', 'nds_guest2' ) . '_' . $_G ['sid'];
		$uid = '0';
		$guestsid = $_G ['sid'];
	}
	DB::query ( "INSERT INTO " . DB::table ( 'ques_user' ) . " (`topicid`,`author`, `authorid`, `dateline`, `mark`, `isguest`, `postip`,`timer`) VALUES ('$topicid','$author', '$uid', '$_G[timestamp]', '$marks', '$guestsid', '$_G[clientip]','$timer')" );
	$qid = DB::insert_id ();
	$query1 = DB::query ( " SELECT oid,tmclass FROM " . DB::table ( 'ques_option' ) . " WHERE `topicid`='$topicid' ORDER by `order`" );
	$tmcmarks = array ();
	WHILE ( $ooo = DB::fetch ( $query1 ) ) {
		$id = $ooo ['oid'];
		$answer = '';
		$comma = '';
		$answer2 = '';
		$othinput = '';
		if (is_array ( $_G ['gp_suboption'] [$id] )) {
			$answer2 = implode ( ",", $_G ['gp_suboption'] [$id] );
			foreach ( $_G ['gp_suboption'] [$id] as $bbl ) {
				$answer .= $comma . trim ( $bbl );
				$comma = ',';
			}
		} elseif ($_G ['gp_suboption'] [$id]) {
			$answer2 = $_G ['gp_suboption'] [$id];
			$answer = $_G ['gp_suboption'] [$id];
		}
		
		if (isset ( $_G ['gp_otinput'] [$id] ))
			$othinput = dhtmlspecialchars ( $_G ['gp_otinput'] [$id] );
		
		if ($selectmust [$id] && ! $answer && ! $timeleft) {
			DB::query ( "DELETE FROM " . DB::table ( 'ques_user' ) . " WHERE qid='$qid' LIMIT 1" );
			DB::query ( "DELETE FROM " . DB::table ( 'ques_result' ) . " WHERE qid='$qid'" );
			showmessage ( 'nds_up_ques:emptyoption', '', '', array ('alert' => 'error' ) );
		}
		if ($cu [$id] [trim ( $answer )]) {
			$ismarks = $cu [$id] [trim ( $answer )];
		} else {
			$ismarks = $cu [$id] [$answer];
		}
		$marks += $ismarks;
		if ($ooo ['tmclass'] && $ismarks) {
			$tmcmarks [$ooo ['tmclass']] += $ismarks;
		}
		if ($optype [$id] == 6 || $optype [$id] == 7)
			$answer2 = dhtmlspecialchars ( $answer2 );
		
		if ($answer2)
			DB::query ( "INSERT INTO " . DB::table ( 'ques_result' ) . " (`topicid`,`qid`,`oid`,`authorid`,`answer`,`othinput`) VALUES ('$topicid','$qid','$id','$uid','$answer2','$othinput')" );
	}
	$usertmc = '';
	if ($sysmode == 2 && ! empty ( $tmclass )) {
		foreach ( $tmclass as $key => $vale ) {
			$ndstmcmarks = $tmcmarks [$key] > 0 ? $tmcmarks [$key] : 0;
			$usertmc .= $key . '=' . $ndstmcmarks . ',';
		}
	}
	if ($sysmode == 1) {
		$marks = $questopic ['qreward'];
		$marks > $creditmax ? $marks = $creditmax : '';
	}
	DB::query ( "UPDATE " . DB::table ( 'ques_user' ) . " SET `mark`='$marks',`tmclassmark`='$usertmc' WHERE `qid`='$qid'" );
	DB::query ( "UPDATE " . DB::table ( 'ques_topic' ) . " SET `person`=person-1 WHERE `topicid`='$topicid'" );
	$markjf = $marks;
	$postdo = '';
	if ($sysmode == 2 && $questopic ['nwds'] > 1 && ! empty ( $questopic ['strategy'] )) {
		$strategy = getgz ( $marks, $questopic ['strategy'] );
		$markjf = $strategy [0];
		$postdo = $strategy [1];
	}
	
	if ($_G ['uid'] && $cvar && $marks > 0 && $questopic ['credit'] && in_array ( $_G ['member'] ['groupid'], $allowcredit )) {
		$extcreditn = 'extcredits' . $cvar;
		if ($creditmax && $markjf > $creditmax)
			$markjf = $creditmax;
		$extcreditarr = array ($extcreditn => $markjf );
		$ruletx = lang ( 'plugin/nds_up_ques', 'answermarks' );
		if ($_G ['charset'] == 'gbk') {
			$ruletxt = iconv ( "GBK", "UTF-8", "$ruletx" );
		}
		updatemembercount ( $_G ['uid'], $extcreditarr, $checkgroup = false, '', '', $ruletxt );
		$logdata = array ('uid' => $_G ['uid'], 'topicid' => $topicid, 'type' => 1, 'num' => $markjf, 'dateline' => $_G ['timestamp'] );
		DB::insert ( 'ques_countlog', $logdata, true );
	}
	DB::query ( "UPDATE " . DB::table ( 'ques_topic' ) . " SET `post_count`= post_count + 1 WHERE `topicid`='$topicid'" );
	if ($isguest && $guestrp == 2)
		dsetcookie ( 'nds_guestrp', $_G ['sid'], 2592000 );
	if ($hook) {
		header ( "Location: plugin.php?id=nds_up_ques&action=stats&hook=1&topicid=" . $topicid );
	} else {
		if ($sysmode == 1) {
			if (! empty ( $questopic ['posturl'] )) {
				header ( "Location: " . $questopic ['posturl'] );
			} elseif (! $questopic ['isprivate'])
				header ( "Location: " . $sturl );
			elseif (! $questopic ['secret']) {
				showmessage ( 'nds_up_ques:subok', $myqan );
			} else {
				showmessage ( 'nds_up_ques:subok', $vqurl );
			}
		} else {
			if ($questopic ['nwds'] && ! empty ( $postdo )) {
				switch ($questopic ['nwds']) {
					case 1 :
						showmessage ( 'nds_up_ques:subok', $myqan );
						break;
					case 2 :
						$showpostdo = discuzcode ( $postdo, 0, 0, 0 );
						showmessage ( 'nds_up_ques:nds_postdo', $myqan, array ('marks' => $marks, 'postdo' => $showpostdo ), array ('showdialog' => true, 'locationtime' => true ) );
						break;
					case 3 :
						showmessage ( 'nds_up_ques:subok', $postdo, array (), array ('header' => true ) );
						break;
					case 4 :
						showmessage ( 'nds_up_ques:subok', $postdo . '&topicid=' . $topicid . '&qid=' . $qid, array (), array ('header' => true ) );
						break;
				} //  switch	  	
			} else {
				showmessage ( 'nds_up_ques:subok', $myqan );
			}
		}
	} // not $hook
}
$navtitle = $questopic ['subject'] . ' - ' . $navtitle;
if (! $hook) {
	include template ( 'nds_up_ques:ques_viewques' );
} else {
	if (! $nothook) {
		include template ( 'nds_up_ques:ques_viewques_hook' );
	}

}
?>