<?php
/**
 * 后台插件公告菜单
 * @createtime 2012-12-15
 */

if (! defined ( 'IN_DISCUZ' ) || ! defined ( 'IN_ADMINCP' )) {
	exit ( 'Access Denied' );
}
define ( 'ALIYUNREC', dirname ( __FILE__ ) );
require_once ALIYUNREC . '/config/common.const.php';
require_once DISCUZ_ROOT . './source/discuz_version.php';
$noticeUrl = 'http://plugin.tui.cnzz.com/?a=notice';
$redirectUrl = sprintf ( '%s&siteurl=%s&sitecharset=%s&siteversion=%s&v=%s', $noticeUrl, urlencode ( $_G ['siteurl'] ), CHARSET, DISCUZ_VERSION . '_' . DISCUZ_RELEASE, ALIYUNREC_VERSION );
echo sprintf ( '<script>window.location.href="%s";</script>', $redirectUrl );