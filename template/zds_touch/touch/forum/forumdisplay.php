<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{template common/header}-->
<div class="dsm_forumdisplay">
<div class="wps cl">
     <div class="pt">
         <a href="forum.php">{lang homepage}</a>$navigation 
    </div>
	<li>
	<style>
	
	
	</style>
            <figure>
                	<!--{if $_G['forum'][icon]}-->
				<!--{eval $_G['forum'][icon] =  get_forumimg($_G['forum']['icon']);}-->
					<a href="forum.php?mod=forumdisplay&fid=$_G['forum'][fid]"><img src="$_G['forum'][icon]" alt="$_G['forum']['name']" class="dsm_flimg" /></a>
				<!--{else}-->
					<a href="forum.php?mod=forumdisplay&fid=$_G['forum'][fid]"><img src="$_G['style']['tpldir']/touch/images/img/forum{if $forum[folder]}_new{/if}.gif" alt="$forum[name]" class="dsm_flimg"  /></a>
				<!--{/if}-->
                    <figcaption>
					<div style="margin: 5px 0;">
                        <h3 class="ds_sjfltit">$_G['forum'][name]</h3>
						
						<span class="displaylist nmt">
						
						<!--{if $moderatedby}--><a href="javascript:;" id="dsm_fbanzhu" onclick="dbox('dsm_fbanzhu','dsm_fbanzhu');" style="color: red;">版主</a><!--{/if}-->
						<!--{if $_G['page'] == 1 && $_G['forum']['rules']}-->
						<a href="javascript:;" id="dsm_fguize" onclick="dbox('dsm_fguize','dsm_fguize');"  style="color: #5F5F5F;">规则</a><!--{/if}-->
                         <!--{if !$_G[forum][favtimes]}-->
                        <a href="home.php?mod=spacecp&ac=favorite&type=forum&id={$_G[fid]}" class="favbtn dsm_flitopfav " style="color: #1A3EFF;">收藏</a>
						<!--{else}-->
						<!--{eval $favorite = C::t('home_favorite')->fetch_by_id_idtype($_G['fid'], 'fid', $_G['uid']);}-->
                        <i class="con">
                        <a href="home.php?mod=spacecp&ac=favorite&op=delete&favid=$favorite['favid']" class="favbtn dsm_flitopfav" style="color: #1A3EFF;">已关注</a></i>
						<!--{/if}-->
						</span>
						 
						</div>
						
						<!--{if $moderatedby}-->
						<div id="dsm_fbanzhu_menu"  style="display:none"> 
						


  
            	<div class="l3"><span class="t">（版 主：</span>
            	<span class="n">
            	$moderatedby ）
            	</span>
            	<div class="clear"></div></div>
            	<!--    else
            	<div class="13">本版尚未有版版上任~~（<a href="/forum-1-1.html" style="color: #1A3EFF;">立即申请</a>）</div>-->
            	  
				</div>	
				<!--{/if}--> 
				
				<!--{if $_G['forum'][description] }-->
                        <p class="dsm_fodispabout">
						{$_G['forum'][description]}
                        </p>
				<!--{else}-->
						<div class="dsm_flifatfengleimk">
                        <a href="forum.php?mod=post&action=newthread&fid=$_G[fid]" class="dsm_flifat dsm_aniu">+ 发帖</a>
						<div class="y">
  
<span class="dsm_flifengleibtn" >
	 <a href="javascript:;" id="dsm_fdiztfl" onclick="dbox('dsm_fdiztfl','dsm_fdiztfl');"  style="color: #5F5F5F;"><i class=""></i><em>分类</em></a>
</span>
<!--{if $subexists && $_G['page'] == 1}-->
<span class="dsm_flifengleibtn dsm_flimorebtn">
	<a href="javascript:;" id="dsm_flimorebtn" onclick="dbox('dsm_flimorebtn','dsm_flimorebtn');"><i class=""></i><em>更多</em></a>
</span>
<!--{/if}--> 
</div>
						</div>
				<!--{/if}--> 
                    </figcaption>
                </figure>
            </li>
			<!--{if $_G['page'] == 1 && $_G['forum']['rules']}-->
	<div id="dsm_fguize_menu" class="rules" style="$collapse['forum_rules'];display:none;" >
		<div class="ptn">$_G['forum'][rules]</div>
	</div>
