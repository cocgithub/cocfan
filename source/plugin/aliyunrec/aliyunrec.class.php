<?php
/**
 * 云推荐插件入口
 * @createtime 2012-12-15
 */
if (! defined ( 'IN_DISCUZ' )) {
	exit ( 'Access Denied' );
}

define ( 'ALIYUNREC', dirname ( __FILE__ ) );
require_once ALIYUNREC . '/common/aliyunrec.common.proxy.service.php';

class plugin_aliyunrec {
	
    function global_header() {
        global $postlist;
        global $_G;
        $basescript    =   $_G['basescript'];
        $fid           =   $_G['action']['fid'];
        if(($basescript != 'portal' && $basescript !='forum')||!$this->_getAliyunRecState()||$_G['page']!=1)
            return ;
        if(AliyunRec_Common_Proxy_Service::getPageType()==ALIYUNREC_INDEX){
                list ($title,$tags,$url,$thumb ) = array($_G['seotitle']['forum']?$_G['seotitle']['forum']:'','',$_G ['siteurl'] . 'forum.php','');        
        }
        elseif(AliyunRec_Common_Proxy_Service::getPageType()==ALIYUNREC_FORUMDISPLAY){
                list ($title,$tags,$url,$thumb ) = array($_G['forum']['name'],'',$this->_getForumdisplayUrl($fid),'');     
        }
        elseif(AliyunRec_Common_Proxy_Service::getPageType()==ALIYUNREC_VIEWTHREAD){
                if (! isset ( $postlist ))
			    return;
                reset ( $postlist );
		        $threadInfo = current ( $postlist );
		        list ($title, $tags, $url, $thumb,$plugin ) = array ($threadInfo ['subject'], $this->_buildTags ( $threadInfo ['tags'] ), $this->_getThreadUrl ( $threadInfo ['tid'] ), $this->_getThumb ( $threadInfo ),$this -> _getDzTag() );
        }
        elseif(AliyunRec_Common_Proxy_Service::getPageType()==ALIYUNREC_PORTAL_INDEX){
        
            list ($title,$tags,$url,$thumb ) = array($_G['seotitle']['portal']?$_G['seotitle']['portal']:'','',$_G ['siteurl'] . 'portal.php','');        
        }
        elseif(AliyunRec_Common_Proxy_Service::getPageType()==ALIYUNREC_PORTAL_LIST){
            global $cat;
            list ($title,$tags,$url,$thumb ) = array($cat['catname'],'',$cat['caturl'],'');        
        }
        elseif(AliyunRec_Common_Proxy_Service::getPageType()==ALIYUNREC_PORTAL_VIEW){
            global $article;
            $thumbpic  =  '';
            if(strpos($article['pic'],'thumb.jpg')){
                $thumbpic   =   $_G['siteurl'].str_replace('.thumb.jpg','',$article['pic']);
            }
            list ($title,$tags,$url,$thumb ) = array($article['title'],'',$_G['siteurl'].'portal.php?mod=view&aid='.$article['aid'],$thumbpic);        
        }
		return AliyunRec_Common_Proxy_Service::getRecommendOptions ( $title, $thumb, $url, $tags,$plugin );
	}
	
    function global_footer() {
        global   $_G;
        $basescript    =   $_G['basescript'];
        if(($basescript != 'portal' && $basescript !='forum')||!$this->_getAliyunRecState()||$_G['page']!=1)
			return;
		return AliyunRec_Common_Proxy_Service::getSendTemplate () . AliyunRec_Common_Proxy_Service::getApplicationUrls ();
	}
	
	/**
	 * 组装标签串
	 * @access private
	 * @param array $tags 标签
	 * @return string 组装后的标签字符串
	 */
	function _buildTags($tags) {
		if (! is_array ( $tags ) || count ( $tags ) < 1)
			return '';
		foreach ( $tags as $value ) {
			list ( , $tag ) = $value;
			$tagString .= ($tagString ? ',' : '') . $tag;
		}
		return $tagString;
	}
	
	/**
	 * 获取帖子地址
	 * @access private
	 * @param int $tid 帖子ID
	 * @return string 帖子地址
	 */
	function _getThreadUrl($tid) {
		global $_G;
		if ($_G ['setting'] ['rewritestatus'] && in_array ( 'forum_viewthread', $_G ['setting'] ['rewritestatus'] ))
			return rewriteoutput ( 'forum_viewthread', 1, $_G ['siteurl'], $tid );
		return $_G ['siteurl'] . 'forum.php?mod=viewthread&tid=' . $tid;
    }

    /**
     *获取列表页地址
     *@access private
     *@param int $fid 板块id
     *@return string
     */
    function _getForumdisplayUrl($fid){
        global $_G; 
		if ($_G ['setting'] ['rewritestatus'] && in_array ( 'forum_forumdisplay', $_G ['setting'] ['rewritestatus'] ))
			return rewriteoutput ( 'forum_forumdisplay', 1, $_G ['siteurl'] );
		return $_G ['siteurl'] . 'forum.php?mod=forumdisplay&fid=' . $fid;
    
    }

	/**
	 * 获取帖子图片
	 * @access private
	 * @param array $threadInfo 帖子信息
	 * @return string 图片地址
	 */
	function _getThumb($threadInfo) {
		global $postarr;
		reset ( $postarr );
		$content = current ( $postarr );
		$content = $content ['message'];
	    //if (strpos ( $content, '[/img]' ) !== false)
		//return $this->_getImgThumb ( $content );
		return (is_array ( $threadInfo ['attachments'] ) && count ( $threadInfo ['attachments'] ) > 0) ? $this->_getAttachThumb ( $threadInfo ['attachments'] ) : '';
	}
	
