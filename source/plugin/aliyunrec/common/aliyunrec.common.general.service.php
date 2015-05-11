<?php
/**
 * 阿里云推荐业务服务
 * @createtime 2012-09-27
 */

! defined ( 'ALIYUNREC' ) && exit ( 'Forbidden' );
require_once ALIYUNREC . '/common/aliyunrec.common.config.service.php';
class AliyunRec_Common_General_Service {
	
	/**
	 * 获取各应用的JS地址
	 * @return string
	 */
    function getApplicationUrls() {

        $applicationIds = AliyunRec_Common_General_Service :: filterAliyunRecIds();
        if (count ( $applicationIds ) < 1 || !is_array($applicationIds))
			return '';
        $urls = AliyunRec_Common_General_Service::buildApplicationUrls ( $applicationIds );
		return AliyunRec_Common_General_Service::buildApplicationJsString ( $urls );
	}
	
	/**
	 * 获取内容页的标签
	 * @param string $title 标题
	 * @param string $thumb 缩略图
	 * @param string $url 文章地址
	 * @param string $tags 文章标签
	 * @return string
	 */
	function getRecommendOptions($title, $thumb = '', $url = '', $tags = '',$plugin = '' ) {
		$template = AliyunRec_Common_Config_Service::getConfig ( 'template.option' );
		if (! $template)
			return '';
		list ( $title, $thumb, $url, $tags ,$plugin ) = array (AliyunRec_Common_General_Service::filterString ( $title ), AliyunRec_Common_General_Service::filterString ( $thumb ), AliyunRec_Common_General_Service::filterString ( $url ), AliyunRec_Common_General_Service::filterString ( $tags ),AliyunRec_Common_General_Service:: filterString ( $plugin ) );
		$params = array ();
		$url && $params [] = "'url':'$url'";
		$title && $params [] = "'title':'$title'";
		$thumb && $params [] = "'thumb':'$thumb'";
		$tags && $params [] = "'tags':'$tags'";
		$plugin && $params [] = "'reserved1':'$plugin'";
		return str_replace ( '<<content>>', implode ( ",\r\n", $params ), $template );
	}
	
	/**
	 * 私有方法
	 * 获取id
	 * @param array $applicationIds ID信息
	 * @return array
	 */
	function builApplicationIds($applicationIds) {
		list ( $fixedIds, $floatIds ) = array (AliyunRec_Common_General_Service::buildFixedIds ( $applicationIds ), AliyunRec_Common_General_Service::buildFloatIds ( $applicationIds ) );
		return array_merge ( $fixedIds, $floatIds );
	}
	
	/**
	 * 私有方法
	 * 获取固定位id
	 * @param array $applicationIds ID信息
	 * @return array
	 */
	function buildFixedIds($applicationIds) {
		if (! isset ( $applicationIds ['fixed'] ) || $applicationIds ['fixed'] == '')
			return array ();
		$tmp = explode ( ',', $applicationIds ['fixed'] );
		return (is_array ( $tmp ) && count ( $tmp ) > 0 && intval ( $tmp [0] ) >= 0) ? array (intval ( $tmp [0] ) ) : array ();
	}
	
	/**
	 * 私有方法
	 * 获取浮窗id
	 * @param array $applicationIds ID信息
	 * @return array
	 */
	function buildFloatIds($applicationIds) {
		$ids = array ();
		if (! isset ( $applicationIds ['float'] ) || $applicationIds ['float'] == '')
			return $ids;
		$tmp = explode ( ',', $applicationIds ['float'] );
		if (! is_array ( $tmp ))
			return $ids;
		foreach ( $tmp as $value ) {
			$value = intval ( $value );
			if ($value < 1)
				continue;
			$ids [] = $value;
		}
		return $ids;
	}
	
	/**
	 * 私有方法
	 * 组装应用地址
	 * @param array $applicationIds 应用ID等信息
	 * @return array
	 */
	function buildApplicationUrls($applicationIds) {
		list ( $urls, $urlTemplate ) = array (array (), AliyunRec_Common_Config_Service::getUrlTemplate () );
		foreach ( $applicationIds as $applicationId ) {
			$urls [] = $urlTemplate . $applicationId;
		}
		return $urls;
	}
	
	/**
	 * 私有方法
	 * 组装应用JS
	 * @param array $applicationUrls 应用地址数组
	 * @return string
	 */
    function buildApplicationJsString($applicationUrls) {

		$fixedRecommendId = AliyunRec_Common_Config_Service::getFixedApplicationIds ();
		$jsString = "<script>var aliyun_recommend_apps = new Array();";
		foreach ( $applicationUrls as $url ) {
			$jsString .= "aliyun_recommend_apps.push('$url');";
		}
		$jsString .= str_replace ( '<<version>>', ALIYUNREC_VERSION, AliyunRec_Common_Config_Service::getConfig ( 'template.request' ) );
		return $jsString . '</script>';
	}
	
	/**
	 * 私有方法
	 * 过滤引号
	 * @param string $string 字符串
	 * @return string
	 */
	function filterString($string) {
		return str_replace ( array ('\'', '"' ), '', trim ( $string ) );
    }

