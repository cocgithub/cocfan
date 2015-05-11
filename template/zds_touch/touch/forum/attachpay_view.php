<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{subtemplate common/header}-->

<div class="ct wps dsm_bgbai">
	<div class="pt bb"><a href="javascript:history.back();" onclick="javascript:history.back();" >{lang back}</a> <em> &gt; </em> {lang pay_view}</div>
	<div class="dsm_vaipc ptn">
		<table class="p_attc" cellspacing="5" cellpadding="5">
			<tr>
				<th>{lang username}</th>
				<th>{lang time}</th>
				<th>{$_G['setting']['extcredits'][$_G['setting']['creditstransextra'][1]][title]}</th>
			</tr>
			<!--{if $loglist}-->
				<!--{loop $loglist $log}-->
					<tr>
						<td><a href="home.php?mod=space&uid=$log[uid]">$log[username]</a></td>
						<td>$log[dateline]</td>
						<td>{$log[$extcreditname]} {$_G[setting][extcredits][$_G[setting][creditstransextra][1]][unit]}</td>
					</tr>
				<!--{/loop}-->
			<!--{else}-->
				<tr><td colspan="3"><div class="xg1" style="text-align:center;">{lang attachment_buy_not}</div></td></tr>
			<!--{/if}-->
		</table>
	</div>
</div>

<!--{subtemplate common/footer}-->