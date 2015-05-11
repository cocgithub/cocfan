<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<style>
.dms_uxianginfo ul.dsm_totbd li.z{width: 49.5%;border-right: 1px solid #FAFAFA;}
</style>
<div class="dms_uxianginfo dsm_utotal" style="background: #fff;">
		<div class="dsm_tothd">
			<h2 class="title">详细资料</h2>
		</div>
		<ul class="dsm_totbd">
		<div style="width: 100%;">
			<li class="z"><span class="dms_uxianinfoz z">{lang credits}：</span><span class="dms_uxianinfoz z">$space[credits] </span></li>
			<!--{loop $_G[setting][extcredits] $key $value}-->
			<!--{if $value[title]}-->
			<li class="z"><span class="dms_uxianinfoz z">$value[title]:</span><span class="dms_uxianinfoy z">{$space["extcredits$key"]} $value[unit]</span></li>
			<!--{/if}-->
			<!--{/loop}-->
			<div class="clear"></div>
			<!--{loop $profiles $value}-->
				
				<li><span class="dms_uxianinfoz z">$value[title]:</span><span class="dms_uxianinfoy z">$value[value]</span></li>
			<!--{/loop}-->
			<!--{if $space[extgroupids]}-->
			<li><span class="dms_uxianinfoz z">{lang group_expiry_type_ext}：</span><span class="dms_uxianinfoy z">$space[extgroupids]</span></li>
			<!--{/if}-->
			<!--{if $space[customstatus]}-->
			<li><span class="dms_uxianinfoz z">{lang permission_basic_status}：</span><span class="dms_uxianinfoy z">$space[customstatus]</span></li>
			<!--{/if}-->
			<li><span class="dms_uxianinfoz z">总{lang online_time}：</span><span class="dms_uxianinfoy z">$space[oltime] {lang hours}</span></li>
			<li><span class="dms_uxianinfoz z">{lang regdate}:</span><span class="dms_uxianinfoy z">$space[regdate]</span></li>
			<li><span class="dms_uxianinfoz z">最后活动时间：</span><span class="dms_uxianinfoy z">$space[lastvisit]</span></li>
			<li><span class="dms_uxianinfoz z">最后发表时间：</span><span class="dms_uxianinfoy z">$space[lastpost]</span></li>
			<!--{if $_G[uid] == $space[uid] || $_G[group][allowviewip]}-->
			
			<li><span class="dms_uxianinfoz z">{lang register_ip}：</span><span class="dms_uxianinfoy z">$space[regip] - $space[regip_loc]</span></li>
			<li><span class="dms_uxianinfoz z">{lang last_visit_ip}：</span><span class="dms_uxianinfoy z">$space[lastip] - $space[lastip_loc]</span></li>
			<!--{/if}-->
			<li><span class="dms_uxianinfoz z">Email：</span><span class="dms_uxianinfoy z">$space[email]</span></li>
			<li><span class="dms_uxianinfoz z">{lang email_status}：</span><span class="dms_uxianinfoy z"><!--{if $space[emailstatus] > 0}-->{lang profile_verified}<!--{else}-->{lang profile_no_verified}<!--{/if}--></span></li>
			
			
		</div>
		</ul>
	</div>