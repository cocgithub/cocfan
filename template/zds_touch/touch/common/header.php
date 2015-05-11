<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=gb2312">
<meta http-equiv="Cache-control" content="{if $_G['setting']['mobile'][mobilecachetime] > 0}{$_G['setting']['mobile'][mobilecachetime]}{else}no-cache{/if}" />
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no, minimum-scale=1.0, maximum-scale=1.0">
<meta name="format-detection" content="telephone=no" />
<meta name="keywords" content="{if !empty($metakeywords)}{echo dhtmlspecialchars($metakeywords)}{/if}" />
<meta name="description" content="{if !empty($metadescription)}{echo dhtmlspecialchars($metadescription)} {/if},$_G['setting']['bbname']" />
<!--{if $_G['basescript'] == 'portal' && CURMODULE=='list'}--><base href="{$_G['siteurl']}" /><!--{/if}-->
<title><!--{if !empty($navtitle)}-->$navtitle - <!--{/if}--><!--{if empty($nobbname)}--> $_G['setting']['bbname'] - <!--{/if}-->COCFAN</title>
<link rel="stylesheet" href="{STATICURL}image/mobile/style.css" type="text/css" media="all">
<script src="{STATICURL}js/mobile/jquery-1.8.3.min.js?{VERHASH}"></script>

<script type="text/javascript">var STYLEID = '{STYLEID}', STATICURL = '{STATICURL}', IMGDIR = '{IMGDIR}', VERHASH = '{VERHASH}', charset = '{CHARSET}', discuz_uid = '$_G[uid]', cookiepre = '{$_G[config][cookie][cookiepre]}', cookiedomain = '{$_G[config][cookie][cookiedomain]}', cookiepath = '{$_G[config][cookie][cookiepath]}', showusercard = '{$_G[setting][showusercard]}', attackevasive = '{$_G[config][security][attackevasive]}', disallowfloat = '{$_G[setting][disallowfloat]}', creditnotice = '<!--{if $_G['setting']['creditnotice']}-->$_G['setting']['creditnames']<!--{/if}-->', defaultstyle = '$_G[style][defaultextstyle]', REPORTURL = '$_G[currenturl_encode]', SITEURL = '$_G[siteurl]', JSPATH = '$_G[setting][jspath]';</script>

<script src="{STATICURL}js/mobile/common.js?{VERHASH}" charset="{CHARSET}"></script>
<link rel="stylesheet" href="$_G['style']['tpldir']/touch/images/style.css" type="text/css">


</head>

<body {if $_G['basescript'] == 'home' && $_GET['mod'] == 'space' && $_GET['do'] == 'notice'}id="zds_hnotice"  {elseif $_G['basescript'] == 'home' && $_GET['mod'] == 'medal' && !$_GET['action']}id="zds_xzcenter"{elseif $_G['basescript'] == 'forum' && $_GET['mod'] == 'viewthread'}id="zds_fviewth"{/if}>

<div id="dsm_wp">

<div id="dsm_side_nv" class="dsm_side_nv">
<div class="user_r">
		<div class="user_top avatar_b">
		<!--{if $_G[uid]}-->
			<div class="avatar_m "><span><a href="home.php?mod=space&uid=$_G[uid]&do=profile"><img src="<!--{avatar($_G[uid], small, true)}-->" /></a></span></div>
			<div class="z avatar_my"><h2 class="name"><a href="home.php?mod=space&uid=$_G[uid]&do=profile">$_G[username]</a></h2>
			<p><a href="member.php?mod=logging&action=logout&formhash={FORMHASH}" class="dialog">{lang logout_mobile}</a></p></div>
			
		<!--{else}-->
			<div class="dsm_ytnvuid"><a rel="nofollow" href="member.php?mod=logging&action=login" class="dsm_aniu dsm_buleaniu">登陆</a><span class="pipe">|</span><a rel="nofollow" href="member.php?mod={$_G[setting][regname]}" class="dsm_aniu dsm_grenaniu">注册</a></div>
		<!--{/if}-->
		</div> 
<!--{if $_G[uid]}-->
			<div class="dsm_ycluserfunc">
				
				<div id="dsm_ycluserfuncmenu_menu" class="dsm_ycluserfuncmenu user_r_b" style="{if $_G[member][newpm] || $_G['member']['category_num'][system] ||  $_G[member][newprompt] }display:block;{else}display:none;{/if}">
					<ul>
		<!--{if $_G[member][newpm] || $_G['member']['category_num'][system] ||  $_G[member][newprompt] }-->
			{if $_G[member][newpm]}
			<li class="new"><a href="home.php?mod=space&do=pm">有新消息($_G[member][newpm])</a></li>
            
            {/if}
			{if $_G['member']['category_num'][system]}
			<li class="new"><a href="home.php?mod=space&do=notice&view=system">有新提醒($_G['member']['category_num'][system])</a></li>
			{else}
			{if $_G[member][newprompt]}
			<li class="new"><a href="home.php?mod=space&do=notice&view=mypost">有新提醒($_G[member][newprompt])</a></li>
            
            {/if}
			{/if}
			<!--{else}-->
			<li><a href="home.php?mod=space&do=notice&view=mypost">消息中心</a></li>
			<!--{/if}-->
			
            <li><a href="forum.php?mod=guide&view=my">我的帖子</a></li>
            <li><a href="home.php?mod=space&do=favorite&view=me">我的收藏</a></li>
			<li><a href="home.php?mod=space&do=friend">我的好友</a></li>
			<!--{if $_G['setting']['taskon']}--><li><a href="home.php?mod=task">任务中心</a></li><!--{/if}-->
			<!--{if $_G['setting']['taskon'] && !empty($_G['cookie']['taskdoing_'.$_G['uid']])}--><li><a href="home.php?mod=task&item=doing">{lang task_doing}</a></li><!--{/if}-->
            <li><a href="member.php?mod=logging&action=logout&formhash={FORMHASH}" class="dialog">{lang logout_mobile}</a></li>
		</ul>
        
				</div>
				<div class="clear"></div>
			<a rel="nofollow" href="javascript:;" id="dsm_ycluserfuncmenu" onclick="dbox('dsm_ycluserfuncmenu','dsm_ycluserfuncmenu');"><span>△ ▽</span></a>
			</div>
		<!--{/if}-->			
    	<div class="user_r_b dsm_ycnvli">
		<ul>
		
			<!--{template dsportal/ds_nav}-->
		</ul>
        
	</div>

