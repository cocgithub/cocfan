<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{if $thread['freemessage']}-->
	<div id="postmessage_$pid" class="t_f">$thread[freemessage]</div>
<!--{/if}-->
<!--{if empty($_GET['archiver'])}-->
	<div class="locked">
			
			<em class="right">
				<!--{if $thread[payers]}-->{lang have} $thread[payers] {lang people_buy}&nbsp; <!--{/if}-->
			</em>
			<!--{if $_G[forum_thread][price] > 0}-->{lang pay_comment}<!--{/if}-->
			<!--{if $thread[endtime]}--><br />{lang pay_free_time}<!--{/if}-->
			<p><a href="forum.php?mod=misc&action=pay&tid=$_G[tid]&pid=$post[pid]{if !empty($_GET['from'])}&from=$_GET['from']{/if}" class="dialog" title="{lang pay}">{lang pay}</a><span class="pipe">|</span><a href="forum.php?mod=misc&action=viewpayments&tid=$_G[tid]" >{lang pay_view}</a></p>
		</div>
	
<!--{else}-->
	<!--{if $thread[payers]}-->{lang have} $thread[payers] {lang people_buy}&nbsp; <!--{/if}-->
	<!--{if $_G[forum_thread][price] > 0}-->{lang pay_comment}<!--{/if}-->
	<!--{if $thread[endtime]}--><br />{lang pay_free_time}<!--{/if}-->
<!--{/if}-->