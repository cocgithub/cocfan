<?php

/**
 *      [Sanree] (C)2001-2099 Sanree Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: buy.inc.php 2 2012-01-31 16:26:10 sanree $
 */

if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}
cpheader();
$langs = $scriptlang['sanree_filterword'];
showsubmenu($langs['sanree_filterword_copyright']);
?>
<style>.mbody ul li{line-height:23px;}</style>
<div class="mbody"><?=$langs['copyrighthtml'];?></div>