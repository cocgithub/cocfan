<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{eval $_G['home_tpl_titles'] = array('{lang remind}');}-->
<!--{template common/header}-->
<div class="wps cl"> 
<div class="pt"><a href="forum.php">{lang homepage}</a> <em> &gt; </em> <a href="home.php?mod=space&do=notice">{lang remind}</a></div>
<!--{if $_G['setting']['version'] != 'X3'}-->
	<div class="dsm_fengexian">
		<ul class="ttp bm cl">
			<!--{loop $_G['notice_structure'] $key $type}-->
		<li $opactives[$key]><em class="notice_$key"></em><a href="home.php?mod=space&do=notice&view=$key"><!--{eval echo lang('template', 'notice_'.$key)}--><!--{if $_G['member']['category_num'][$key]}-->($_G['member']['category_num'][$key])<!--{/if}--></a></li>
		<!--{/loop}-->
		<!--{if $_G['setting']['my_app_status']}-->
		<li$actives[userapp]><em class="notice_userapp"></em><a href="home.php?mod=space&do=notice&view=userapp">{lang applications_news}{if $mynotice}($mynotice){/if}</a></li>
		<!--{/if}-->
			<!--
			<li {if $_GET['view'] == 'mypost'} class="a"{/if}>
				<a href="home.php?mod=space&do=notice&view=mypost">论坛提醒<!--{if $_G['member']['category_num'][mypost]}-->
				<span style="color: red;">($_G['member']['category_num'][mypost])</span><!--{/if}--></a>
			</li>
			<li {if $_GET['view'] == 'interactive'} class="a"{/if}>
				<a href="home.php?mod=space&do=notice&view=interactive">坛友互动<!--{if $_G['member']['category_num'][interactive]}-->
				<span style="color: red;">($_G['member']['category_num'][interactive])</span><!--{/if}--></a>
			</li>
			<li {if $_GET['view'] == 'system'} class="a"{/if}>
				<a href="home.php?mod=space&do=notice&view=system">系统提醒<!--{if $_G['member']['category_num'][system]}-->
				<span style="color: red;">($_G['member']['category_num'][system])</span><!--{/if}--></a>
				
			</li>-->
			<li><a href="home.php?mod=space&do=pm">短消息</a></li>
			</ul>
			</div>
<!--{/if}-->
<div class="dsm_notilist">

			<!--{if empty($list)}-->
			<div class="wmt">
				<!--{if $_GET[isread] != 1}-->
					{lang no_new_notice}<a href="home.php?mod=space&do=notice&isread=1">{lang view_old_notice}</a>
				<!--{else}-->
					{lang no_notice}
				<!--{/if}-->
			</div>
			<!--{/if}-->

			<!--{if $list}-->

					<!--{loop $list $key $value}-->
							<div class="dsm_notlibm">

								<p class="mbm">
								<a href="home.php?mod=spacecp&ac=common&op=ignore&authorid=$value[authorid]&type=$value[type]&handlekey=addfriendhk_{$value[authorid]}" class="y xi2 dialog" >({lang shield})</a>
								<span class="xg1"><!--{date($value[dateline], 'u')}--></span>
								<!--{if $value[style]}--><span class="dsm_notnew">new</span><!--{/if}-->
								</p>
                                
								<p class="xg3">$value[note]</p>
                                
								<!--{if $value[from_num]}-->
								<p class="mtn ptn bt xg3">{lang ignore_same_notice_message}</p>
								<!--{/if}-->
							</div>
						<!--{/loop}-->

				<!--{if $view!='userapp' && $space[notifications]}-->
				<div class="wmt"><a href="home.php?mod=space&do=notice&ignore=all">{lang ignore_same_notice_message}</a></div>
				<!--{/if}-->

				<!--{if $multi}--><div class="pgbox">$multi</div><!--{/if}-->
			<!--{/if}-->

		</div>
        </div>

<!--{subtemplate common/footer}-->