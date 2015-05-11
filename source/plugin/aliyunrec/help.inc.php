<?php
/**
 * 后台在线帮助菜单
 * @createtime 2012-12-15
 */

if (! defined ( 'IN_DISCUZ' ) || ! defined ( 'IN_ADMINCP' )) {
	exit ( 'Access Denied' );
}
define ( 'ALIYUNREC', dirname ( __FILE__ ) );
require_once ALIYUNREC . '/config/common.const.php';
require_once DISCUZ_ROOT . './source/discuz_version.php';
$recPluginCenter = 'http://tui.cnzz.com/help/chajianguanli/discuz/204.html';
$redirectUrl = sprintf ( '%s?siteurl=%s&sitecharset=%s&siteversion=%s&version=%s', $recPluginCenter, urlencode ( $_G ['siteurl'] ), CHARSET, DISCUZ_VERSION . '_' . DISCUZ_RELEASE, ALIYUNREC_VERSION );
echo sprintf ( '<script>window.open("%s");history.back(-1);</script>', $redirectUrl );
