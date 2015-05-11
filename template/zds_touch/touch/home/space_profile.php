<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{if $_GET['mycenter'] && !$_G['uid']}-->
	<!--{eval dheader('Location:member.php?mod=logging&action=login');exit;}-->
<!--{/if}-->
<!--{template common/header}-->

<!--{if !$_GET['mycenter']}-->

<!-- header start -->
<header class="header">
    <div class="nav">
		<a href="javascript:;" onclick="history.go(-1);" class="z"><img src="{STATICURL}image/mobile/images/icon_back.png" /></a>
		<span><!--{if $_G['uid'] == $space['uid']}-->{lang myprofile}<!--{else}-->$space[username]{lang otherprofile}<!--{/if}--></span>
    </div>
</header>
<!-- header end -->
<!-- userinfo start -->
<div class="userinfo">
	<div class="user_avatar">
	<div class="">
		<div class="avatar_m z dsm_uinfoz"><span><img src="<!--{avatar($space[uid], small, true)}-->" /></span> </div>
		<div class="dsm_uinfoc z">
			<h2 class="names">$space[username]</h2>
			<span class="pipe">|</span><span>(UID: {$space[uid]})</span>
			<p class="dsm_homegroup" ><!--{if $_G['group']['allowbanuser']}--> <a href="forum.php?mod=modcp&action=member&op=ban&uid={$space[uid]}" class="" style="color:red;">{lang ban_member}</a><span class="pipe">|</span><!--{/if}-->{lang usergroup}：<span {if $space[adminid]}style="color:red;"{/if}><!--{if $space[adminid]}-->{$space[admingroup][grouptitle]}<!--{else}-->{$space[group][grouptitle]}<!--{/if}--></span></p>
			
			<p><a href="home.php?mod=space&uid={$space[uid]}&do=profile&uview=1" style="color: #4F6AB5;">详细资料&gt;&gt;</a></p>
			
			
		</div>
		<div class="dsm_uinfoy y">
		<!--{if $space[uid] == $_G[uid]}-->
	<li class="dsm_uinfoan1"><a href="home.php?mod=space&do=wall"class="dsm_aniu">看留言</a></li>
		<li class="dsm_uinfoan2" ><a href="home.php?mod=spacecp&ac=profile&op=password" class="dsm_aniu">改密码</a></li>
	<!--{else}-->
		<!--{eval require_once libfile('function/friend');$isfriend=friend_check($space[uid]);}-->
				<!--{if !$isfriend}-->
				
				<li class="dsm_uinfoan1"><a href="home.php?mod=spacecp&ac=friend&op=add&uid=$space[uid]&handlekey=addfriendhk_$space[uid]" class="dsm_aniu">++好友</a></li>
				<!--{else}-->
				<li class="dsm_uinfoan1"><a href="home.php?mod=spacecp&ac=friend&op=ignore&uid=$space[uid]&handlekey=ignorefriendhk_{$space[uid]}" class="dsm_aniu">解除好友</a></li>
		<!--{/if}-->
		<li class="dsm_uinfoan2"><a href="home.php?mod=spacecp&ac=pm&touid=$space[uid]" class="dsm_aniu">发消息</a></li>
		<!--{/if}-->
		</div>
		<div class="clear"></div>
		
		
		
	</div>
	</div>
	<!--{if $_G['basescript'] == 'home' && $_GET['mod'] == 'space' && $_GET['uview'] == '1'}-->
