<?php
/**
 * 常量配置
 * @createtime 2012-10-11
 */
! defined ( 'ALIYUNREC' ) && exit ( 'Forbidden' );

define ( 'ALIYUNREC_VERSION', 'aliyunrec_v2.3_for_discuz_x2.5' ); //插件程序版本
define ( 'ALIYUNREC_DOMAIN', 'http://tui.cnzz.net/api.php?id=' ); //JS请求域名
define ( 'ALIYUNREC_TUI_DOMAIN' , 'http://oem.tui.cnzz.com/tui_api.php'); //tui平台请求地址
define ( 'ALIYUNREC_PLUGIN_TYPE', 'ndiscuz');//插件类型
define ( 'ALIYUNREC_FROM_TYPE', '');//插件来源
define ( 'ALIYUNREC_INDEX',1);//首页
define ( 'ALIYUNREC_FORUMDISPLAY',2);//列表页
define ( 'ALIYUNREC_VIEWTHREAD',3);//内容页

//////////////////////////////new//////////////////////////////////////////
define ( 'ALIYUNREC_PORTAL_INDEX',4);//门户首页
define ( 'ALIYUNREC_PORTAL_LIST',5);//门户列表页
define ( 'ALIYUNREC_PORTAL_VIEW',6);//门户内容页
////////////////////////////////////////////////////////////////////////////

define ( 'ALIYUNREC_FIXED_POSITION_POSTBOTTOM',5);//主贴下方
define ( 'ALIYUNREC_FIXED_POSITION_POSTTOP',6);//主贴上方
define ( 'ALIYUNREC_FIXED_POSITION_POSTENDLINE',7);//楼层中间

///////////////////////////new//////////////////////////////////////////////
define ( 'ALIYUNREC_FIXED_VIEW_ARTICLE_CONTENT',8);//文章内容下方
define ( 'ALIYUNREC_FIXED_ARTICLE_MBM',9);//评论上方
define ( 'ALIYUNREC_FIXED_VIEW_ARTICLE_SIDE_TOP',10);//边侧上方
define ( 'ALIYUNREC_FIXED_VIEW_ARTICLE_SIDE_BOTTOM',11);//边侧下方
////////////////////////////////////////////////////////////////////////////

define ( 'ALIYUNREC_XORKEY','!@aliyunrec@!');//xorkey
define ( 'ALIYUNREC_PRIVATEKEY','aliyunrec');//privatekey
