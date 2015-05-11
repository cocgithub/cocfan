<?php
/*
	ID: adkiller_7ree
	[www.7ree.com] (C)2007-2013 7ree.com.
	This is NOT a freeware, use is subject to license terms
	Update: 17:50 2013/5/21
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/

if(!defined('IN_DISCUZ')) exit('Access Denied');


class plugin_adkiller_7ree_forum{	
	function viewthread_sidebottom_output() {
		  global $postlist,$_G;
		  $return = array();
		  $plugin_var_7ree = $_G['cache']['plugin']['adkiller_7ree'];
		  $adgroup_7ree = $plugin_var_7ree['adgroup_7ree'] ? unserialize($plugin_var_7ree['adgroup_7ree']) : array();
		  $killergroup_7ree = $plugin_var_7ree['killergroup_7ree'] ? unserialize($plugin_var_7ree['killergroup_7ree']) : array();

		  if(is_array($postlist)) {
            foreach($postlist as $key => $var_7ree) {	
            $return[] = (in_array($var_7ree['groupid'] , $adgroup_7ree) && in_array($_G['groupid'] , $killergroup_7ree)  && $_G[uid]) ? "<p><strong><a href='plugin.php?id=adkiller_7ree:adkiller_action_7ree&uid_7ree={$var_7ree['authorid']}&fid_7ree={$_G[fid]}' onclick=\"showWindow('adkiller_7ree', this.href);return false;\">".lang('plugin/adkiller_7ree', 'php_lang_link_7ree')."</a></strong></p>" : "";
            }   
		  }
		  return $return; 
	}

}

?>