<!--{template home/space_dsm_userinfo}-->
<!--{else}-->
	<div class="dsm_utotal">
			<ul class="dsm_totbd">
			<li><a href="{if CURMODULE != 'follow'}home.php?mod=space&uid=$space[uid]&do=thread&view=me&type=thread&from=space{else}home.php?mod=space&uid=$space[uid]&view=thread{/if}" target="_blank"><i class="dsm_uifont">&nbsp;</i>主题<strong>$space[threads]</strong></a></li>
			
			<li><a href="{if CURMODULE != 'follow'}home.php?mod=space&uid=$space[uid]&do=thread&view=me&type=reply&from=space{else}home.php?mod=space&uid=$space[uid]&view=thread&type=reply{/if}" target="_blank"><i class="dsm_uifont">&nbsp;</i>回复<strong><!--{eval echo $space['posts'] - $space['threads'] ;}--></strong></a></li>	
			<!--{if $space[uid] != $_G[uid]}--><li><a href="home.php?mod=space&uid={$space[uid]}&do=wall" target="_blank"><i class="dsm_uifont">&nbsp;</i>留言板</a></li>	
			<!--{else}-->
			<!--{/if}-->
			<!--<!--{if $space[uid] != $_G[uid]}-->
			<li><a href="home.php?mod=spacecp&ac=poke&op=send&uid=$space[uid]&handlekey=propokehk_{$space[uid]}" id="a_poke_{$space[uid]}" onclick="showWindow(this.id, this.href, 'get', 0);" ><i class="dsm_uifont">&nbsp;</i>打招呼</a></li>	
				<!--{/if}-->-->
				
		
			
		</ul>
		</div>
	
	<!--{if $space[uid] == $_G[uid]}-->
	<ul class="dsm_uicli" style="height: auto;">
            <!--{template dsportal/ds_user}-->
        </ul>
	<!--{/if}-->
	
<!--{if $space['medals']}-->
	<div class="dsm_utogroup dsm_utotal" style="background: #fff;">
		<div class="dsm_tothd">
			<h2 class="title">{lang medals}</h2>
			<span><a href="home.php?mod=medal" >勋章中心</a><i class="dsm_ui-more"></i></span>
		</div>
		<ul class="dsm_totbd dsm_umedals">
		<li>
		<!--{loop $space['medals'] $medal}-->
				<img src="{STATICURL}/image/common/$medal[image]" alt="$medal[name]" id="md_{$medal[medalid]}" onmouseover="showMenu({'ctrlid':this.id, 'menuid':'md_{$medal[medalid]}_menu', 'pos':'12!'});" />
			<!--{/loop}-->
			</li>
		</ul>
	</div>
	<!--{/if}-->
	
	
	
	<div class="dsm_utogroup dsm_utotal" style="background: #fff;">
		<div class="dsm_tothd">
			<h2 class="title">个性签名</h2>
			<span><!--{if helper_access::check_module('wall') && $space[uid] != $_G[uid]}--><a href="javascript:document.getElementById('commentsubmit_btn').focus()" >给Ta留言</a><!--{/if}--><i class="dsm_ui-more"></i></span>
		</div>
		<ul class="dsm_totbd">
		
		<li style="padding: 5px;">
		<!--{if $space[group][maxsigsize] && $space[sightml]}-->
			$space[sightml]
			<!--{else}-->
			
			<!--{if $space[uid] == $_G[uid]}-->您当前尚未编辑个性签名
			<!--{else}-->
			Ta 未设置个性签名~~
			<!--{/if}-->
		<!--{/if}-->
		</li>
		</ul>
	</div>
	
	<!--{if $count}-->
	<div class="dsm_utoadmfor dsm_utotal" style="background: #fff;">
		<div class="dsm_tothd">
			<h2 class="title">{lang manage_forums}<!--管理版块--></h2>
		</div>
		<ul class="dsm_totbd">
		<div>
			<!--{loop $manage_forum $key $value}-->
		<li><a href="forum.php?mod=forumdisplay&fid=$key" target="_blank">$value</a> &nbsp;</li>
		<!--{/loop}-->
		</div>
		</ul>
	</div>
	<!--{/if}-->
	
	
	
	
	<!--{if $space[uid] != $_G[uid]}-->
	<!--留言开始-->
	<!--{if helper_access::check_module('wall')}-->
	<form id="quickcommentform_{$space[uid]}" action="home.php?mod=spacecp&ac=comment" method="post" autocomplete="off" onsubmit="ajaxpost('quickcommentform_{$space[uid]}', 'return_qcwall_$space[uid]');doane(event);" style="margin: 10px;">
			
			<div class="tedt mtn mbn">
				<div class="area dsm_kuanipcc">
					<!--{if $_G['uid'] || $_G['group']['allowcomment']}-->
						<textarea id="comment_message" onkeydown="ctrlEnter(event, 'commentsubmit_btn');" name="message" rows="5" ></textarea>
					<!--{elseif $_G['connectguest']}-->
						<div class="pt hm">{lang connect_fill_profile_to_comment}</div>
					<!--{else}-->
						<div class="pt hm">{lang login_to_wall} <a rel="nofollow" href="member.php?mod=logging&action=login" class="xi2">{lang login}</a> | <a rel="nofollow" href="member.php?mod={$_G[setting][regname]}" class="xi2">$_G['setting']['reglinkname']</a></div>
					<!--{/if}-->
				</div>
			</div>
			
			<p>
				<input type="hidden" name="referer" value="home.php?mod=space&uid=$space[uid]&do=wall" />
				<input type="hidden" name="id" value="$space[uid]" />
				<input type="hidden" name="idtype" value="uid" />
				<input type="hidden" name="handlekey" value="qcwall_{$space[uid]}" />
				<input type="hidden" name="commentsubmit" value="true" />
				<input type="hidden" name="quickcomment" value="true" />
				
				<button type="submit" name="commentsubmit_btn" value="true" id="commentsubmit_btn" class="formdialog pn dsm_kuanibt" ><strong>给Ta{lang leave_comments}</strong></button>
				<span id="return_qcwall_{$space[uid]}"></span>
			</p>
			<input type="hidden" name="formhash" value="{FORMHASH}" />
		</form>
		<div class="clear"></div>
		
		
		<!--{/if}-->
