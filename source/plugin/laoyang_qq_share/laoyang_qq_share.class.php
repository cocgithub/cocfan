<?php
/**
 *	[分享到QQ(laoyang_qq_share.{modulename})] (C)2013-2099 Powered by 吉他社(www.jitashe.net).
 *	Version: 1.0
 *	Date: 2013-4-2 07:06
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
class plugin_laoyang_qq_share {
    public $code = "<script type=\"text/javascript\">
(function(){
var p = {
url:location.href, 
desc:'', 
title:'qq_share_title', 
summary:'', 
pics:'', 
flash: '', 
site:'', 
style:'101',
width:96,
height:24
};
var s = [];
for(var i in p){
s.push(i + '=' + encodeURIComponent(p[i]||''));
}
document.write(['<a class=\"qcShareQQDiv\" href=\"http://connect.qq.com/widget/shareqq/index.html?',s.join('&'),'\" target=\"_blank\"></a>'].join(''));
})();
</script>
<script src=\"http://connect.qq.com/widget/loader/loader.js\" widget=\"shareqq\" charset=\"utf-8\"></script>";
    function viewthread_useraction_output()
    {
        global $thread;
        $this->code=  str_replace('qq_share_title', $thread['subject'], $this->code);
        return $this->code;
    }
}
class plugin_laoyang_qq_share_forum extends plugin_laoyang_qq_share
{
    
}
?>