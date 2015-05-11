<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<div class="displaylist">
			<ul>
            <!--{if $list['threadcount']}-->

						<!--{loop $list['threadlist'] $key $thread}-->
							<li>
<!--{hook/forumdisplay_thread_mobile $key}-->
                    <a href="forum.php?mod=viewthread&tid=$thread[tid]&extra=$extra">
                    <h2  $thread[highlight] >
                    <!--{if in_array($thread['displayorder'], array(1, 2, 3, 4))}-->
						<span class="icon"><img src="$_G['style']['tpldir']/touch/images/img/image_s.png"></span>
					<!--{elseif $thread['digest'] > 0}-->
						<span class="icon"><img src="$_G['style']['tpldir']/touch/images/img/icon_digest.png"></span>
					<!--{elseif $thread['attachment'] == 2 && $_G['setting']['mobile']['mobilesimpletype'] == 0}-->
						<span class="icon"><img src="$_G['style']['tpldir']/touch/images/img/image_s.png"></span>
					<!--{/if}-->
                    
					{$thread[subject]}
					
					</h2>
                    <p class="cl">
                    <span class="num">{$thread[replies]} / $thread[views]</span>
                    <span class="by">$thread[author] - $thread[lastpost]</span>
                    </p>
                    </a>
							</li>
						<!--{/loop}-->
		
				<!--{else}-->
					<p class="nos">{lang guide_nothreads}</p>
				<!--{/if}-->
		</ul>
</div>