<!--留言结束-->
<!--{/if}-->
		
	<!--{/if}-->
	
	<!--{if $space['uid'] == $_G['uid']}-->
	<div class="btn_exit"><a href="member.php?mod=logging&action=logout&formhash={FORMHASH}">{lang logout_mobile}</a></div>
	<!--{/if}-->
</div>
<!-- userinfo end -->

<!--{else}-->

<!-- header start -->
<header class="header">
	<div class="hdc cl">
		<!--{if $_G['setting']['domain']['app']['mobile']}-->
			{eval $nav = 'http://'.$_G['setting']['domain']['app']['mobile'];}
		<!--{else}-->
			{eval $nav = "forum.php";}
		<!--{/if}-->
		<h2><a title="$_G[setting][bbname]" href="$nav"><img src="{STATICURL}image/mobile/images/logo.png" /></a></h2>
		<ul class="user_fun">
			<li><a href="search.php?mod=forum" class="icon_search">{lang search}</a></li>
			<li><a href="forum.php?forumlist=1" class="icon_threadlist">{lang forum_list}</a></li>
			<li class="on" id="usermsg"><a href="<!--{if $_G[uid]}-->home.php?mod=space&uid=$_G[uid]&do=profile&mycenter=1<!--{else}-->member.php?mod=logging&action=login<!--{/if}-->" class="icon_userinfo">{lang user_info}</a><!--{if $_G[member][newpm]}--><span class="icon_msg"></span><!--{/if}--></li>
			<!--{if $_G['setting']['mobile']['mobilehotthread']}-->
			<li><a href="forum.php?mod=guide&view=hot" class="icon_hotthread">{lang hot_thread}</a></li>
			<!--{/if}-->
		</ul>
	</div>
</header>
<!-- header end -->
<!-- userinfo start -->
<div class="userinfo">
	<div class="user_avatar">
		<div class="avatar_m"><span><img src="<!--{avatar($_G[uid], small, true)}-->" /></span></div>
		<h2 class="name">$_G[username]</h2>
	</div>
	<div class="myinfo_list cl">
		<ul>
			<li><a href="home.php?mod=space&uid={$_G[uid]}&do=favorite&view=me&type=thread">{lang myfavorite}</a></li>
			<li><a href="home.php?mod=space&uid={$_G[uid]}&do=thread&view=me">{lang mythread}</a></li>
			<li class="tit_msg"><a href="home.php?mod=space&do=pm">{lang mypm}<!--{if $_G[member][newpm]}--><img src="{STATICURL}image/mobile/images/icon_msg.png" /><!--{/if}--></a></li>
			<li><a href="home.php?mod=space&uid={$_G[uid]}">{lang myprofile}</a></li>
		</ul>
	</div>
</div>
<!-- userinfo end -->

<!--{/if}-->

<!--{template common/footer}-->
<!--{eval $nofooter = true;}-->