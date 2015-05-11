<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{template common/header}-->
<div class="wps">
<div class="ct">
	<div class="pt">
		<a href="./" class="nvhm" title="$_G[setting][bbname]">{lang homepage}</a> <em>&rsaquo;</em>
		<a href="home.php?mod=spacecp">{lang setup}</a> <em>&rsaquo;</em>
		<!--{template home/spacecp_header_name}-->
	</div>
	</div>
</div>
<!--{template home/spacecp_footer}-->



					<!--{if $switchtype == 'user'}--><!--{eval $cid = 1;$tlang = '{lang usergroup_group1}';}--><!--{/if}-->
					<!--{if $switchtype == 'upgrade'}--><!--{eval $cid = 2;$tlang = '{lang usergroup_group2}';}--><!--{/if}-->
					<!--{if $switchtype == 'admin'}--><!--{eval $cid = 3;$tlang = '{lang usergroup_group3}';}--><!--{/if}-->
					
					<div class="material-box">
							<div class="material-mod">
								<span class="material-name" style="width: auto;">我的用户组:</span>
								<div class="data-info-public">
									<span {if !$group}style="color: #fd6a02;"{/if}>$_G[group][grouptitle]</span>
								</div>
								
							</div>
							<div class="material-mod">
								<span class="material-name" style="width: auto;">晋级用户组:</span>
								<div class="data-info-public">
									<span><!--{if $group}-->$currentgrouptitle
									<!--{else}-->
									当前无法晋级
									<!--{/if}-->
									</span>
								</div>
								
							</div>
							<!--{if $group}--><div class="material-mod">
							
							<!--{if $group['grouptype'] == 'member'}-->
										<!--{eval $v = $group[groupcreditshigher] - $_G['member']['credits'];}-->
										<!--{if $_G['group']['grouptype'] == 'member' && $v > 0}-->
											<span class="notice material-name" style="width: auto;color: #fd6a02">{lang spacecp_usergroup_message1} $v</span>
										<!--{else}-->
											<span class="notice material-name" style="width: auto;color: #fd6a02;">{lang spacecp_usergroup_message2} $group[groupcreditshigher]</span>
										<!--{/if}-->
									<!--{/if}-->
							</div>
							<!--{/if}-->
							<div class="material-mod">
								<span class="material-name" style="width: auto;">我的积分 :</span>
								<div class="data-info-public">
									<span>$_G[member][credits]</span>
								</div>
							</div>
							<div class="material-mod">
								<span class="material-name" style="width: auto;"><!--{loop $_G[setting][extcredits] $key $value}-->
			<!--{if $value[title]}-->
			<span style="padding:0 10px;">$value[title]:{$space["extcredits$key"]} $value[unit]</span>
			<!--{/if}-->
			<!--{/loop}--></span>
								
							</div>
							
							<!--<div class="material-mod">
								<span class="material-name" style="width: auto;"><a href="/">点击查看积分获取规则</a></span>
								
							</div>-->
							
						</div>
					
					
				
			
					
				
<!--{template common/footer}-->
