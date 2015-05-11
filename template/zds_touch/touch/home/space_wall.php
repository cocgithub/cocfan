<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{eval $_G['home_tpl_titles'] = array('{lang message}');}-->
<style>
#ntcmsg_popmenu.dialogbox form{background: #000;margin: 0 auto;-moz-border-radius: 5px;-webkit-border-radius: 5px;border-radius: 5px;text-align: center;padding: 15px;opacity: 0.85!important;color: #fff;}
#ntcmsg_popmenu.dialogbox .pt{display: none;}
#ntcmsg_popmenu.dialogbox form div.c{font-size: 15px;}
#ntcmsg_popmenu.dialogbox form .o .pn{height: 25px;
line-height: 25px;font-size: 17px;background: #f60;border: none;color: #fff;text-shadow: 0 0 1px #930;width: 100%;text-align: center;}
</style>


	<!--{template common/header}-->
	<div class="wps cl dsm_dxpm">
		
			<div class="">
				<div class="pt">
					<h1 style="font-weight:500;">您正给 {$space[username]}  留言</h1>
				</div>
			<div class="">


		<!--{if helper_access::check_module('wall')}-->
		<form id="quickcommentform_{$space[uid]}" action="home.php?mod=spacecp&ac=comment" method="post" autocomplete="off" onsubmit="ajaxpost('quickcommentform_{$space[uid]}', 'return_qcwall_$space[uid]');doane(event);">
			
			<div class="tedt mtn mbn">
				<div class="area dsm_kuanipcc">
					<!--{if $_G['uid'] || $_G['group']['allowcomment']}-->
						<textarea id="comment_message" onkeydown="ctrlEnter(event, 'commentsubmit_btn');" name="message" rows="5"></textarea>
					<!--{elseif $_G['connectguest']}-->
						<div class="pt hm">{lang connect_fill_profile_to_comment}</div>
					<!--{else}-->
						<div class="pt hm">{lang login_to_wall} <a rel="nofollow" href="member.php?mod=logging&action=login" class="xi2">{lang login}</a> | <a rel="nofollow" href="member.php?mod={$_G[setting][regname]}" class="xi2">$_G['setting']['reglinkname']</a></div>
					<!--{/if}-->
				</div>
			</div>
			<p>
				<input type="hidden" name="referer" value="home.php?mod=space&uid=$wall[uid]&do=wall" />
				<input type="hidden" name="id" value="$space[uid]" />
				<input type="hidden" name="idtype" value="uid" />
				<input type="hidden" name="handlekey" value="qcwall_{$space[uid]}" />
				<input type="hidden" name="commentsubmit" value="true" />
				<input type="hidden" name="quickcomment" value="true" />
				<button type="submit" name="commentsubmit_btn" value="true" id="commentsubmit_btn" class="formdialog pn dsm_kuanibt"><strong>给Ta{lang leave_comments}</strong></button>
				<span id="return_qcwall_{$space[uid]}"></span>
			</p>
			<input type="hidden" name="formhash" value="{FORMHASH}" />
		</form>
		<hr class="da mtm m0" />
		<!--{/if}-->
		
		
		<div class="pmbox">
		<ul>
			
			
			<!--{loop $list $k $value}-->
					<!--{template home/space_comment_li}-->
			<!--{/loop}-->
			
		</ul>
		<div class="pgs cl mtm">$multi</div>
	</div>
		
		
		<script type="text/javascript">
			var elems = selector('dd[class~=magicflicker]');
			for(var i=0; i<elems.length; i++){
				magicColor(elems[i]);
			}
			function succeedhandle_qcwall_{$space[uid]}(url, msg, values) {
				wall_add(values['cid']);
			}
		</script>

		<!--{if !$_G[setting][homepagestyle]}--><!--[diy=diycontentbottom]--><div id="diycontentbottom" class="area"></div><!--[/diy]--><!--{/if}-->

		
		</div>
	</div>

<!--{if !$_G[setting][homepagestyle]}-->
	<div class="wp mtn">
		<!--[diy=diy3]--><div id="diy3" class="area"></div><!--[/diy]-->
	</div>
<!--{/if}-->

<!--{subtemplate common/footer}-->