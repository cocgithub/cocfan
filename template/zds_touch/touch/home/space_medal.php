<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{template common/header}-->
<style>

</style>
<div class="wps cl"> 
<div class="pt"><a href="./" title="{lang homepage}">{lang homepage}</a> <em> &gt; </em> <a href="home.php?mod=medal">{lang medals}</a></div>
<ul class="ttp bm cl">

<li {if $_GET[action] == '' && $_GET[lqlog] == ''} class="a"{/if} ><a href="home.php?mod=medal">{lang medals_center}</a></li>
				<li {if $_GET[action] == 'log'} class="a"{/if}><a href="home.php?mod=medal&action=log">{lang my_medals}</a></li>
				<li {if $_GET[lqlog] == '1'} class="a"{/if}><a href="home.php?mod=medal&lqlog=1" >领取记录</a></li>
				<!--{hook/medal_nav_extra}-->
	
	</ul>
<div class="clear"></div>
			
			<!--{if $_GET[lqlog] == '1'}-->
				<!--{if $lastmedals}-->
					
					<ul class="el ptm pbw mbw ds_xzymlqlog">
						<!--{loop $lastmedals $lastmedal}-->
						<li>
							<a href="home.php?mod=space&uid=$lastmedal[uid]" class="t"><!--{avatar($lastmedal[uid],small)}--></a>
							<a href="home.php?mod=space&uid=$lastmedal[uid]" class="xi2">$lastmedalusers[$lastmedal[uid]][username]</a> {lang medals_message1} $lastmedal[dateline] {lang medals_message2} <strong>$medallist[$lastmedal['medalid']]['name']</strong> {lang medals}
						</li>
						<!--{/loop}-->
					</ul>
				<!--{/if}-->			
			<!--{elseif $_GET[action] == 'log'}-->

				<!--{if $mymedals}-->

					<ul class=" ds_xzulli" id="zds_medallist">
					
					<!--{loop $mymedals $mymedal}-->
					
						<li class="ds_xzmedali1" style="display: inline-block;width: 49%;">
							<figure>
								<img src="{STATICURL}image/common/$mymedal[image]" alt="$mymedal[name]" />
									<figcaption>
										<h3 class="tit"> 
											
										<i>$mymedal[name]</i></h3>
										<p>$mymedal[description]</p>
									</figcaption>
							</figure>
						</li>
					<!--{/loop}-->
					  
					</ul>
				<!--{/if}-->

				<!--{if $medallogs}-->
					
					<ul class="el ptm pbw mbw ds_xzymlqlog">
						<!--{loop $medallogs $medallog}-->
						<li style="padding-left:10px;">
							<!--{if $medallog['type'] == 2 || $medallog['type'] == 3}-->
								{lang medals_message3} $medallog[dateline] {lang medals_message4} <strong>$medallog[name]</strong> {lang medals},<!--{if $medallog['type'] == 2}-->{lang medals_operation_2}<!--{elseif $medallog['type'] == 3}-->{lang medals_operation_3}<!--{/if}-->
							<!--{elseif $medallog['type'] != 2 && $medallog['type'] != 3}-->
								{lang medals_message3} $medallog[dateline] {lang medals_message5} <strong>$medallog[name]</strong> {lang medals},<!--{if $medallog[expiration]}-->{lang expire}: $medallog[expiration]<!--{else}-->{lang medals_noexpire}<!--{/if}-->
							<!--{/if}-->
						</li>
						<!--{/loop}-->
					</ul>
					<!--{if $multipage}--><div class="pgs cl mtm">$multipage</div><!--{/if}-->
				<!--{else}-->
					<p class="emp">{lang medals_nonexistence_own}</p>
				<!--{/if}-->
			
			<!--{else}-->
				<!--{if $medallist}-->
					<!--{if $medalcredits}-->
						<div class="tbmu">
							{lang you_have_now}
							<!--{eval $i = 0;}-->
							<!--{loop $medalcredits $id}-->
								<!--{if $i != 0}-->, <!--{/if}-->{$_G['setting']['extcredits'][$id][img]} {$_G['setting']['extcredits'][$id][title]} <span class="xi1"><!--{echo getuserprofile('extcredits'.$id);}--></span> {$_G['setting']['extcredits'][$id][unit]}
								<!--{eval $i++;}-->
							<!--{/loop}-->
						</div>
					<!--{/if}-->
					<ul class=" ds_xzulli" id="zds_medallist">
					<!--{eval $i = 0;}-->
                    <!--{eval $pagesize = 6;}-->
					<!--{loop $medallist $key $medal}-->
					<!--{eval $i++;}-->	
						<li <!--{if $i%($pagesize)==0}-->class="ds_xzmedali1"<!--{/if}--> <!--{if $i>$pagesize}-->style="display:none"<!--{/if}--> id="medal$i">
							<figure>
								<img src="{STATICURL}image/common/$medal[image]" alt="$medal[name]" />
									<figcaption>
										<h3 class="tit"> 
											<a href="home.php?mod=medal&action=confirm&medalid=$medal[medalid]"  class="xi2 dialog">
											<!--{if in_array($medal[medalid], $membermedal)}-->
												<span class="y ds_xzhave btn btn-b ">{lang space_medal_have}</span>
											<!--{else}-->
												<!--{if $medal[type] && $_G['uid']}-->
													<!--{if in_array($medal[medalid], $mymedals)}-->
														<!--{if $medal['price']}-->
															<span class="y btn btn-b">{lang space_medal_purchased}</span>
														<!--{else}-->
															<!--{if !$medal[permission]}-->
																<span class="y btn btn-b ds_xzrgsy">{lang space_medal_applied}</span>
															<!--{else}-->
																<span class="y btn btn-b">{lang space_medal_receive}</span>
															<!--{/if}-->
														<!--{/if}-->
													<!--{else}-->
														<span class="y btn btn-b">
														<!--{if $medal['price']}-->
															{lang space_medal_buy}
														<!--{else}-->
															<!--{if !$medal[permission]}-->
																{lang medals_apply}
															<!--{else}-->
																{lang medals_draw}
															<!--{/if}-->
														<!--{/if}-->
														</span>
														
													<!--{/if}-->
												<!--{elseif $medal[type] == 0}-->
													<span class="y btn ds_xzrgsy">{lang medals_type_0}</span>
												<!--{/if}-->
											<!--{/if}-->
										</a>
										<i>$medal[name]</i></h3>
										<p>$medal[description]</p>
									</figcaption>
							</figure>
						</li>
					<!--{/loop}-->
					  <div class="ds_xzmedalimore" onclick="checkMore(this,'zds_medallist',1,{$pagesize})">查看更多</div>
					</ul>
					
				<!--{else}-->
					<!--{if $medallogs}-->
						<p class="emp">{lang medals_nonexistence}</p>
					<!--{else}-->
						<p class="emp">{lang medals_noavailable}</p>
					<!--{/if}-->
				
				<!--{/if}-->
				
				
			
			<!--{/if}-->
			

</div>

<script>
	function checkMore(obj,id,p,k){
		var j=p+1;
		var li=$("#"+id).find('li');	
		var pz= Math.ceil(li.length/$pagesize);
		for (var i=0; i <= li.length; i++) {
		  if(i>=p*k && i<=(p+1)*k){
		  	$('#medal'+i).show();
		  	li.eq(p*k-1).removeClass('ds_xzmedali1');
		  }	
		};
		var visible = $("#"+id).find('li:visible').length;
		var visible = $("#"+id).find('img:visible').length;
		if(visible%$pagesize!=0){
			li.last().addClass('ds_xzmedali1');
		}
		if(p<pz){
			$(obj).attr('onclick','checkMore(this,\''+id+'\','+j+',$pagesize)');
		}else{
			$(obj).attr('onclick','');
			$('.ds_xzmedalimore').html("暂无更多信息");
		}
		
		
	}
	
</script>


<!--{template common/footer}-->