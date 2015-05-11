<?PHP
/*  nds_up_ques  v3.2
 *  Plugin FOR Discuz! X 
 *	WWW.NWDS.CN | NDS.西域数码工作室 
 *  Plugin update 20130717 BY singcee
 */
if (! defined ( 'IN_DISCUZ' )) {
	exit ( 'Access Denied' );
}
loadcache('plugin');
global $_G;
class mobileplugin_nds_up_ques {
	function viewthread_posttop_mobile(){
        global $_G;
        $this->allowhookgroups = unserialize($_G['cache']['plugin']['nds_up_ques']['allowhookgroups']);
        $this->allowhookforums = unserialize($_G['cache']['plugin']['nds_up_ques']['allowhookforums']);
          $ndsvtreturn = array();
        if ($_G['page']!= 1  ){
        	return $ndsvtreturn;
         }
        $viframeheight = 260;
        $siframeheight = 260;
           if(!in_array($_G['fid'], $this->allowhookforums)) return $ndsvtreturn; 
        $questpid = DB::fetch_first("SELECT topicid,postmust,isprivate FROM ".DB::table('ques_topic')." WHERE tid = '$_G[tid]' "); 
        if (!$questpid['topicid']){
        	return $ndsvtreturn;
        }else{
           	$viframeheight +=  $questpid['iframeheight'];
            $ndsvtreturn[0] = '<iframe id="nds_ques" name="nds_ques" height="'.$viframeheight.'" width="766" scrolling="no" border="0" frameborder="0" src="'.$_G[siteurl].'plugin.php?id=nds_up_ques&action=viewques&hook=1&topicid='.$questpid[topicid].'&mobile=yes" ></iframe>';
        }
		return $ndsvtreturn;   
		   
    }
}
class mobileplugin_nds_up_ques_forum extends mobileplugin_nds_up_ques {

}

?>