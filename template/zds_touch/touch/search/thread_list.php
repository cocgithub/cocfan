<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<div class="threadlist" style="margin-bottom:20px">
	<h2 class="thread_tit"><!--{if $keyword}-->{lang search_result_keyword} <!--{if $modfid}--><a href="forum.php?mod=modcp&action=thread&fid=$modfid&keywords=$modkeyword&submit=true&do=search&page=$page" target="_blank">{lang goto_memcp}</a><!--{/if}--><!--{else}-->{lang search_result}<!--{/if}--></h2>
	<!--{if empty($threadlist)}-->
	<ul><li><a href="javascript:;">{lang search_nomatch}</a></li></ul>
	<!--{else}-->
			<ul>
				<!--{loop $threadlist $thread}-->
				<li>
					<a href="forum.php?mod=viewthread&tid=$thread[realtid]&highlight=$index[keywords]" target="_blank" $thread[highlight]>$thread[subject]</a>
				</li>
				<!--{/loop}-->
			</ul>
	<!--{/if}-->
	$multipage
</div>