    /**
     *私有方法
     *获取页面类型
     *1：首页；2：列表页；3：内容页
     *@return string
     */
    function getPageType(){
        
        global $_G;
        $basescript    =   $_G['basescript'];
        if($basescript ==  'forum'){
            if(CURMODULE ==  'forumdisplay')
                return ALIYUNREC_FORUMDISPLAY;
            else if(CURMODULE == 'viewthread')
                return ALIYUNREC_VIEWTHREAD;
            else if(CURMODULE =='index')
                return ALIYUNREC_INDEX;
        }
        elseif($basescript == 'portal'){
            if(CURMODULE == 'index')
                return ALIYUNREC_PORTAL_INDEX;
            else if(CURMODULE == 'list')
                return ALIYUNREC_PORTAL_LIST;
            else if(CURMODULE == 'view')
                return ALIYUNREC_PORTAL_VIEW;  
        }
    }
    /**
     *私有方法
     *过滤展现id
     *@return array
     */
    function filterAliyunRecIds(){
       $AliyunrecDB =  table_common_plugin_aliyunrec :: getstance();  
       $IdInfo      =  $AliyunrecDB -> getIdInfo();
       $AliyunrecIds= array();
       if(!is_array($IdInfo))
            return $AliyunrecIds;
       global  $_G;
       $mid         =   AliyunRec_Common_General_Service::getPageType();
       $fid         =   $_G['action']['fid']; 
       $basescript  =   $_G['basescript'];
       
       if($basescript == 'forum'){
           if($mid  ==   ALIYUNREC_INDEX) {
                foreach($IdInfo as $info){
                    if($info['r_status']==2)//暂停
                        continue;
                    if($info['r_mid']== ALIYUNREC_INDEX){
                        array_push($AliyunrecIds,$info['r_id']);
                    }
                } 
           }            
           else{
                 foreach($IdInfo as $info){
                    if($info['r_status']==2)//暂停
                        continue;
                    if($mid == $info['r_mid']&&(in_array($fid,explode(',',$info['r_fid'])) || $info['r_fid']==-1 )){     
                        array_push($AliyunrecIds,$info['r_id']);
                    }
                }
           }
       }
       elseif($basescript == 'portal'){
           foreach($IdInfo as $info){
                if($info['r_status']==2)//暂停
                    continue;
                if($mid == $info['r_mid']){
                    array_push($AliyunrecIds,$info['r_id']);
                }
           }
       }
       return $AliyunrecIds;
    }
   
    /**
     *获取页面埋点的id
     *@param string $pid 位置类型
     *@$pid =  5:正文下方 6:正文下方 7:楼层中间
     *@return string
     */
    function getPositionString($pid){
        $AliyunrecDB =  table_common_plugin_aliyunrec :: getstance();  
        $IdInfo      =  $AliyunrecDB -> getIdInfo();
		$style       =  ($pid == ALIYUNREC_FIXED_POSITION_POSTBOTTOM )?' style =  "margin-top:5px"':'';
		if($pid == ALIYUNREC_FIXED_POSITION_POSTBOTTOM){
			$style   =  'style =  "margin-top:5px"';
		}
		elseif($pid == ALIYUNREC_FIXED_POSITION_POSTTOP){
			$style   =  'style =  "margin-bottom:5px"';
		}
		else{
			$style   =  '';
		}
        if(!is_array($IdInfo))
            return '';
        $AliyunrecDiv = '';
        foreach($IdInfo  as $info ){
            if($info['r_status']==2)//暂停
                continue;
            if($info['r_type']==1&&$info['r_pid']==$pid){
                $AliyunrecDiv .=  '<div id = "aliyun_cnzz_tui_'.$info['r_id'].'" '.$style.' ></div>';
            }   
        }
        return $AliyunrecDiv;
    } 
    
    /**
     *获取v1.0ids
     *@return array
     */
    function getOldApplicationIds(){
        $applicationIds = AliyunRec_Common_Config_Service::getConfig ( 'common.application' );
        if (! is_array ( $applicationIds ) || count ( $applicationIds ) < 1)
            return array();
        $applicationIds = AliyunRec_Common_General_Service::builApplicationIds ( $applicationIds );
        return array_filter($applicationIds);  
    }

    /**
     *获取dz tag
     *return string
     */
   function getDzTag(){
        $dzTagString   =  AliyunRec_Common_Config_Service::getConfig ( 'template.tag' );
        if(!$dzTagString)
            return '';
       return $dzTagString; 
   }
   
   /**
    *加密过程
    *return string
    */
    function getFnEncode($str){
      $str  = diconv($str, CHARSET, 'UTF-8');
      $sOut = $str ^ str_repeat(ALIYUNREC_XORKEY, ceil(strlen($str) /strlen(ALIYUNREC_XORKEY)));
      $sOut = pack("H*", md5($sOut.ALIYUNREC_PRIVATEKEY)).$sOut;
      $sOut = base64_encode($sOut);
      $sOut = str_replace("+", ",", $sOut);
      $sOut = str_replace("/", "_", $sOut);
      $sOut = str_replace("=", ".", $sOut);
      return $sOut;
  }


}
