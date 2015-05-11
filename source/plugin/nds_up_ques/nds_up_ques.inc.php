<?PHP
/*  nds_up_ques  v3.1
 *  Plugin FOR Discuz! X 
 *	WWW.NWDS.CN | NDS.西域数码工作室 
 *  Plugin update 20121212 BY singcee
 */
if (! defined ( 'IN_DISCUZ' )) {
	exit ( 'Access Denied' );
}
$actionarray = array ('newques', 'viewques', 'admincp', 'edit', 'stats', 'viewanswer', 'scan', 'ranklist', 'expques' );
$action = ! in_array ( $_G ['gp_action'], $actionarray ) ? 'index' : $_G ['gp_action'];
$ndsquesname = trim ( $_G ['cache'] ['plugin'] ['nds_up_ques'] ['ndsquesname'] );
$allowpostcredit = unserialize ( $_G ['cache'] ['plugin'] ['nds_up_ques'] ['allowpostcredit'] );
$allowcredit = unserialize ( $_G ['cache'] ['plugin'] ['nds_up_ques'] ['allowcredit'] );
$creditmax = intval ( $_G ['cache'] ['plugin'] ['nds_up_ques'] ['creditmax'] );
$allowpost = unserialize ( $_G ['cache'] ['plugin'] ['nds_up_ques'] ['allowpost'] );
$perpage = $_G ['cache'] ['plugin'] ['nds_up_ques'] ['perpage'] ? intval ( $_G ['cache'] ['plugin'] ['nds_up_ques'] ['perpage'] ) : 10;
$allowsubmit = unserialize ( $_G ['cache'] ['plugin'] ['nds_up_ques'] ['allowsubmit'] );
$cvar = $_G ['cache'] ['plugin'] ['nds_up_ques'] ['creditvar'];
$allowhookgroups = unserialize ( $_G ['cache'] ['plugin'] ['nds_up_ques'] ['allowhookgroups'] );
$allowhookforums = unserialize ( $_G ['cache'] ['plugin'] ['nds_up_ques'] ['allowhookforums'] );
$ndsdeftype = $_G ['cache'] ['plugin'] ['nds_up_ques'] ['deftype'];
$ndsdefleas = $_G ['cache'] ['plugin'] ['nds_up_ques'] ['defleas'];
$ndsquesadmins = unserialize ( $_G ['cache'] ['plugin'] ['nds_up_ques'] ['quesadmins'] );
$ndsopcheck = $_G ['cache'] ['plugin'] ['nds_up_ques'] ['opcheck'];
$ndsopenclass = $_G ['cache'] ['plugin'] ['nds_up_ques'] ['openclass'];
$qclassset = $_G ['cache'] ['plugin'] ['nds_up_ques'] ['qclass'];
$expensesmod = $_G ['cache'] ['plugin'] ['nds_up_ques'] ['expensesmod'];
$guestrp = $_G ['cache'] ['plugin'] ['nds_up_ques'] ['guestrp'];
$nosharing = $_G ['cache'] ['plugin'] ['nds_up_ques'] ['nosharing'];
$rws = $_G ['cache'] ['plugin'] ['nds_up_ques'] ['rewritestart'];
$ndsqclass = array ();
foreach ( explode ( "\n", $qclassset ) as $value1 ) {
	$ak = trim ( substr ( $value1, 0, strpos ( $value1, '=' ) - 1 ) );
	if ($ak != '') {
		$ndsqclass [$ak] = trim ( substr ( $value1, strpos ( $value1, '=' ) + 1 ) );
	}
}
$ndssecret = $_G ['cache'] ['plugin'] ['nds_up_ques'] ['secret'];
$runmode = intval ( $_G ['cache'] ['plugin'] ['nds_up_ques'] ['sysmode'] );
$sysmode = ($runmode < 3) ? $runmode : 1;
! empty ( $_G ['gp_mode'] ) ? $sysmode = intval ( $_G ['gp_mode'] ) : /**
  $extgroupids = array();
  if (!empty($_G['member'][extgroupids])){
  	$extgroupids =  explode(' ',$_G['member'][extgroupids]);
  }
 **/

$showtid = 0;
$showpost_count = 1;
$ndscopyauthor = 1;
$page = max ( 1, intval ( $_G ['gp_page'] ) );
$start_limit = ($page - 1) * $perpage;
if ($start_limit < 0)
	$start_limit = 0;
$multipage = '';
$hook = intval ( $_G ['gp_hook'] );
$topicid = intval ( $_G ['gp_topicid'] );
$qid = intval ( $_G ['gp_qid'] );
$imgurl = './source/plugin/nds_up_ques/images/';
$vqurl = $rws ? 'ndsques_vq-' . $topicid . '.html' : 'plugin.php?id=nds_up_ques&action=viewques&topicid=' . $topicid;
$sturl = $rws ? 'ndsques_st-' . $topicid . '.html' : 'plugin.php?id=nds_up_ques&action=stats&topicid=' . $topicid;
$vaurl = $rws ? 'ndsques_va-' . $topicid . '.html' : 'plugin.php?id=nds_up_ques&action=viewanswer&topicid=' . $topicid;
$scurl = $rws ? 'ndsques_sc-' . $topicid . '-' . $qid . 'html' : 'plugin.php?id=nds_up_ques&action=scan&topicid=' . $topicid . '&qid=' . $qid;
$homeurl = $rws ? 'ndsques.html' : 'plugin.php?id=nds_up_ques';
$navtitle = ! empty ( $ndsquesname ) ? $ndsquesname : lang ( 'plugin/nds_up_ques', 'ques' );
require_once DISCUZ_ROOT . './source/plugin/nds_up_ques/nds_ques_' . $action . '.inc.php';

?>