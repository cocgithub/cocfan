<?php

/**
 *	(C)2012-2099 Powered by 禄仔(http://addon.cncal.cn) QQ：13505491.
 *      This is NOT a freeware, use is subject to license terms
 *	Date: 2012-9-25 12:00
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}

class plugin_team {
	public function global_usernav_extra1() {
		global $_G;
		$admingroup = unserialize($_G['cache']['plugin']['team']['admingroup']);
		if(in_array($_G['groupid'], $admingroup)) {
			return '<a href="plugin.php?id=team&op=report" target="_blank" style="padding:0 5px;"><img src="static/image/common/access_allow.gif" border="0" class="vm" /> <em class="xi1">管理团队成员</em></a>';
		} else {
			return '<a href="plugin.php?id=team&op=report" target="_blank" style="padding:0 5px;"><em class="xi1">申请管理员</em></a>';
		}
	}
}

?>