	/**
	 * 获取远程图片地址
	 * @access private
	 * @param string $content 帖子内容
	 * @return string 图片地址
	 */
	function _getImgThumb($content) {
		$url = '';
		preg_match ( "/\[img\]\s*([^\[\<\r\n]+?)\s*\[\/img\]/ies", $content, $match );
		(is_array ( $match ) && count ( $match ) > 0) && $url = $match [1];
		if ($url)
			return $this->_buildImageThumbUrl ( $url );
		preg_match ( "/\[img=(\d{1,4})[x|\,](\d{1,4})\]\s*([^\[\<\r\n]+?)\s*\[\/img\]/ies", $content, $match );
		(is_array ( $match ) && count ( $match ) > 0) && $url = $match [3];
		return $url ? $this->_buildImageThumbUrl ( $url ) : '';
	}
	
	/**
	 * 获取附件图片地址
	 * @access private
	 * @param array $attachments 帖子附件
	 * @return string 图片地址
	 */
	function _getAttachThumb($attachments) {
		global $_G;
		$image = array ();
		foreach ( $attachments as $attachment ) {
			if (! $attachment ['isimage'])
				continue;
			$image = $attachment;
			break;
		}
		return (count ( $image ) > 0) ? ($_G ['siteurl'] . $image ['url'] . $image ['attachment']) : '';
	}
	
	/**
	 * 组装图片地址
	 * @access private
	 * @param string $url 图片地址
	 * @return string 图片地址
	 */
	function _buildImageThumbUrl($url) {
		if (! in_array ( strtolower ( substr ( $url, 0, 6 ) ), array ('http:/', 'https:', 'ftp://', 'rtsp:/', 'mms://' ) ) && ! preg_match ( '/^static\//', $url ) && ! preg_match ( '/^data\//', $url ))
			return 'http://' . $url;
		return $url;
	}
	
	/**
	 * 获取插件状态，1表示开启，0表示关闭
	 * @access private
	 * @return int
	 */
	function _getAliyunRecState() {
		global $_G;
		return true;
    }

    /**
     *获取文章信息用于抓取
     *@access private
     *@return string
     */
    function _getDzTag(){
        global $_G;
      //var_dump($_G);
        $plugin       = 'plugin=dz';
        $dzTagstring  = AliyunRec_Common_Proxy_Service::getDzTag();
        $dzTagarray   = explode(',',$dzTagstring);   
        if(!is_array($dzTagarray)||count($dzTagarray)<2)
            return AliyunRec_Common_Proxy_Service::getFnEncode($plugin);
        foreach ( $dzTagarray as $v){
             $plugin   .=    '&'.$v.'='.$_G['forum_thread'][$v];        
        }
         return AliyunRec_Common_Proxy_Service::getFnEncode($plugin);
    }
}

class plugin_aliyunrec_forum extends plugin_aliyunrec {
    /**
     *在正文下方插入div
     *@access private
     *@return array
     */	
    function viewthread_postbottom_output() {
        global $_G;
		if (CURMODULE != 'viewthread' || ! $this->_getAliyunRecState () || $_G['page']!=1)
            return array ();
        return array( AliyunRec_Common_Proxy_Service::getPositionString(ALIYUNREC_FIXED_POSITION_POSTBOTTOM));
    }

    /**
     *在正文上方插入div
     *@access private
     *@return array
     */
    function viewthread_posttop_output(){
        global $_G;
        if (CURMODULE != 'viewthread' || ! $this->_getAliyunRecState () || $_G['page']!=1)
            return array ();
        return array( AliyunRec_Common_Proxy_Service::getPositionString(ALIYUNREC_FIXED_POSITION_POSTTOP));
    }

    /**
     *在楼层中间插入div
     *@access private
     *@return array
     */
    function viewthread_endline_output(){ 
        global $_G;
		if (CURMODULE != 'viewthread' || ! $this->_getAliyunRecState () || $_G['page']!=1)
            return array ();
        return array( AliyunRec_Common_Proxy_Service::getPositionString(ALIYUNREC_FIXED_POSITION_POSTENDLINE));
    }
	   
}
class plugin_aliyunrec_portal extends plugin_aliyunrec{

    function view_article_content_output(){
        global $_G;
		if (CURMODULE != 'view' || ! $this->_getAliyunRecState () )
            return '';
        return AliyunRec_Common_Proxy_Service::getPositionString(ALIYUNREC_FIXED_VIEW_ARTICLE_CONTENT);
    }
    function view_article_side_top_output(){
        global $_G;
		if (CURMODULE != 'view' || ! $this->_getAliyunRecState () )
            return '';
        return AliyunRec_Common_Proxy_Service::getPositionString(ALIYUNREC_FIXED_VIEW_ARTICLE_SIDE_TOP);
    }
    function view_article_side_bottom_output(){
        global $_G;
		if (CURMODULE != 'view' || ! $this->_getAliyunRecState () )
            return '';
        return AliyunRec_Common_Proxy_Service::getPositionString(ALIYUNREC_FIXED_VIEW_ARTICLE_SIDE_BOTTOM);
    }
    function ad_article($value){
        if($value['params'][0]=='article'&& $value['params'][2]==2){
           return "<div class='".$value['params'][1]."'>".AliyunRec_Common_Proxy_Service::getPositionString(ALIYUNREC_FIXED_ARTICLE_MBM)."</div>".$value['content'];
        }else{
			return $value['content'];
		}
    
    }

}
