<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{if $actives[profile]}-->
	{lang memcp_profile}
<!--{elseif $actives[verify]}-->
	{lang memcp_verify}
<!--{elseif $actives[avatar]}-->
	{lang memcp_avatar}
<!--{elseif $actives[credit]}-->
	{lang memcp_credit}
<!--{elseif $actives[usergroup]}-->
	{lang memcp_usergroup}
<!--{elseif $actives[privacy]}-->
	{lang memcp_privacy}
<!--{elseif $actives[sendmail]}-->
	{lang memcp_sendmail}
<!--{elseif $actives[password]}-->
	{lang password_security}
<!--{elseif $actives[promotion]}-->
	{lang memcp_promotion}
<!--{elseif $actives[plugin]}-->
	{$_G['setting']['plugins'][$pluginkey][$_GET['id']]['name']}
<!--{/if}-->