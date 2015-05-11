<?PHP
/*  nds_up_ques  v3.2
 *  Plugin FOR Discuz! X 
 *	WWW.NWDS.CN | NDS.西域数码工作室 
 *  Plugin update 20131012 BY singcee
 */
if (! defined ( 'IN_DISCUZ' )) {
	exit ( 'Access Denied' );
}
include_once DISCUZ_ROOT . './source/plugin/nds_up_ques/nds_function.php';
if (! submitcheck ( 'newquessubmit' )) {
	if (empty ( $_G ['uid'] ) || ! in_array ( $_G ['member'] ['groupid'], $allowpost ))
		showmessage ( 'nds_up_ques:no_access', 'plugin.php?id=nds_up_ques:nds_up_ques', '', array ('alert' => 'error' ) );
	$mischecktm = lang ( 'plugin/nds_up_ques', 'checktm' );
} else {
	$subject = $_G ['gp_subject'];
	if ($_G ['adminid'] != 1)
		$subject = cutstr ( dhtmlspecialchars ( $subject ), 150 );
	$qclass = intval ( $_G ['gp_quesclass'] );
	$person = intval ( $_G ['gp_person'] );
	$exp = ndsmktime ( $_G ['gp_exp'] );
	if (! $exp)
		showmessage ( 'nds_up_ques:nds_exptimeerr', '', '', array ('alert' => 'error' ) );
	$tid = intval ( $_G ['gp_tid'] );
	$tid = $tid ? $tid : 0;
	$message = $_G ['gp_message'];
	if ($_G ['adminid'] != 1)
		$message = cutstr ( dhtmlspecialchars ( $message ), 500 );
	$credit = $_G ['gp_credit'] ? 1 : 0;
	$postmust = $_G ['gp_postmust'] ? 1 : 0;
	$secret = $_G ['gp_secret'] ? 1 : 0;
	$copyauthor = cutstr ( dhtmlspecialchars ( $_G ['gp_copyauthor'] ), 20 );
	$credit ? $qreward = intval ( $_G ['gp_qreward'] ) : 0;
	$ques_mode = intval ( $_G ['gp_ques_mode'] );
	$isprivate = $_G ['gp_isprivate'] ? 1 : 0;
	$isprivatetext = $isprivate ? cutstr ( dhtmlspecialchars ( $_G ['gp_isprivatetext'] ), 120 ) : '';
	$seeanswer = $_G ['gp_seeanswer'] ? 1 : 0;
	$ndsflag = $_G ['gp_ndsflag'] ? 1 : 0;
	$ndsright = $_G ['gp_ndsright'] ? 1 : 0;
	if ($tid && $ndsright) showmessage ( 'nds_up_ques:nds_tidnotright', '', '', array ('alert' => 'error' ) );
	$tmclass = $_G ['gp_tmtype'] ? 1 : 0;
	$tmtypehd = $_G ['gp_tmtypehd'] ? 1 : 0;
	$timeleft = $_G ['gp_timeleft'] ? intval ( $_G ['gp_timeleft'] ) : 0;
	$expenses = $_G ['gp_expenses'] ? intval ( $_G ['gp_expenses'] ) : 0;
	$nwds = $_G ['gp_nwds'] ? intval ( $_G ['gp_nwds'] ) : 0;
	$strategy = $ques_mode == 2 ? dhtmlspecialchars ( $_G ['gp_strategy'] ) : '';
	$posturl = $ques_mode == 1 ? cutstr ( trim ( $_G ['gp_posturl'] ), 150 ) : '';
	$tmtypetext = $tmclass ? cutstr ( dhtmlspecialchars ( str_replace ( "，", ",", $_G ['gp_tmtypetext'] ) ), 200 ) : '';
	if (! $_G ['gp_person'])
		showmessage ( 'nds_up_ques:noperson', '', '', array ('alert' => 'error' ) );
	if (! $_G ['gp_subject'])
		showmessage ( 'nds_up_ques:nosubject', '', '', array ('alert' => 'error' ) );
	if ($ndsopenclass && ! $_G ['gp_subject'])
		showmessage ( 'nds_up_ques:noqclass', '', '', array ('alert' => 'error' ) );
	if ($tid) {
		if (! in_array ( $_G ['groupid'], $allowhookgroups ))
			showmessage ( 'nds_up_ques:notice7', '', '', array ('alert' => 'error' ) );
		$questid = DB::fetch_first ( "SELECT fid,authorid FROM " . DB::table ( 'forum_thread' ) . " WHERE `tid`='$tid' " );
		if (! $questid ['fid'])
			showmessage ( 'nds_up_ques:notidkook2', '', '', array ('alert' => 'error' ) );
		if ($_G ['adminid'] != 1 && (! in_array ( $questid ['fid'], $allowhookforums ) || $_G ['uid'] != $questid ['authorid']))
			showmessage ( 'nds_up_ques:notidkook1', '', '', array ('alert' => 'error' ) );
	}
	if (is_array ( $_G ['gp_newtitle'] )) {
		if (implode ( "", $_G ['gp_newtitle'] ) == '')
			showmessage ( 'nds_up_ques:vilques', '', '', array ('alert' => 'error' ) );
		$tdata = array ('subject' => $subject, 'qclass' => $qclass, 'opcheck' => 0, 'message' => $message, 'author' => $_G [username], 'authorid' => $_G [uid], 'dateline' => $_G ['timestamp'], 'person' => $person, 'stop' => 0, 'credit' => $credit, 'postmust' => $postmust, 'secret' => $secret, 'exp' => $exp, 'tid' => $tid, 'copyauthor' => $copyauthor, 'ques_mode' => $ques_mode, 'qreward' => $qreward, 'isprivate' => $isprivate, 'isprivatetext' => $isprivatetext, 'seeanswer' => $seeanswer, 'ndsflag' => $ndsflag, 'ndsright' => $ndsright, 'tmclass' => $tmclass, 'tmtypehd' => $tmtypehd, 'tmtypetext' => $tmtypetext, 'timeleft' => $timeleft, 'expenses' => $expenses, 'nwds' => $nwds, 'strategy' => $strategy, 'posturl' => $posturl, 'userrepost' => $_G ['gp_userrepost'] ? 1 : 0, 'ndsabc' => $_G ['gp_ndsabc'] ? 1 : 0, 'isguest' => $_G ['gp_isguest'] ? 1 : 0 );
		$topicid = DB::insert ( 'ques_topic', $tdata, true );
		foreach ( $_G ['gp_newtitle'] as $k => $v ) {
			if ($v && ! (($_G ['gp_newtypes'] [$k] < 6) && empty ( $_G ['gp_newoptions'] [$k] ))) {
				if ($_G ['adminid'] != 1)
					$_G ['gp_newdesp'] [$k] = htmlspecialchars ( $_G ['gp_newdesp'] [$k] );
				if ($_G ['adminid'] != 1)
					$_G ['gp_newoptions'] [$k] = dhtmlspecialchars ( $_G ['gp_newoptions'] [$k] );
				$gp_options = preg_replace ( '/"([^"]*)"/', '“${1}”', $_G ['gp_newoptions'] [$k] );
				$gp_options = str_replace ( "'", "‘", $gp_options );
				$ques_mode == 2 ? $gp_options = str_replace ( ",", "，", $gp_options ) : '';
				$gp_options = dhtmlspecialchars ( $gp_options );
				$gp_keys = preg_replace ( '/"([^"]*)"/', '“${1}”', $_G ['gp_newkeys'] [$k] );
				$gp_keys = str_replace ( "'", "‘", $gp_keys );
				$gp_keys = dhtmlspecialchars ( $gp_keys );
				$gp_type = intval ( $_G ['gp_newtypes'] [$k] );
				if ($gp_type == 8) {
					$gp_chmin = intval ( $_G ['gp_newhdmin'] [$k] );
					$gp_chmax = intval ( $_G ['gp_newhdmax'] [$k] );
					$gp_textsize = intval ( $_G ['gp_newhdkld'] [$k] );
					$gp_othinput = intval ( $_G ['gp_newhdtstyle'] [$k] );
				} else {
					$gp_chmin = intval ( $_G ['gp_newchmin'] [$k] );
					$gp_chmax = intval ( $_G ['gp_newchmax'] [$k] );
					$gp_othinput = intval ( $_G ['gp_newothinput'] [$k] );
					$gp_textsize = intval ( $_G ['gp_newtextsize'] [$k] );
					if ($gp_type == 2 || $gp_type == 4) {
						$gp_textsize = intval ( $_G ['gp_newselstyle'] [$k] );
					}
					if ($gp_type == 9) {
						$gp_othinput = intval ( $_G ['gp_newimgstyle'] [$k] );
					}
					if ($gp_type == 6) {
						$gp_othinput = intval ( $_G ['gp_newtktstyle'] [$k] );
					}
				}
				//$gp_options = dhtmlspecialchars($_G['gp_options'][$k]);
				//$gp_keys = dhtmlspecialchars($_G['gp_keys'][$k]);
				

				$data = array ('topicid' => $topicid, 'title' => dhtmlspecialchars ( $_G ['gp_newtitle'] [$k] ), 'desp' => $_G ['gp_newdesp'] [$k], //if admin = 1 discuzcode
'mark' => dhtmlspecialchars ( $_G ['gp_newmark'] [$k] ), 'option' => $gp_options, 'key' => $gp_keys, 'answer' => dhtmlspecialchars ( $_G ['gp_newanswer'] [$k] ), 'order' => intval ( $_G ['gp_neworder'] [$k] ), 'least' => intval ( $_G ['gp_newleast'] [$k] ), 'type' => $gp_type, 'chmin' => $gp_chmin, 'chmax' => $gp_chmax, 'othinput' => $gp_othinput, 'textsize' => $gp_textsize, 'textareawidth' => intval ( $_G ['gp_newtextareawidth'] [$k] ), 'tmclass' => intval ( $_G ['gp_newtmclass'] [$k] ) );
				$oid = DB::insert ( 'ques_option', $data, true );
			}
		}
	}
	showmessage ( 'nds_up_ques:postok', 'plugin.php?id=nds_up_ques&module=nds_up_ques&action=view&topicid=' . $topicid . '', '' );

}
$navtitle = lang ( 'plugin/nds_up_ques', 'action_2' ) . ' - ' . $navtitle;
include template ( 'nds_up_ques:ques_newques' );
	