</div>
</div>



<div id="dsm_content" class="cl dsm_content">
<div id="hd">
	<div class="header-logo">
	<!--{if CURMODULE=='viewthread' || CURMODULE=='view'  || CURMODULE=='forumdisplay'  || CURMODULE=='post'  || CURMODULE=='misc'}-->
	<a href="javascript:;" onclick="history.go(-1)" style="line-height: 43px;" class="dsm_ttui" />&nbsp;</a>
	<!--{else}-->
    <!--{if false}-->
		<a href="{$_G[siteurl]}"><img src="$_G['style']['tpldir']/touch/images/img/logo.png" width="74" height="35" /></a>
    <!--{/if}-->
	<!--{/if}-->
	</div>
	<div class="header-title dsmnv_toptit">
		<span>
		<!--{if $_G['basescript'] == 'portal' && CURMODULE=='view'}-->
		文章详情
		<!--{elseif $_G['basescript'] == 'portal' && $_GET['mod'] == 'list'}-->
		{$cat[catname]}
		<!--{elseif $_G['basescript'] == 'forum' && !$_GET['mod']}-->
		首页
		<!--{elseif $_G['basescript'] == 'forum' && $_GET['mod'] == 'forum'}-->
		论坛
		<!--{elseif $_G['basescript'] == 'forum' && $_GET['mod'] == 'viewthread'}-->
		帖子详情
		<!--{elseif  $_G['basescript'] == 'forum' && $_GET['mod'] == 'forumdisplay'}-->
		{$_G['forum'][name]}
		<!--{elseif  $_G['basescript'] == 'forum' && $_GET['mod'] == 'guide'}-->
		导读
		<!--{else}-->
		$navtitle
		<!--{/if}--></span>
		
	</div>
	<div style="width: 80px;">
		<a href="search.php?mod=forum&mobile=2" class="so">&nbsp;</a>
		
		<div class="y user" id="dsm_side_pr">
		<!--{if $_G[uid]}--><a href="javascript:;" id="dsm_side_open" class="pic"><!--{if $_G[member][newpm] || $_G[member][newprompt] || $_G[member][newprompt_num][follower] || $_G[member][newprompt_num][follow]  }--><span class="pm"></span><!--{/if}--><!--{avatar($_G[uid],small)}--></a>
		<!--{else}-->
		<a  href="javascript:;" id="dsm_side_open" class="pic_no"><span class="">
		&nbsp;</span></a><!--{/if}-->
		
		</div>
		
	</div>
</div>


<script  type="text/javascript">
function auto_height(){
     var h= document.documentElement.clientHeight-0;
 var high = document.getElementById('dsm_wp');
 high.style.height=h+"px";
 }	

 $('#dsm_side_open').bind('click', function() {
    if($('body').css('overflow-x') != 'hidden') {
        $('body').css('overflow-x', 'hidden'); 
        $('#dsm_content').addClass('open');
$('#dsm_side_nv').addClass('oy');
$('#dsm_side_nv').css('display', 'block');
$('#dsm_side_pr').addClass('dsm_sd_pr');
$('#dsm_side_open').addClass('msk');
$('#hd').css('position', 'relative');
$('.dsm_tfixheight').css('display', 'none');
    } else {
        $('body').css('overflow-x', '');
        $('#dsm_content').removeClass('open');
$('#dsm_side_nv').removeClass('oy');
$('#dsm_side_nv').css('display', 'none');
$('#dsm_side_pr').removeClass('dsm_sd_pr');
$('#dsm_side_open').removeClass('msk');
$('#hd').css('position', 'fixed');
$('.dsm_tfixheight').css('display', 'block');
    }
});


if(window.onload!=null){   
    window.onload=function(){auto_height();};  
}
$(document).ready(function() {

	/* Drop Down Menu */
	$(".nv-list").click(function () {
      	$(this).toggleClass("expanded");
    });
	

	
});
</script>


<script type="text/javascript">

function mys(id){
return !id ? null : document.getElementById(id);
}

function dbox(id,classname){
if(mys(id+'_menu').style.display =='block'){
mys(id+'_menu').style.display ='none'
mys(id).className = '';
}else{
mys(id+'_menu').style.display ='block'
mys(id).className = classname;
}
}

if(window.onload!=null){   
    window.onload=function(){auto_height();};  
}
</script>

<div class="dsm_tfixheight">&nbsp;</div>

<div id="nv" class="cl">
	<!--{template dsportal/ds_hdnav}-->
</div>

<div id="hd_bot"></div>

