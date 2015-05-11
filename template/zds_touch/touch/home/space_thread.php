<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{template common/header}-->
<ul id="thread_types" class="ttp bm cl">
				<li {if $viewtype == 'reply' || $viewtype == 'postcomment'}{else} class="a"{/if}><a href="{if CURMODULE != 'follow'}home.php?mod=space&uid=$space[uid]&do=thread&view=me&type=thread&from=space{else}home.php?mod=space&uid=$space[uid]&view=thread{/if}">$space[username] 的主题</a></li>
				<li {if $viewtype == 'reply' || $viewtype == 'postcomment'} class="a"{/if}><a href="{if CURMODULE != 'follow'}home.php?mod=space&uid=$space[uid]&do=thread&view=me&type=reply&from=space{else}home.php?mod=space&uid=$space[uid]&view=thread&type=reply{/if}">$space[username] 的回复</a></li>
			
				<!--{hook/guide_nav_extra}-->
			</ul>

<!-- main threadlist start -->
<div class="threadlist displaylist ">
	<ul>
	<!--{if $list}-->
		<!--{loop $list $thread}-->
			<li>
			<!--{if $viewtype == 'reply' || $viewtype == 'postcomment'}-->
			<a href="forum.php?mod=redirect&goto=findpost&ptid=$thread[tid]&pid=$thread[pid]" target="_blank">
			<h2 $thread[highlight] >
                    <!--{if in_array($thread['displayorder'], array(1, 2, 3, 4))}-->
						<span class="icon"><img src="$_G['style']['tpldir']/touch/images/img/icon_top.png"></span>
					<!--{elseif $thread['digest'] > 0}-->
						<span class="icon"><img src="$_G['style']['tpldir']/touch/images/img/icon_digest.png"></span>
					<!--{elseif $thread['attachment'] == 2 && $_G['setting']['mobile']['mobilesimpletype'] == 0}-->
						<span class="icon"><img src="$_G['style']['tpldir']/touch/images/img/image_s.png"></span>
					<!--{/if}-->
			$thread[subject]
			</h2>
			</a>
			<!--{else}-->
			<a href="forum.php?mod=viewthread&tid=$thread[tid]" target="_blank" {if $thread['displayorder'] == -1}class="grey"{/if}>
			<h2  $thread[highlight] >
                    <!--{if in_array($thread['displayorder'], array(1, 2, 3, 4))}-->
						<span class="icon"><img src="$_G['style']['tpldir']/touch/images/img/icon_top.png"></span>
					<!--{elseif $thread['digest'] > 0}-->
						<span class="icon"><img src="$_G['style']['tpldir']/touch/images/img/icon_digest.png"></span>
					<!--{elseif $thread['attachment'] == 2 && $_G['setting']['mobile']['mobilesimpletype'] == 0}-->
						<span class="icon"><img src="$_G['style']['tpldir']/touch/images/img/image_s.png"></span>
					<!--{/if}-->
			$thread[subject]
			</h2>
			</a>
			<!--{/if}-->
			<p class="cl">
			<span class="num">{$thread[replies]} / {$thread[views]}</span>
			<span class="by">$thread[lastpost] - <a href="forum.php?mod=forumdisplay&fid=$thread[fid]" class="xg1" target="_blank">[{$forums[$thread[fid]]}]</a></span>
			
			</p>
			</li>
		<!--{/loop}-->
	<!--{else}-->
		<li>{lang no_related_posts}</li>
	<!--{/if}-->
	</ul>
	$multi
</div>
<!-- main threadlist end -->

<!--{template common/footer}-->
<!--{eval $nofooter = true;}-->