<!--{/if}-->
			<!--{if $_G['forum'][description] }-->
			<li class="dsm_flifatfengleimk">
						
                        <a href="forum.php?mod=post&action=newthread&fid=$_G[fid]" class="dsm_flifat z dsm_aniu">+ 发帖</a>
						<div class="y">
  
<span class="dsm_flifengleibtn" >
	 <a href="javascript:;" id="dsm_fdiztfl" onclick="dbox('dsm_fdiztfl','dsm_fdiztfl');"  style="color: #5F5F5F;"><i class=""></i><em>分类</em></a>
</span>
<!--{if $subexists && $_G['page'] == 1}-->
<span class="dsm_flifengleibtn dsm_flimorebtn">
	<a href="javascript:;" id="dsm_flimorebtn" onclick="dbox('dsm_flimorebtn','dsm_flimorebtn');"><i class=""></i><em>更多</em></a>
</span>
<!--{/if}--> 
</div>
			</li>
			<!--{/if}--> 
			
	


<!--{if $subexists && $_G['page'] == 1}-->
<div id="dsm_flimorebtn_menu" class="sub_list cl list-c" style="display:none;">
<ul class="dsm_forlistli" id="sub_forum_$cat[fid]" class="sub_forum ">
<!--{loop $sublist $sub}-->
<!--{eval $forumurl = !empty($sub['domain']) && !empty($_G['setting']['domain']['root']['forum']) ? 'http://'.$sub['domain'].'.'.$_G['setting']['domain']['root']['forum'] : 'forum.php?mod=forumdisplay&fid='.$sub['fid'];}-->
    
         <li style="padding: 10px;">
            	<figure>				
         <!--{if $sub[icon]}-->
			$sub[icon]
		 <!--{else}-->
			<a href="$forumurl"{if $sub[redirect]} target="_blank"{/if}><img src="$_G['style']['tpldir']/touch/images/img/forum{if $sub[folder]}_new{/if}.gif" alt="$sub[name]"  height="31" width="31"/></a>
		 <!--{/if}-->
         
         <h3 class="tit"><a href="forum.php?mod=forumdisplay&fid={$sub[fid]}">{$sub['name']}</a></h3>
               		<p style="font-size: 0.875rem;
color: #ccc;">$sub[todayposts]</p>
					
</figure>
            </li>
   
<!--{/loop}-->
</ul>
</div>
<!--{/if}--> 



<!--{hook/forumdisplay_top_mobile}-->



					<div id="dsm_fdiztfl_menu" style="display:none;">
					<ul id="thread_types" class="ttp bm cl">
						<!--{hook/forumdisplay_threadtype_inner}-->
						
						<!--{if ($_G['forum']['threadtypes'] && $_G['forum']['threadtypes']['listable']) || count($_G['forum']['threadsorts']['types']) > 0}-->
						<!--{if $_G['forum']['threadtypes']}-->
							<!--{loop $_G['forum']['threadtypes']['types'] $id $name}-->
								<!--{if $_GET['typeid'] == $id}-->
								<li class="a"><a href="forum.php?mod=forumdisplay&fid=$_G[fid]{if $_GET['sortid']}&filter=sortid&sortid=$_GET['sortid']{/if}{if $_GET['archiveid']}&archiveid={$_GET['archiveid']}{/if}"><!--{if $_G[forum][threadtypes][icons][$id] && $_G['forum']['threadtypes']['prefix'] == 2}--><img class="vm" src="$_G[forum][threadtypes][icons][$id]" alt="" /> <!--{/if}-->$name</a></li>
								<!--{else}-->
								<li><a href="forum.php?mod=forumdisplay&fid=$_G[fid]&filter=typeid&typeid=$id$forumdisplayadd[typeid]{if $_GET['archiveid']}&archiveid={$_GET['archiveid']}{/if}"><!--{if $_G[forum][threadtypes][icons][$id] && $_G['forum']['threadtypes']['prefix'] == 2}--><img class="vm" src="$_G[forum][threadtypes][icons][$id]" alt="" /> <!--{/if}-->$name</a></li>
								<!--{/if}-->
							<!--{/loop}-->
						<!--{/if}-->
					<!--{/if}-->
					</ul>
					</div>

