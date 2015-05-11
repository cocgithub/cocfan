<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{template common/header}-->
<!--{if $tagname}-->
<div class="wps cl">
	<div class="pt">
		<a href="forum.php">{lang homepage}</a> <em>&rsaquo;</em>
		<a href="misc.php?mod=tag">{lang tag}</a>
		<!--{if $tagname}-->
			<em>&rsaquo;</em>
			<a href="misc.php?mod=tag&id=$id">$tagname</a>
		<!--{/if}-->
		<!--{if $showtype == 'thread'}-->
			<em>&rsaquo;</em> {lang related_thread}
		<!--{/if}-->
		<!--{if $showtype == 'blog'}-->
			<em>&rsaquo;</em> {lang related_blog}
		<!--{/if}-->
	</div>
</div>


<!--{if empty($showtype) || $showtype == 'thread'}-->
<div class="displaylist">
		<ul>
            <!--{if $threadlist}-->
				<!--{loop $threadlist $thread}-->
					<li>
						<a href="forum.php?mod=viewthread&tid=$thread[tid]&extra=$extra">
							<h2  $thread[highlight] >
									<!--{if $thread[folder] == 'lock'}-->
										<img src="{IMGDIR}/folder_lock.gif" />
									<!--{elseif $thread['special'] == 1}-->
										<img src="{IMGDIR}/pollsmall.gif" alt="{lang thread_poll}" />
									<!--{elseif $thread['special'] == 2}-->
										<img src="{IMGDIR}/tradesmall.gif" alt="{lang thread_trade}" />
									<!--{elseif $thread['special'] == 3}-->
										<img src="{IMGDIR}/rewardsmall.gif" alt="{lang thread_reward}" />
									<!--{elseif $thread['special'] == 4}-->
										<img src="{IMGDIR}/activitysmall.gif" alt="{lang thread_activity}" />
									<!--{elseif $thread['special'] == 5}-->
										<img src="{IMGDIR}/debatesmall.gif" alt="{lang thread_debate}" />
									<!--{elseif in_array($thread['displayorder'], array(1, 2, 3, 4))}-->
										<span class="icon"><img src="$_G['style']['tpldir']/touch/images/img/up.gif" alt="$_G[setting][threadsticky][3-$thread[displayorder]]"></span>
									<!--{elseif $thread['digest'] > 0}-->
										<span class="icon"><img src="$_G['style']['tpldir']/touch/images/img/icon_digest.png"></span>
									
									
									<!--{/if}-->
									<!--{if $thread['attachment'] == 2 && $_G['setting']['mobile']['mobilesimpletype'] == 0}-->
										<span class="icon"><img src="$_G['style']['tpldir']/touch/images/img/image_s.png"  alt="attach_img" title="{lang attach_img}" ></span>
									<!--{/if}-->
								{$thread[subject]}
							</h2>
							<p class="cl">
								<span class="num">{$thread[replies]} / $thread[views]</span>
								<span class="by">$thread[author] - <a href="forum.php?mod=forumdisplay&fid=$thread[fid]">$thread['forumname']</a> - $thread[lastpost]</span>
							</p>
						</a>
					</li>
				<!--{/loop}-->
				<!--{if empty($showtype)}-->
					<div>
						<a class="dsm_module-more" href="misc.php?mod=tag&id=$id&type=thread">{lang more}...</a>
					</div>
				<!--{else}-->
					<!--{if $multipage}--><div class="pgs mtm cl">$multipage</div><!--{/if}-->
				<!--{/if}-->
			<!--{else}-->
					<p class="nos">{lang no_content}</p>
			<!--{/if}-->
		</ul>
</div>
<!--{/if}-->


<!--{if helper_access::check_module('blog') && (empty($showtype) || $showtype == 'blog')}-->
		<div class="bm">
			<div class="bm_h">
				<h2>{lang related_blog}</h2>
			</div>
			<div class="bm_c">
				<!--{if $bloglist}-->
					<div class="xld xlda">
						<!--{loop $bloglist $blog}-->
							<dl class="bbda">
								<dd class="m">
									<div class="avt"><a href="home.php?mod=space&uid=$blog[uid]" target="_blank" c="1"><!--{avatar($blog[uid],small)}--></a></div>
								</dd>
								<dt class="xs2">
									<!--{if helper_access::check_module('share')}-->
									<a href="home.php?mod=spacecp&ac=share&type=blog&id=$blog[blogid]&handlekey=lsbloghk_{$blog[blogid]}" id="a_share_$blog[blogid]" onclick="showWindow(this.id, this.href, 'get', 0);" class="oshr xs1 xw0">{lang share}</a>
									<!--{/if}-->
									<a href="home.php?mod=space&uid=$blog[uid]&do=blog&id=$blog[blogid]" target="_blank">$blog['subject']</a>
								</dt>
								<dd>
									<!--{if $blog['hot']}--><span class="hot">{lang hot} <em>$blog[hot]</em> </span><!--{/if}-->
									<a href="home.php?mod=space&uid=$blog[uid]" target="_blank">$blog[username]</a> <span class="xg1">$blog[dateline]</span>
								</dd>
								<dd class="cl" id="blog_article_$blog[blogid]">
									<!--{if $blog[pic]}--><div class="atc"><a href="home.php?mod=space&uid=$blog[uid]&do=blog&id=$blog[blogid]" target="_blank"><img src="$blog[pic]" alt="$blog[subject]" class="tn" /></a></div><!--{/if}-->
									$blog[message]
								</dd>
								<dd class="xg1">
									<!--{if $blog[classname]}-->{lang personal_category}: <a href="home.php?mod=space&uid=$blog[uid]&do=blog&classid=$blog[classid]&view=me" target="_blank">{$blog[classname]}</a><span class="pipe">|</span><!--{/if}-->
									<!--{if $blog[viewnum]}--><a href="home.php?mod=space&uid=$blog[uid]&do=blog&id=$blog[blogid]" target="_blank">$blog[viewnum] {lang blog_read}</a><span class="pipe">|</span><!--{/if}-->
									<a href="home.php?mod=space&uid=$blog[uid]&do=blog&id=$blog[blogid]#comment" target="_blank"><span id="replynum_$blog[blogid]">$blog[replynum]</span> {lang blog_replay}</a>
								</dd>
							</dl>
						<!--{/loop}-->
					</div>
					<!--{if empty($showtype)}-->
						<div class="ptm">
							<a class="xi2" href="misc.php?mod=tag&id=$id&type=blog">{lang more}...</a>
						</div>
					<!--{else}-->
						<!--{if $multipage}--><div class="pgs mtm cl">$multipage</div><!--{/if}-->
					<!--{/if}-->
				<!--{else}-->
					<p class="emp">{lang no_content}</p>
				<!--{/if}-->
			</div>
		</div>
	<!--{/if}-->
	
	
	
<!--{else}-->

<div class="wps cl">
	<div class="pt">
		<a href="forum.php">{lang homepage}</a> <em>&rsaquo;</em>
		<a href="misc.php?mod=tag">{lang tag}</a>
		
	</div>
</div>

	<div id="ct" class="wp cl">
		
		<div class="search">
			
				<form method="post" action="misc.php?mod=tag" class="pns">
					<input type="text" name="name" class="input" size="30"  style="width: 80%;"/>&nbsp;
					<button type="submit" class="button2" style="width: 15%;"><em>{lang search}</em></button>
				</form>
				<div class="taglist mtm mbm"><p class="emp">{lang empty_tags}</p></div>
			
		</div>
	</div>
<!--{/if}-->

<!--{template common/footer}-->