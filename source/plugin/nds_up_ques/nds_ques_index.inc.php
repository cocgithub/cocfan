<?PHP
/*  nds_up_ques  v3.2
 *  Plugin FOR Discuz! X 
 *	WWW.NWDS.CN | NDS.西域数码工作室 
 *  Plugin update 20131011 BY singcee
 */
if (! defined ( 'IN_DISCUZ' )) {
	exit ( 'Access Denied' );
}
$tid = intval ( $_G ['gp_typeid'] );
$wherestr = $tid ? " AND qclass = '$tid'" : '';
$myqtid = intval ( $_G ['gp_myqt'] );
$wherestr .= $myqtid == 2 ? " and  authorid = " . $_G ['uid'] : '';
$rlurl = $rws ? 'ndsques_rl_all.html' : 'plugin.php?id=nds_up_ques&action=ranklist';
! empty ( $_G ['gp_srchtxt'] ) ? $wherestr .= " AND  subject like '%" . dhtmlspecialchars ( trim ( substr ( $_GET ['srchtxt'], 0, 20 ) ) ) . "%' " : '';
if ($myqtid == 3) {
	$magiccount = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_topic' ) . " t
			LEFT JOIN " . DB::table ( ques_user ) . " u ON u.topicid = t.topicid
			WHERE u.authorid = '$_G[uid]'" . $wherestr ), 0 );
	$sql = "SELECT t.* FROM " . DB::table ( 'ques_topic' ) . " t LEFT JOIN " . DB::table ( ques_user ) . " u ON u.topicid = t.topicid 
 	        WHERE u.authorid = '$_G[uid]' " . $wherestr . " ORDER by t.dateline DESC LIMIT $start_limit,$perpage";
} elseif ($myqtid == 4) {
	$mytopicids = '0';
	$glue = ',';
	$query = DB::query ( "SELECT topicid  FROM " . DB::table ( 'ques_user' ) . " WHERE authorid = '$_G[uid]'" );
	while ( $value = DB::fetch ( $query ) ) {
		$mytopicids .= $glue . $value ['topicid'];
	}
	$magiccount = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_topic' ) . " WHERE topicid NOT IN('$mytopicids') " . $wherestr ), 0 );
	$sql = "SELECT * FROM " . DB::table ( 'ques_topic' ) . "  WHERE topicid NOT IN($mytopicids) " . $wherestr . " ORDER by dateline DESC LIMIT $start_limit,$perpage";
} else {
	$magiccount = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_topic' ) . " WHERE 1=1 " . $wherestr . " " ), 0 );
	$sql = "SELECT * FROM " . DB::table ( 'ques_topic' ) . ' WHERE  1=1 ' . $wherestr . " ORDER by dateline DESC LIMIT $start_limit,$perpage";
}
$multipage = multi ( $magiccount, $perpage, $page, "plugin.php?id=nds_up_ques:nds_up_ques" . ($tid ? '&typeid=' . $tid : '') . ($myqtid ? '&myqt=' . $myqtid : '') );
$query = DB::query ( $sql );
$qclasshtml = '';
while ( $queslist = DB::fetch ( $query ) ) {
	if ($queslist ['person'] == 0) {
		$topictype = '&nbsp;&nbsp;<img src="' . $imgurl . 'appsetting.gif" alt="' . lang ( 'plugin/nds_up_ques', 'typeperson' ) . '" />';
	} elseif ($queslist ['stop']) {
		$topictype = '&nbsp;&nbsp;<img src="' . $imgurl . 'access_disallow.gif" alt="' . lang ( 'plugin/nds_up_ques', 'typestop' ) . '" />';
	} elseif ($queslist ['exp'] != 1 && $queslist ['exp'] < $_G ['timestamp']) {
		$topictype = '&nbsp;&nbsp;<img src="' . $imgurl . 'clock.gif" alt="' . lang ( 'plugin/nds_up_ques', 'typeexp' ) . '" />';
	} elseif ($ndsopcheck && ! $queslist ['opcheck']) {
		$topictype = '&nbsp;&nbsp;<img src="' . $imgurl . 'access_normal.gif" alt="' . lang ( 'plugin/nds_up_ques', 'typeopcheck' ) . '" />';
	} else {
		$topictype = '&nbsp;&nbsp;<img src="' . $imgurl . 'access_allow.gif"alt="' . lang ( 'plugin/nds_up_ques', 'typerun' ) . '" />';
	}
	$queslists [$queslist ['topicid']] ['topictype'] = $topictype;
	if ($ndsqclass && $queslist ['qclass']) {
		$queslists [$queslist ['topicid']] ['qclass'] = "<span class='emqclass'>[</span><em>" . $ndsqclass [$queslist ['qclass']] . "</em><span class='emqclass'>]</span>";
	} else {
		$queslists [$queslist ['topicid']] ['qclass'] = '';
	}
	$queslists [$queslist ['topicid']] ['subject'] = $queslist ['subject'];
	$queslists [$queslist ['topicid']] ['dateline'] = empty($_G ['mobile'])? dgmdate( $queslist ['dateline'],'dt'):dgmdate( $queslist ['dateline'],'d');
	$queslists [$queslist ['topicid']] ['authorid'] = $queslist ['authorid'];
	$queslists [$queslist ['topicid']] ['author'] = $queslist ['author'];
	$queslists [$queslist ['topicid']] ['person'] = $queslist ['person'];
	$queslists [$queslist ['topicid']] ['post_count'] = $queslist ['post_count'];
	$queslists [$queslist ['topicid']] ['exps'] = $queslist ['exp'] == 1 ? lang ( 'plugin/nds_up_ques', 'nds_explong' ) : dgmdate ( $queslist ['exp'] );
	$queslists [$queslist ['topicid']] ['ques_mode'] = $queslist ['ques_mode'];
	$queslists [$queslist ['topicid']] ['expenses'] = 0;
	if ($queslist ['expenses']) {
		if ($_G ['adminid'] == 1 || $queslist ['authorid'] == $_G ['uid']) {
			$queslists [$queslist ['topicid']] ['expenses'] = 2;
		} else {
			$queslists [$queslist ['topicid']] ['expenses'] = 1;
			if ($expensesmod == '1') {
				$count = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_countlog' ) . " WHERE `topicid`='$queslist[topicid]' AND `uid`='$_G[uid]' AND `tag` = '0'  LIMIT 1" ), 0 );
				if ($count) {
					$queslists [$queslist ['topicid']] ['expenses'] = 2;
				}
			} elseif ($expensesmod == '2') {
				$count = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_countlog' ) . " WHERE `topicid`='$queslist[topicid]' AND `uid`='$_G[uid]' LIMIT 1" ), 0 );
				if ($count) {
					$queslists [$queslist ['topicid']] ['expenses'] = 2;
				}
			}
		}
	}
}
$navtitle = lang ( 'plugin/nds_up_ques', 'action_1' ) . ' - ' . $navtitle;
include template ( 'nds_up_ques:ques_index' );

?>