</div>



     <!--{if CURMODULE != 'guide'}-->
<div class="displayguide">
		<div class="tf">
						<a href="forum.php?mod=forumdisplay&fid=$_G[fid]" class="showmenu {if !$_GET['typeid'] && !$_GET['sortid'] && !$_GET['filter'] && $_GET['filter']}a{/if}"  >{lang threads_all}</a>&nbsp;						
						<a href="forum.php?mod=forumdisplay&fid=$_G[fid]&filter=author&orderby=dateline" class="{if $_GET['filter'] == 'author'} a{/if}">新帖</a>&nbsp;
						<a href="forum.php?mod=forumdisplay&fid=$_G[fid]&filter=heat&orderby=heats$forumdisplayadd[heat]{if $_GET['archiveid']}&archiveid={$_GET['archiveid']}{/if}" class="{if $_GET['filter'] == 'heat'} a{/if}">{lang order_heats}</a>&nbsp;
						<a href="forum.php?mod=forumdisplay&fid=$_G[fid]&filter=hot" class="{if $_GET['filter'] == 'hot'} a{/if}">{lang hot_thread}</a>&nbsp;
						<a href="forum.php?mod=forumdisplay&fid=$_G[fid]&filter=digest&digest=1$forumdisplayadd[digest]{if $_GET['archiveid']}&archiveid={$_GET['archiveid']}{/if}" class="{if $_GET['filter'] == 'digest'} a{/if}">{lang digest_posts}</a>&nbsp;
			

		</div>
</div>
        
<!--{/if}-->   

<!--{if !$subforumonly}-->


		<!--{if $page == 1}-->
		
			<!--{if !empty($announcement) && !$_G['setting']['mobile']['mobiledisplayorder3']}-->
			<ul class="dsm_foli_zdul">
	        <li>
			<!--{if empty($announcement['type'])}--><a href="forum.php?mod=announcement&id=$announcement[id]#$announcement[id]" class="tit" target="_blank"><span class="dsm_foli_zdtubiao dsm_foli_zdtbgg">&nbsp;</span>$announcement[subject]</a>
			<!--{else}-->
			<a href="$announcement[message]" class="tit"  target="_blank">
			<span class="dsm_foli_zdtubiao dsm_foli_zdtbgg">&nbsp;</span>
			$announcement[subject]</a><!--{/if}-->
			</li></ul>
	        <!--{/if}-->
		
		<!--{if $_G['setting']['mobile']['mobiledisplayorder3']}-->
		<div class="dsm_folizhiding">
		<div class="dsm_folizd1" >
		<ul class="dsm_foli_zdul">
			<!--{if !empty($announcement)}-->
	        <li>
			<!--{if empty($announcement['type'])}--><a href="forum.php?mod=announcement&id=$announcement[id]#$announcement[id]" class="tit" target="_blank"><span class="dsm_foli_zdtubiao dsm_foli_zdtbgg">&nbsp;</span>$announcement[subject]</a>
			<!--{else}-->
			<a href="$announcement[message]" class="tit"  target="_blank">
			<span class="dsm_foli_zdtbgg dsm_foli_zdtubiao">&nbsp;</span>
			$announcement[subject]</a><!--{/if}-->
			</li>
	        <!--{/if}-->
			
			<!--{if $_G['forum_threadcount']}-->
			<!--{loop $_G['forum_threadlist'] $key $thread}-->
			<!--{if !$_G['setting']['mobile']['mobiledisplayorder3'] && $thread['displayorder'] > 0}-->
			{eval continue;}
			<!--{/if}-->
			<!--{if $thread['displayorder'] > 0 && !$displayorder_thread}-->
			{eval $displayorder_thread = 1;}
			<!--{/if}-->
			<!--{if $thread['moved']}-->
			<!--{eval $thread[tid]=$thread[closed];}-->
			<!--{/if}-->
			<!--{if in_array($thread['displayorder'], array(1, 2, 3, 4))}-->
			{eval $istop = 1;}
			<li>
				<a href="forum.php?mod=viewthread&tid=$thread[tid]&extra=$extra" $thread[highlight]>
				
				 <!--{if in_array($thread['displayorder'], array(1, 2, 3, 4))}-->
					<span class="dsm_foli_zdtubiao dsm_foli_zdtubiao{$thread['displayorder']}"></span>
				
				<!--{/if}-->
				
				{$thread[subject]}</a>
			</li>
			<!--{/if}-->
			<!--{/loop}-->
			<!--{/if}-->
		</ul>
		</div> 
		</div> 
