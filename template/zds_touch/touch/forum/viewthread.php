<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{eval $threadsort = $threadsorts = true;}-->
<!--{template common/header}-->
<!--{eval loadcache('plugin');}-->
<div class="wps cl dsm_fview" >
    <div class="pt">
         <a href="forum.php">首页</a>$navigation  
		  <div class="y">
		  
		  <!--{if $_G[uid]}-->
         <a href="forum.php?mod=post&action=reply&fid=$_G[fid]&tid=$_G[tid]&reppost=$post[pid]&page=$page" class="dsm_aniu" style="width: 45px;color: #fff;background: #eb1a1c;">{lang reply}</a><!--{/if}-->
         
    </div>
    </div>

<!--{hook/viewthread_top_mobile}-->
<!-- main postlist start -->
<div class="postlist">
	<h2>
        	<!--{if $_G['forum_thread']['typeid'] && $_G['forum']['threadtypes']['types'][$_G['forum_thread']['typeid']]}-->
				[{$_G['forum']['threadtypes']['types'][$_G['forum_thread']['typeid']]}]
            <!--{/if}-->
            <!--{if $threadsorts && $_G['forum_thread']['sortid']}-->
                [{$_G['forum']['threadsorts']['types'][$_G['forum_thread']['sortid']]}]
			<!--{/if}-->
			$_G[forum_thread][subject]
            <!--{if $_G['forum_thread'][displayorder] == -2}--> <span>({lang moderating})</span>
            <!--{elseif $_G['forum_thread'][displayorder] == -3}--> <span>({lang have_ignored})</span>
            <!--{elseif $_G['forum_thread'][displayorder] == -4}--> <span>({lang draft})</span>
            <!--{/if}-->
			<!--{if !IS_ROBOT && !$_GET['authorid'] && !$_G['forum_thread']['archiveid']}-->
				<a href="forum.php?mod=viewthread&tid=$_G[tid]&page=$page&authorid=$_G[forum_thread][authorid]" rel="nofollow" class="blue" style="font-size:12px;font-weight:normal;margin-left:10px;">{lang viewonlyauthorid}</a>
			<!--{elseif !$_G['forum_thread']['archiveid']}-->
				<a href="forum.php?mod=viewthread&tid=$_G[tid]&page=$page" rel="nofollow" class="blue" style="font-size:12px;font-weight:normal;margin-left:10px;">{lang thread_show_all}</a>
			<!--{/if}-->
	</h2>

	<!--{eval $postcount = 0;}-->
	<!--{loop $postlist $post}-->
	<!--{eval $needhiddenreply = ($hiddenreplies && $_G['uid'] != $post['authorid'] && $_G['uid'] != $_G['forum_thread']['authorid'] && !$post['first'] && !$_G['forum']['ismoderator']);}-->
	<!--{hook/viewthread_posttop_mobile $postcount}-->
   <div class="plc cl {if $post['first']}&nbsp;{else}dsm_fviewlou{/if}" id="pid$post[pid]">
	   
       <div class="display pi " href="#replybtn_$post[pid]">
       <div {if $post['first']}class="dsm_vititle"{/if}>
       <span class="avatar"><a href="home.php?mod=space&uid=$post[authorid]" ><img src="<!--{if !$post['authorid'] || $post['anonymous']}--><!--{avatar(0, small, true)}--><!--{else}--><!--{avatar($post[authorid], small, true)}--><!--{/if}-->" style="width:32px;height:32px;" /></a></span>
       <ul class="authi">
				<li class="{if $post['first']}dsm_fvieflo1st{/if}  {if $post['number']== 2}dsm_fvieflo2nd{/if} 
				 {if $post['number']== 3}dsm_fvieflo3rd{/if}
				  {if $post['number']== 4}dsm_fvieflo4th{/if}
				 grey">
					<em>
						<!--{if isset($post[isstick])}-->
							<img src ="{IMGDIR}/settop.png" title="{lang replystick}" class="vm" /> {lang from} {$post[number]}{$postnostick}
						<!--{elseif $post[number] == -1}-->
							{lang recommend_post}
						<!--{else}-->
							<!--{if !empty($postno[$post[number]])}-->$postno[$post[number]]<!--{else}-->{$post[number]}{$postno[0]}<!--{/if}-->
						<!--{/if}-->
					</em>
					
					
					<b>
					<!--{if $post['authorid'] && $post['username'] && !$post['anonymous']}-->
						<a href="home.php?mod=space&uid=$post[authorid]" class="blue">$post[author]</a></b>

					<!--{else}-->
						<!--{if !$post['authorid']}-->
						<a href="javascript:;">{lang guest} <em>$post[useip]{if $post[port]}:$post[port]{/if}</em></a>
						<!--{elseif $post['authorid'] && $post['username'] && $post['anonymous']}-->
						<!--{if $_G['forum']['ismoderator']}--><a href="home.php?mod=space&uid=$post[authorid]" target="_blank">{lang anonymous}</a><!--{else}-->{lang anonymous}<!--{/if}-->
						<!--{else}-->
						$post[author] <em>{lang member_deleted}</em>
						<!--{/if}--></b>
					<!--{/if}--> 
					(<span {if $post[adminid]} style="color: #EB852F;"{/if}>{$post[authortitle]}</span>)
					<!--{if $post['invisible'] == 0}-->
                            <!--{if !IS_ROBOT && !$_GET['authorid'] && !$_G['forum_thread']['archiveid']}-->
                                <a href="forum.php?mod=viewthread&tid=$post[tid]&page=$page&authorid=$post[authorid]" rel="nofollow" class=" grey" >{lang thread_show_author}</a>
                            <!--{elseif !$_G['forum_thread']['archiveid']}-->
                                <a href="forum.php?mod=viewthread&tid=$post[tid]&page=$page" rel="nofollow" class="grey">{lang thread_show_all}</a>
                            <!--{/if}-->
                        <!--{/if}-->
				</li>
				<li class="grey rela">
					<!--{if $_G['forum']['ismoderator']}-->
					<!-- manage start -->
					<!--{if $post[first]}-->
						<em><a href="#moption_$post[pid]" class="popup blue">{lang manage}</a></em>
						<div id="moption_$post[pid]" popup="true" class="manage" style="display:none;">
							<!--{if !$_G['forum_thread']['special']}-->
							<input type="button" value="{lang edit}" class="redirect button" href="forum.php?mod=post&action=edit&fid=$_G[fid]&tid=$_G[tid]&pid=$post[pid]<!--{if $_G[forum_thread][sortid]}--><!--{if $post[first]}-->&sortid={$_G[forum_thread][sortid]}<!--{/if}--><!--{/if}-->{if !empty($_GET[modthreadkey])}&modthreadkey=$_GET[modthreadkey]{/if}&page=$page">
							<!--{/if}-->
							<input type="button" value="{lang delete}" class="dialog button" href="forum.php?mod=topicadmin&action=moderate&fid={$_G[fid]}&moderate[]={$_G[tid]}&operation=delete&optgroup=3&from={$_G[tid]}">
							<input type="button" value="{lang modmenu_stickpost}" class="dialog button" href="forum.php?mod=topicadmin&action=moderate&fid={$_G[fid]}&moderate[]={$_G[tid]}&operation=stick&optgroup=1&from={$_G[tid]}">
							<input type="button" value="{lang admin_digest_add}" class="dialog button" href="forum.php?mod=topicadmin&action=moderate&fid={$_G[fid]}&moderate[]={$_G[tid]}&operation=digest&optgroup=1&from={$_G[tid]}">
							<input type="button" value="{lang thread_moved}" class="dialog button" href="forum.php?mod=topicadmin&action=moderate&fid={$_G[fid]}&moderate[]={$_G[tid]}&operation=move&optgroup=2&from={$_G[tid]}">
							<input type="button" value="{lang modmenu_type}" class="dialog button" href="forum.php?mod=topicadmin&action=moderate&fid={$_G[fid]}&moderate[]={$_G[tid]}&operation=type&optgroup=2&from={$_G[tid]}">
							<input type="button" value="{lang close}" class="dialog button" href="forum.php?mod=topicadmin&action=moderate&fid={$_G[fid]}&moderate[]={$_G[tid]}&from={$_G[tid]}&optgroup=4">
							<input type="button" value="{lang admin_banpost}" class="dialog button" href="forum.php?mod=topicadmin&action=banpost&fid={$_G[fid]}&tid={$_G[tid]}&topiclist[]={$_G[forum_firstpid]}">
							<input type="button" value="{lang topicadmin_warn_add}" class="dialog button" href="forum.php?mod=topicadmin&action=warn&fid={$_G[fid]}&tid={$_G[tid]}&topiclist[]={$_G[forum_firstpid]}">
						</div>
					<!--{else}-->
						<em><a href="#moption_$post[pid]" class="popup blue">{lang manage}</a></em>
						<div id="moption_$post[pid]" popup="true" class="manage" style="display:none;">
							<input type="button" value="{lang edit}" class="redirect button" href="forum.php?mod=post&action=edit&fid=$_G[fid]&tid=$_G[tid]&pid=$post[pid]{if !empty($_GET[modthreadkey])}&modthreadkey=$_GET[modthreadkey]{/if}&page=$page">
							<!--{if $_G['group']['allowdelpost']}--><input type="button" value="{lang modmenu_deletepost}" class="dialog button" href="forum.php?mod=topicadmin&action=delpost&fid={$_G[fid]}&tid={$_G[tid]}&operation=&optgroup=&page=&topiclist[]={$post[pid]}"><!--{/if}-->
							<!--{if $_G['group']['allowbanpost']}--><input type="button" value="{lang modmenu_banpost}" class="dialog button" href="forum.php?mod=topicadmin&action=banpost&fid={$_G[fid]}&tid={$_G[tid]}&operation=&optgroup=&page=&topiclist[]={$post[pid]}"><!--{/if}-->
							<!--{if $_G['group']['allowwarnpost']}--><input type="button" value="{lang modmenu_warn}" class="dialog button" href="forum.php?mod=topicadmin&action=warn&fid={$_G[fid]}&tid={$_G[tid]}&operation=&optgroup=&page=&topiclist[]={$post[pid]}"><!--{/if}-->
							<!--{if $_G['group']['allowstickreply']}--><input type="button" value="{lang modmenu_stickpost}" class="dialog button" href="forum.php?mod=topicadmin&action=stickreply&fid={$_G[fid]}&tid={$_G[tid]}&operation=&optgroup=&page=&topiclist[]={$post[pid]}"><!--{/if}-->
						</div>
					<!--{/if}-->
					<!-- manage end -->
					<!--{/if}-->
					<!--{if $post[first]}-->
					<em><a href="home.php?mod=spacecp&ac=favorite&type=thread&id=$_G[tid]" class="favbtn blue" <!--{if $_G[forum][ismoderator]}-->style="margin-right:10px;"<!--{/if}-->>{lang favorite}</a></em>
					<!--{/if}-->
					$post[dateline]
				</li>
            </ul>
       </div>
	   <!--{hook/viewthread_modaction}-->
		   <!--{if !IS_ROBOT && $post['first'] && !$_G['forum_thread']['archiveid']}-->
			<!--{if !empty($lastmod['modaction'])}--><div class="dsm_modact"><a href="forum.php?mod=misc&action=viewthreadmod&tid=$_G[tid]" title="{lang thread_mod}" onclick="showWindow('viewthreadmod', this.href)"><!--{if $lastmod['modactiontype'] == 'REB'}-->{lang thread_mod_recommend_by}<!--{else}-->{lang thread_mod_by}<!--{/if}--></a></div><!--{/if}-->
			<!--{hook/viewthread_modaction}-->
		<!--{/if}-->
		
			<div class="message">
                	<!--{if $post['warned']}-->
                        <span class="grey quote">{lang warn_get}</span>
                    <!--{/if}-->
                    <!--{if !$post['first'] && !empty($post[subject])}-->
                        <h2><strong>$post[subject]</strong></h2>
                    <!--{/if}-->
                    <!--{if $_G['adminid'] != 1 && $_G['setting']['bannedmessages'] & 1 && (($post['authorid'] && !$post['username']) || ($post['groupid'] == 4 || $post['groupid'] == 5) || $post['status'] == -1 || $post['memberstatus'])}-->
                        <div class="grey quote">{lang message_banned}</div>
                    <!--{elseif $_G['adminid'] != 1 && $post['status'] & 1}-->
                        <div class="grey quote">{lang message_single_banned}</div>
                    <!--{elseif $needhiddenreply}-->
                        <div class="grey quote">{lang message_ishidden_hiddenreplies}</div>
                    <!--{elseif $post['first'] && $_G['forum_threadpay']}-->
						<!--{template forum/viewthread_pay}-->
					<!--{else}-->

                    	<!--{if $_G['setting']['bannedmessages'] & 1 && (($post['authorid'] && !$post['username']) || ($post['groupid'] == 4 || $post['groupid'] == 5))}-->
                            <div class="grey quote">{lang admin_message_banned}</div>
                        <!--{elseif $post['status'] & 1}-->
                            <div class="grey quote">{lang admin_message_single_banned}</div>
                        <!--{/if}-->
                        <!--{if $_G['forum_thread']['price'] > 0 && $_G['forum_thread']['special'] == 0 && $post['first']}-->
                            {lang pay_threads}: <strong>$_G[forum_thread][price] {$_G['setting']['extcredits'][$_G['setting']['creditstransextra'][1]][unit]}{$_G['setting']['extcredits'][$_G['setting']['creditstransextra'][1]][title]} </strong> <a href="forum.php?mod=misc&action=viewpayments&tid=$_G[tid]" >{lang pay_view}</a>
                        <!--{/if}-->

                        <!--{if $post['first'] && $threadsort && $threadsortshow}-->
                        	<!--{if $threadsortshow['optionlist'] && !($post['status'] & 1) && !$_G['forum_threadpay']}-->
                                <!--{if $threadsortshow['optionlist'] == 'expire'}-->
                                    {lang has_expired}
                                <!--{else}-->
                                    <div class="box_ex2 viewsort">
                                        <h4>$_G[forum][threadsorts][types][$_G[forum_thread][sortid]]</h4>
                                    <!--{loop $threadsortshow['optionlist'] $option}-->
                                        <!--{if $option['type'] != 'info'}-->
                                            $option[title]: <!--{if $option['value']}-->$option[value] $option[unit]<!--{else}--><span class="grey">--</span><!--{/if}--><br />
                                        <!--{/if}-->
                                    <!--{/loop}-->
                                    </div>
                                <!--{/if}-->
                            <!--{/if}-->
                        <!--{/if}-->
                        <!--{if $post['first']}-->
                            <!--{if !$_G[forum_thread][special]}-->
                                $post[message]
                            <!--{elseif $_G[forum_thread][special] == 1}-->
                                <!--{template forum/viewthread_poll}-->
                            <!--{elseif $_G[forum_thread][special] == 2}-->
                                <!--{template forum/viewthread_trade}-->
                            <!--{elseif $_G[forum_thread][special] == 3}-->
                                <!--{template forum/viewthread_reward}-->
                            <!--{elseif $_G[forum_thread][special] == 4}-->
                                <!--{template forum/viewthread_activity}-->
                            <!--{elseif $_G[forum_thread][special] == 5}-->
                                <!--{template forum/viewthread_debate}-->
                            <!--{elseif $threadplughtml}-->
                                $threadplughtml
                                $post[message]
                            <!--{else}-->
                            	$post[message]
                            <!--{/if}-->
                        <!--{else}-->
                            $post[message]
                        <!--{/if}-->

					<!--{/if}-->
			</div>
			<!--{if $_G['setting']['mobile']['mobilesimpletype'] == 0}-->
			<!--{if $post['attachment']}-->
               <div class="grey quote dsm_vquote">
               {lang attachment}: <em><!--{if $_G['uid']}-->{lang attach_nopermission}<!--{else}-->{lang attach_nopermission_login}<!--{/if}--></em>
               </div>
            <!--{elseif $post['imagelist'] || $post['attachlist']}-->
               <!--{if $post['imagelist']}-->
				<!--{if count($post['imagelist']) == 1}-->
				<ul class="img_one">{echo showattach($post, 1)}</ul>
				<!--{else}-->
				<ul class="img_list cl vm">{echo showattach($post, 1)}</ul>
				<!--{/if}-->
				<!--{/if}-->
                <!--{if $post['attachlist']}-->
				<ul>{echo showattach($post)}</ul>
				<!--{/if}-->
			<!--{/if}-->
			<!--{/if}-->
			<!--{if $_G[uid] && $allowpostreply && !$post[first]}-->
			<div id="replybtn_$post[pid]" class="replybtn" display="true" style="display:none;position:absolute;right:0px;top:5px;">
				<!--{if $_G[uid] == $post[authorid] }--><input type="button" class="redirect button" href="forum.php?mod=post&action=edit&fid=$_G[fid]&tid=$_G[tid]&pid=$post[pid]&page=$page" value="{lang edit}">
				<!--{/if}-->
				<input type="button" class="redirect button" href="forum.php?mod=post&action=reply&fid=$_G[fid]&tid=$_G[tid]&repquote=$post[pid]&extra=$_GET[extra]&page=$page" value="{lang reply}">
			</div>
			<!--{/if}-->
       </div>
   </div>
   <!--{if $post['first']}-->
  <div class="dsm_fvidloux"><!--{if $ordertype != 1}-->
	<a href="forum.php?mod=viewthread&tid=$_G[tid]&extra=$_GET[extra]&ordertype=1&threads=thread" class="xi2">{lang post_descview}</a>
	<!--{else}-->
	<a href="forum.php?mod=viewthread&tid=$_G[tid]&extra=$_GET[extra]&ordertype=2&threads=thread" class="xi2">{lang post_ascview}</a>
	<!--{/if}-->    
    <span class="y">共有{$_G[forum_thread][replies]}条回复</span></div>
   <!--{/if}-->
   <!--{hook/viewthread_postbottom_mobile $postcount}-->
   <!--{eval $postcount++;}-->
   <!--{/loop}-->

	<!--{subtemplate forum/forumdisplay_fastpost}-->

</div>
<!-- main postlist end -->

$multipage

<!--{hook/viewthread_bottom_mobile}-->

<script type="text/javascript">
	$('.favbtn').on('click', function() {
		var obj = $(this);
		$.ajax({
			type:'POST',
			url:obj.attr('href') + '&handlekey=favbtn&inajax=1',
			data:{'favoritesubmit':'true', 'formhash':'{FORMHASH}'},
			dataType:'xml',
		})
		.success(function(s) {
			popup.open(s.lastChild.firstChild.nodeValue);
			evalscript(s.lastChild.firstChild.nodeValue);
		})
		.error(function() {
			window.location.href = obj.attr('href');
			popup.close();
		});
		return false;
	});
</script>
</div>
<a href="javascript:;" title="{lang scrolltop}" class="scrolltop bottom"></a>
<!--{subtemplate common/footer}-->
