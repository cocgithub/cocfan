<?PHP
/*  nds_up_ques  v3.1
 *  Plugin FOR Discuz! X 
 *	WWW.NWDS.CN | NDS.西域数码工作室 
 *  Plugin update 20121212 BY singcee
 */
if (! defined ( 'IN_DISCUZ' )) {
	exit ( 'Access Denied' );
}
$wherestr .= ' where authorid != 0 ';
if ($_G ['gp_view'] == 'ms') {
	$wherestr .= ' and dateline > ' . ($_G ['timestamp'] - 2592000);
	$lanlist = lang ( 'plugin/nds_up_ques', 'mslist' );
} elseif ($_G ['gp_view'] == 'qs') {
	$wherestr .= ' and dateline > ' . ($_G ['timestamp'] - 10368000);
	$lanlist = lang ( 'plugin/nds_up_ques', 'qslist' );
} else {
	$lanlist = lang ( 'plugin/nds_up_ques', 'alllist' );
}
$query = DB::query ( " SELECT author, authorid, sum(mark) as mark FROM " . DB::table ( 'ques_user' ) . $wherestr . " GROUP BY authorid ORDER by sum(mark) DESC LIMIT 0,10" );
$nid = 1;
while ( $quesuser = DB::fetch ( $query ) ) {
	$quesuserlist [$nid] ['nid'] = $nid;
	$quesuserlist [$nid] ['authorid'] = $quesuser ['authorid'];
	$quesuserlist [$nid] ['author'] = $quesuser ['author'];
	$quesuserlist [$nid] ['mark'] = $quesuser ['mark'];
	$nid ++;
}
$navtitle = lang ( 'plugin/nds_up_ques', 'action_8' ) . ' - ' . $navtitle;
include template ( 'nds_up_ques:ques_ranklist' );

?>