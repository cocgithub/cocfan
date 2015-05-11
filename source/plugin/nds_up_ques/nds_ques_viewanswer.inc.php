<?PHP
/*  nds_up_ques  v3.1
 *  Plugin FOR Discuz! X 
 *	WWW.NWDS.CN | NDS.西域数码工作室 
 *  Plugin update 20121212 BY singcee
 */
if (! defined ( 'IN_DISCUZ' )) {
	exit ( 'Access Denied' );
}
! empty ( $_G ['gp_srchtxt'] ) ? $wherestr .= " AND  author = '" . dhtmlspecialchars ( trim ( substr ( $_GET ['srchtxt'], 0, 20 ) ) ) . "' " : '';
$orderby = $_G ['gp_orderby'] ? $_G ['gp_orderby'] : 'dateline';
$imes = $_G ['gp_imes'] ? $_G ['gp_imes'] : 'DESC';
$questopics = DB::fetch_first ( "SELECT * FROM " . DB::table ( 'ques_topic' ) . " WHERE `topicid`='$topicid'" );
$sysmode = $questopics ['ques_mode'];
$isguest = empty ( $_G ['uid'] ) ? $questopics ['isguest'] : 0;
if ($questopics ['postmust']) {
	if (empty ( $_G ['uid'] ) && ! $isguest) {
		showmessage ( 'nds_up_ques:see2' );
	} else {
		if ($isguest) {
			if ($guestrp == 1) {
				$count = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_user' ) . " WHERE   authorid = 0 AND  `postip`='" . $_G ['clientip'] . "' AND `topicid`='$topicid' LIMIT 1" ), 0 );
			} elseif ($guestrp == 2) {
				if (! empty ( $_G ['cookie'] ['nds_guestrp'] )) {
					$count = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_user' ) . " WHERE  authorid = 0 AND `isguest`='" . $_G ['cookie'] ['nds_guestrp'] . "' AND `topicid`='$topicid' LIMIT 1" ), 0 );
				}
			}
		} else {
			$count = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_user' ) . " WHERE `topicid`='$topicid' AND `authorid`='$_G[uid]' LIMIT 1" ), 0 );
		}
		
		if (! $count && $questopics ['authorid'] != $_G ['uid'] && $_G ['adminid'] != 1 && $_G ['adminid'] != 2)
			showmessage ( 'nds_up_ques:see2' );
	} //else
}
if ($questopics ['secret'] && ! $count && (empty ( $_G ['uid'] ) || ($questopics ['authorid'] != $_G ['uid'] && $_G ['adminid'] != 1 && $_G ['adminid'] != 2)))
	showmessage ( 'nds_up_ques:secret' );
$magiccount = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_user' ) . " WHERE `topicid`='$topicid' LIMIT 1" ), 0 );
$multipage = multi ( $magiccount, $perpage, $page, "plugin.php?id=nds_up_ques:nds_up_ques&action=viewanswer&topicid=" . $topicid . "&orderby=" . $orderby . "&imes=" . $imes );
$topiclist = '';
$nid = $start_limit + 1;
$query = DB::query ( " SELECT * FROM " . DB::table ( 'ques_user' ) . " WHERE `topicid`='$topicid' " . $wherestr . "  ORDER by $orderby $imes LIMIT $start_limit,$perpage" );
while ( $quesuser = DB::fetch ( $query ) ) {
	$quesuserlist [$quesuser ['qid']] ['nid'] = $nid ++;
	$quesuserlist [$quesuser ['qid']] ['topicid'] = $quesuser ['topicid'];
	$quesuserlist [$quesuser ['qid']] ['dateline'] = dgmdate ( $quesuser ['dateline'] );
	$quesuserlist [$quesuser ['qid']] ['authorid'] = $quesuser ['authorid'];
	$quesuserlist [$quesuser ['qid']] ['author'] = $quesuser ['author'];
	$quesuserlist [$quesuser ['qid']] ['mark'] = $quesuser ['mark'];
}
$navtitle = lang ( 'plugin/nds_up_ques', 'action_7' ) . ' - ' . $navtitle;
include template ( 'nds_up_ques:ques_viewanswer' );

?>