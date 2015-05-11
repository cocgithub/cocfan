<?php
/**
 * 提供阿里云推荐公共服务代理功能。
 * 是 aliyunrec.common.general.service.php, aliyunrec.common.config.service.php服务的出口。
 * @createtime 2012-10-11
 */

! defined ( 'ALIYUNREC' ) && exit ( 'Forbidden' );
require_once ALIYUNREC . '/config/common.const.php';
require_once ALIYUNREC . '/common/aliyunrec.common.general.service.php';
require_once ALIYUNREC . '/table/table_common_plugin_aliyunrec.php';

class AliyunRec_Common_Proxy_Service {
	
	/**
	 * 获取发送的JS模板
	 * @return string
	 */
	function getSendTemplate() {
		$sendTemplate = AliyunRec_Common_Config_Service::getConfig ( 'template.send' );
		return $sendTemplate ? $sendTemplate : '';
	}
	
	/**
	 * 获取各应用的JS地址
	 * @return string
	 */
	function getApplicationUrls() {
		return AliyunRec_Common_General_Service::getApplicationUrls ();
	}
	
	/**
	 * 获取固定位的ID名称
	 * @return array()
	 */
	function getFixedApplicationIds() {
		return AliyunRec_Common_Config_Service::getFixedApplicationIds ();
	}
	
	/**
	 * 获取内容页的标签
	 * @param string $title 标题
	 * @param string $thumb 缩略图
	 * @param string $url 文章地址
	 * @param string $tags 文章标签
	 * @return string
	 */
	function getRecommendOptions($title, $thumb = '', $url = '', $tags = '',$plugin = '') {
		return AliyunRec_Common_General_Service::getRecommendOptions ( $title, $thumb, $url, $tags ,$plugin );
	}
	
	/**
	 * 获取语言包
	 * @return array
	 */
	function getLocalLanguage() {
		$language = AliyunRec_Common_Config_Service::getConfig ( 'common.language' );
		return is_array ( $language ) ? $language : array ();
    }

    /**
     *获取页面类型
     *@return string
     */
    function getPageType(){
        return AliyunRec_Common_General_Service::getPageType();
    }

    /**
     *获取页面埋点的id
     *@param string $pid 位置类型
     *@$pid =  1:正文下方 2:正文下方 3:楼层中间
     *@return string
     */
    function getPositionString($pid){
        return AliyunRec_Common_General_Service::getPositionString($pid);
    }

    /**
     *获得v1.0 用户的云推荐id
     *@return array
     */
    function getOldApplicationIds(){
         return AliyunRec_Common_General_Service::getOldApplicationIds();
    }
    
    /**
     *获得dz tag string
     *@return string
     */
    function getDzTag(){
        return AliyunRec_Common_General_Service::getDzTag();
    }

    /**
     *加密
     *@param string str
     *return string
     */
    function getFnEncode($str){
        return AliyunRec_Common_General_Service::getFnEncode($str);
    }






}
