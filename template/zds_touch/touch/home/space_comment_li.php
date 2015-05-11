<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<li class="dsm_spacwalli">
<div class="avatar_img"><!--{if $value[author]}--><!--{avatar($value[authorid],small)}--><!--{else}--><img style="height:32px;width:32px;" src="{STATICURL}image/common/grouppm.png" /><!--{/if}--></div>
<div class="dsm_spwaliname">
<div class="cl">
<span class="y">
<!--{date($value[dateline], 'u')}-->



<!--{if $value[status] == 1}-->({lang moderate_need})<!--{/if}-->

</span>
<span class="name"><!--{if $value[author]}-->
		<a href="home.php?mod=space&uid=$value[authorid]" id="author_$value[cid]">{$value[author]}</a>
		<!--{else}-->
		$_G[setting][anonymoustext]
		<!--{/if}--> 留言:</span>
</div>
<div class="cl grey">

<span><!--{if $value[status] == 0 || $value[authorid] == $_G[uid] || $_G[adminid] == 1}-->$value[message]<!--{else}--> {lang moderate_not_validate} <!--{/if}--></span>


<span class="time"><!--{if $_G[uid]}-->
			<!--{if $value[authorid]==$_G[uid]}-->
				<a href="home.php?mod=spacecp&ac=comment&op=edit&cid=$value[cid]&handlekey=editcommenthk_{$value[cid]}" id="c_$value[cid]_edit" onclick="showWindow(this.id, this.href, 'get', 0);" class="dialog">{lang edit}</a>
			<!--{/if}-->
			<!--{if $value[authorid]==$_G[uid] || $value[uid]==$_G[uid] || checkperm('managecomment')}-->
				<a href="home.php?mod=spacecp&ac=comment&op=delete&cid=$value[cid]&handlekey=delcommenthk_{$value[cid]}" id="c_$value[cid]_delete" onclick="showWindow(this.id, this.href, 'get', 0);" class="dialog">{lang delete}</a>
			<!--{/if}-->
		<!--{/if}-->
		<!--{if $value[authorid]!=$_G[uid] && ($value['idtype'] != 'uid' || $space[self]) && $value[author]}-->
			<a href="home.php?mod=spacecp&ac=comment&op=reply&cid=$value[cid]&feedid=$feedid&handlekey=replycommenthk_{$value[cid]}" id="c_$value[cid]_reply" onclick="showWindow(this.id, this.href, 'get', 0);" class="dialog">{lang reply}</a>
		<!--{/if}--></span>
</div>
</div>
</li>

		



<a name="comment_anchor_$value[cid]"></a>
