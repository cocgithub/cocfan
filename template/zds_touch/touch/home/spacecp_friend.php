<?php echo 'DS��� ��֧������  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{subtemplate common/header}-->
<div class="wps cl">
	<div class="ct">

		<div class="pt"><a href="forum.php">{lang homepage}</a> <em> &gt; </em> <a href="home.php?mod=space&do=friend">{lang friends}</a> 
				
				<!--{if $op == 'find'}-->
					<em> &gt; </em> {lang people_might_know}
				<!--{elseif $op == 'request'}-->
					<em> &gt; </em> {lang friend_request}
				<!--{elseif $op == 'group'}-->
					<em> &gt; </em> {lang set_friend_group}
				<!--{elseif $op=='changegroup'}-->
					<em> &gt; </em> {lang set_friend_group}
				<!--{elseif $op=='add2'}-->			
					<em> &gt; </em> {lang approval_the_request}
				<!--{elseif $op =='ignore'}-->
					<em> &gt; </em> {lang lgnore_friend}
				<!--{elseif $op=='add'}-->
					<em> &gt; </em> {lang add_friend}
				<!--{/if}-->
			</div>
	</div>

	<div class="mmt mmt_gdf ttp bm cl">
	<ul class="">
	<li><a href="home.php?mod=space&do=friend"{if $a_actives[me]} class="a"{/if}>ȫ������</a></li>
	<!--{if empty($_G['setting']['sessionclose'])}--><li><a href="home.php?mod=space&do=friend&view=online&type=friend"{if $a_actives[onlinefriend]} class="a"{/if}>���ߺ���</a></li><!--{/if}-->
	<li><a href="home.php?mod=space&do=friend&view=blacklist"{if $a_actives[blacklist]} class="a"{/if}>������</a></li>
	<li><a href="home.php?mod=spacecp&ac=friend&op=request"{if $actives[request]} class="a"{/if}>{lang friend_request}</a></li>	
	</ul>
	</div>



		<!--{if $op =='ignore'}-->

			<div class="ipc">
            <form method="post" autocomplete="off" id="friendform_{$uid}" name="friendform_{$uid}" action="home.php?mod=spacecp&ac=friend&op=ignore&uid=$uid&confirm=1" {if $_G[inajax]}onsubmit="ajaxpost(this.id, 'return_$_GET[handlekey]');"{/if}>
				<input type="hidden" name="referer" value="{echo dreferer()}">
				<input type="hidden" name="friendsubmit" value="true" />
				<input type="hidden" name="formhash" value="{FORMHASH}" />
				<input type="hidden" name="from" value="$_GET[from]" />
				<!--{if $_G[inajax]}--><input type="hidden" name="handlekey" value="$_GET[handlekey]" /><!--{/if}-->
				<div class="box pbm mbm mtn xg1 inbox">{lang determine_lgnore_friend}</div>
				<div class="box mbm inbox">
					<button type="submit" name="friendsubmit_btn" class="ibt" value="true">{lang determine}</button>
				</div>
			</form>
            </div>
			<script type="text/javascript">
				function succeedhandle_{$_GET[handlekey]}(url, msg, values) {
					if(values['from'] == 'notice') {
						deleteQueryNotice(values['uid'], 'pendingFriend');
					} else if(typeof friend_delete == 'function') {
						friend_delete(values['uid']);
					}
				}
			</script>            
            
		<!--{elseif $op=='changegroup'}-->

			<form method="post" autocomplete="off" id="changegroupform_{$uid}" name="changegroupform_{$uid}" action="home.php?mod=spacecp&ac=friend&op=changegroup&uid=$uid" {if $_G[inajax]}onsubmit="ajaxpost(this.id, 'return_$_GET[handlekey]');"{/if}>
				<input type="hidden" name="referer" value="{echo dreferer()}">
				<input type="hidden" name="changegroupsubmit" value="true" />
				<!--{if $_G[inajax]}--><input type="hidden" name="handlekey" value="$_GET[handlekey]" /><!--{/if}-->
				<input type="hidden" name="formhash" value="{FORMHASH}" />
				<div class="ipc">
                <div class="pbb">
					<table><tr>
					<!--{eval $i=0;}-->
					<!--{loop $groups $key $value}-->
					<td style="padding:15px 15px 0 0;"><label><input type="radio" name="group" value="$key"$groupselect[$key] /> $value</label></td>
					<!--{if $i%2==1}--></tr><tr><!--{/if}-->
					<!--{eval $i++;}-->
					<!--{/loop}-->
					</tr></table>
				</div>
				<div class="dsm_frifzbox ptm mbm inbox">
					<button type="submit" name="changegroupsubmit_btn" class="dsm_aniu ibt" value="true"><strong>{lang determine}</strong></button>
				</div>
                </div>
			</form>
			<script type="text/javascript">
				function succeedhandle_$_GET[handlekey](url, msg, values) {
					friend_changegroup(values['gid']);
				}
			</script>

		<!--{elseif $op=='groupname'}-->
			
			<div id="__groupnameform_{$group}">
				<form method="post" autocomplete="off" id="groupnameform_{$group}" name="groupnameform_{$group}" action="home.php?mod=spacecp&ac=friend&op=groupname&group=$group" {if $_G[inajax]}onsubmit="ajaxpost(this.id, 'return_$_GET[handlekey]');"{/if}>
					<input type="hidden" name="referer" value="{echo dreferer()}">
					<input type="hidden" name="groupnamesubmit" value="true" />
					<!--{if $_G[inajax]}--><input type="hidden" name="handlekey" value="$_GET[handlekey]" /><!--{/if}-->
					<input type="hidden" name="formhash" value="{FORMHASH}" />
					<div class="c pt">
						<p>{lang set_friend_group_name}</p>
						<p>����ǰ���ѷ��顰<span style="color:red;">$groups[$group]</span>��</p>
						<p class="mtm">����Ϊ{lang new_name}��<input type="text" name="groupname" value="$groups[$group]" size="15" class="px" style="border: 1px solid #ccc;" /></p>
					</div>
					<p class="o pns">
						<button type="submit" name="groupnamesubmit_btn" value="true" class="formdialog pn pnc dsm_aniu" style="height: 35px;color: #fff;border: 1px solid #c78530;background: #f6a337;font-size: 18px;font-family: Arial,Helvetica,sans-serif;"><strong>{lang determine}</strong></button>
					</p>
				</form>
				<script type="text/javascript">
					function succeedhandle_$_GET[handlekey](url, msg, values) {
						friend_changegroupname(values['gid']);
					}
				</script>
			</div>
        

		
		<!--{elseif $op=='request'}-->
        
				<!--{if $list}-->
				<div class="dsm_wmt">
				
					<a href="home.php?mod=spacecp&ac=friend&op=addconfirm&key=$space[key]">{lang confirm_all_applications}</a><span class="pipe">|</span><a href="home.php?mod=spacecp&ac=friend&op=ignore&confirm=1&key=$space[key]" onclick="return confirm('{lang determine_ignore_all_friends_application}');">{lang ignore_all_friends_application}</a>
					
				</div>
				<!--{/if}--> 
                
			<!--{if $list}-->
			<div id="dsm_friul">
			<ul  class="buddy">
				<!--{loop $list $key $value}-->
				<li id="friend_tbody_$value[fuid]">

								<div class="friend_avt"><a href="home.php?mod=space&uid=$value[fuid]" c="1"><!--{avatar($value[fuid],small)}--></a></div>

								<div class="friend_nm mbb ptn">
									<a href="home.php?mod=space&uid=$value[fuid]">$value[fusername]</a>
									<!--{if $ols[$value[fuid]]}--><img src="{IMGDIR}/ol.gif" alt="online" title="{lang online}" class="vm" /> <!--{/if}-->
									<!--{if $value['videostatus']}-->
									<img src="{IMGDIR}/videophoto.gif" alt="videophoto" class="vm" /> <span class="xg1">{lang certified_by_video}</span>
									<!--{/if}-->
                                    <span class="xg1 y"><!--{date($value[dateline], 'n-j H:i')}--></span>
								</div>
								<div id="friend_$value[fuid]" class="xg1">
										<a href="home.php?mod=spacecp&ac=friend&op=add&uid=$value[fuid]&handlekey=afrfriendhk_{$value[uid]}" id="afr_$value[fuid]" onclick="showWindow(this.id, this.href, 'get', 0);" >{lang confirm_applications}</a>
                                        <span class="pipe">|</span> <a href="home.php?mod=spacecp&ac=friend&op=ignore&uid=$value[fuid]&confirm=1&handlekey=afifriendhk_{$value[uid]}" id="afi_$value[fuid]" onclick="showWindow(this.id, this.href, 'get', 0);" >{lang ignore}</a>									
								</div>

				</li>
				<!--{/loop}-->
			</ul>
			</div>
			<!--{if $multi}--><div class="pgbox">$multi</div><!--{/if}-->
			<!--{else}-->
			<div class="wmt">{lang no_new_friend_application}</div>
			<!--{/if}-->

		<!--{elseif $op=='add'}-->

			<form method="post" autocomplete="off" id="addform_{$tospace[uid]}" name="addform_{$tospace[uid]}" action="home.php?mod=spacecp&ac=friend&op=add&uid=$tospace[uid]" {if $_G[inajax]}onsubmit="ajaxpost(this.id, 'return_$_GET[handlekey]');"{/if}>
				<input type="hidden" name="referer" value="{echo dreferer()}" />
				<input type="hidden" name="addsubmit" value="true" />
				<!--{if $_G[inajax]}--><input type="hidden" name="handlekey" value="$_GET[handlekey]" /><!--{/if}-->
				<input type="hidden" name="formhash" value="{FORMHASH}" />
				<div class="ipc">
                <div class="pbb">
					<table>
						<tr>							
							<td valign="top">{lang add} <strong class="xi2">{$tospace[username]}</strong> {lang add_friend_note}:<br /><br />
								<p><input type="text" name="note" value="" size="35" style="width:100%" onkeydown="ctrlEnter(event, 'addsubmit_btn', 1);" /></p>
								<div class="mtn ipcll xg1">{lang view_note_message}</div>
								<div class="mtm">
									{lang friend_group}: <select name="gid" class="ps">
									<!--{loop $groups $key $value}-->
									<option value="$key" {if empty($space['privacy']['groupname']) && $key==1} selected="selected"{/if}>$value</option>
									<!--{/loop}-->
									</select>
								</div>
							</td>
						</tr>
					</table>
				</div>
				<div class="box ptm mbm inbox">
					<button type="submit" name="addsubmit_btn" id="addsubmit_btn" value="true" class="ibt">{lang determine}</button>
				</div>
                </div>
			</form>
		<!--{elseif $op=='add2'}-->

			<form method="post" autocomplete="off" id="addratifyform_{$tospace[uid]}" name="addratifyform_{$tospace[uid]}" action="home.php?mod=spacecp&ac=friend&op=add&uid=$tospace[uid]" {if $_G[inajax]}onsubmit="ajaxpost(this.id, 'return_$_GET[handlekey]');"{/if}>
				<input type="hidden" name="referer" value="{echo dreferer()}" />
				<input type="hidden" name="add2submit" value="true" />
				<input type="hidden" name="from" value="$_GET[from]" />
				<!--{if $_G[inajax]}--><input type="hidden" name="handlekey" value="$_GET[handlekey]" /><!--{/if}-->
				<input type="hidden" name="formhash" value="{FORMHASH}" />
				<div class="ipc">
                <div class="pbb">
					<table cellspacing="0" cellpadding="0">
						<tr>							
							<td valign="top">
								<div class="ptm">{lang approval_the_request_group}:</div>
								<table><tr>
								<!--{eval $i=0;}-->
								<!--{loop $groups $key $value}-->
								<td style="padding:15px 15px 0 0;"><label for="group_$key"><input type="radio" name="gid" id="group_$key" value="$key"$groupselect[$key] /> $value</label></td>
								<!--{if $i%2==1}--></tr><tr><!--{/if}-->
								<!--{eval $i++;}-->
								<!--{/loop}-->
								</tr></table>
							</td>
						</tr>
					</table>
				</div>
				<div class="box ptm mbm inbox">
					<button type="submit" name="add2submit_btn" value="true" class="ibt"><strong>{lang approval}</strong></button>
				</div>
                </div>
			</form>
			<script type="text/javascript">
				function succeedhandle_$_GET[handlekey](url, msg, values) {
					if(values['from'] == 'notice') {
						deleteQueryNotice(values['uid'], 'pendingFriend');
					} else {
						myfriend_post(values['uid']);
					}
				}
			</script>
		<!--{/if}-->
</div>
<!--{subtemplate common/footer}-->