<!--{/if}-->
	<!--{/if}-->


<div class="displaylist">
			<ul>
			<!--{if $_G['forum_threadcount']}-->
				<!--{loop $_G['forum_threadlist'] $key $thread}-->
					<!--{if !in_array($thread['displayorder'], array(1, 2, 3, 4))}-->
					<li>
					<!--{hook/forumdisplay_thread_mobile $key}-->
                    <a href="forum.php?mod=viewthread&tid=$thread[tid]&extra=$extra">
                    <h2  $thread[highlight] >
                   
					<!--{if $thread['digest'] > 0}-->
						<span class="icon"><img src="$_G['style']['tpldir']/touch/images/img/icon_digest.png"></span>
						
					<!--{elseif  $_G['setting']['mobile']['mobilesimpletype'] == 0}-->
						
						<!--{if $thread[folder] == 'lock'}-->
                            <img src="$_G['style']['tpldir']/touch/images/img/folder_lock.png" />
                        <!--{elseif $thread['special'] == 1}-->
                            <img src="$_G['style']['tpldir']/touch/images/img/pollsmall.png" alt="{lang thread_poll}" />
                        <!--{elseif $thread['special'] == 2}-->
                            <img src="{IMGDIR}/tradesmall.gif" alt="{lang thread_trade}" />
                        <!--{elseif $thread['special'] == 3}-->
                            <img src="{IMGDIR}/rewardsmall.gif" alt="{lang thread_reward}" />
                        <!--{elseif $thread['special'] == 4}-->
                            <img src="{IMGDIR}/activitysmall.gif" alt="{lang thread_activity}" />
                        <!--{elseif $thread['special'] == 5}-->
                            <img src="{IMGDIR}/debatesmall.gif" alt="{lang thread_debate}" />
							<!--{elseif $thread['attachment'] == 2}-->
                            <span class="icon"><img src="$_G['style']['tpldir']/touch/images/img/image_s.png"></span>
						<!--{/if}-->
                        
                        
					<!--{/if}-->
                    
					{$thread[subject]}
					
					</h2>
                    <p class="cl">
                    <span class="num">{$thread[replies]} / $thread[views]</span>
                    <span class="by">$thread[author] - $thread[lastpost]</span>
                    </p>
                    </a>
					</li>
					<!--{/if}-->
                <!--{/loop}-->
            <!--{else}-->
			<li class="no">{lang forum_nothreads}</li>
			<!--{/if}-->
		</ul>
</div>
<!--{if $multipage}-->
<div class="wps pgwps cl">
$multipage
</div>
<!--{/if}-->
<!--{/if}-->
</div>


<script type="text/javascript">
(function($){
$.fn.Postlist_re=function(o){
		var flag=1;
		var rel=$(this).find("ul li").length;
		
		if(rel>4){
			$(this).append("<div class='dsm_foli_zdhidean'></div>");
			$(".dsm_folizhiding .dsm_folizd1").height(155);
		};
		$(this).find(".dsm_foli_zdhidean").click(function(){
			if(flag==1){
				$(".dsm_folizhiding .dsm_folizd1").height('auto');
				$(this).addClass("up");
				flag=0;
			}else{
				$(".dsm_folizhiding .dsm_folizd1").height(155);
				$(this).removeClass("up");
				flag=1;
			}
		});
	};
	})(jQuery)
	$(".dsm_folizhiding").Postlist_re();


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


<!--{hook/forumdisplay_bottom_mobile}-->

<!--{template common/footer}-->
