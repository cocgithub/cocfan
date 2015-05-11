<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<div class="clear"></div>
<div class="dsm_talinav">
	<li class="dsm_taliz">任务名称</li>
	<li class="dsm_talizh">任务奖励</li>
	<li class="dsm_taliy">状态</li>
</div>
<div class="clear"></div>
<div class="ptm">
	<!--{if $tasklist}-->
		
			<!--{loop $tasklist $task}-->
			<div class="dsm_tali">
				<li class="dsm_taliz">
					<img src="$task[icon]" width="35" height="35" alt="$task[name]" class="z"/>
					
						<h3 class="xi2"><a href="home.php?mod=task&do=view&id=$task[taskid]" class="dsm_taskname">$task[name]</a> <span class="xs1 xg2 xw0">({lang task_applies}: <a href="home.php?mod=task&do=view&id=$task[taskid]#parter">$task[applicants]</a> )</span></h3>
						
						
					
				</li>
				<li class="dsm_talizh">
					
						<!--{if $task['reward'] == 'credit'}-->
							{lang credits} $_G['setting']['extcredits'][$task[prize]][title] $task[bonus] $_G['setting']['extcredits'][$task[prize]][unit]
						<!--{elseif $task['reward'] == 'magic'}-->
							{lang magics_title} $listdata[$task[prize]] $task[bonus] {lang magics_unit}
						<!--{elseif $task['reward'] == 'medal'}-->
							{lang medals} $listdata[$task[prize]] <!--{if $task['bonus']}-->{lang expire} $task[bonus] {lang days} <!--{/if}-->
						<!--{elseif $task['reward'] == 'invite'}-->
							{lang invite_code} $task[prize] {lang expire} $task[bonus] {lang days}
						<!--{elseif $task['reward'] == 'group'}-->
							{lang usergroup} $listdata[$task[prize]] <!--{if $task['bonus']}--> $task[bonus] {lang days} <!--{/if}-->
						<!--{else}-->
						&nbsp;
						<!--{/if}-->
						<!--{if $_GET['item'] == 'doing'}-->
						<div class="dsm_tapbg">
							<div class="dsm_tapbr" style="width: {if $task[csc]}$task[csc]%{else}8px{/if};"></div>
							<div class="xs0">{lang task_complete} <span id="csc_$task[taskid]">$task[csc]</span>%</div>
						</div>
						<!--{/if}-->
				</li>
				<li class="dsm_taliy">
					
						<!--{if $_GET['item'] == 'new'}-->
							<!--{if $task['noperm']}-->
								<a href="javascript:;" onclick="doane(event);showDialog('{lang task_group_nopermission}')" class="dsm_aniu" title="{lang task_group_nopermission}">用户组不符</a>
							<!--{elseif $task['appliesfull']}-->
								<a href="javascript:;" onclick="doane(event);showDialog('{lang task_applies_full}')" class="dsm_aniu" title="{lang task_applies_full}">人数已满</a>
							<!--{else}-->
								<a href="home.php?mod=task&do=apply&id=$task[taskid]" class="dsm_aniu dsm_tapply">接任务</a>
							<!--{/if}-->
						<!--{elseif $_GET['item'] == 'doing'}-->
							<p><a href="home.php?mod=task&do=draw&id=$task[taskid]" class="dsm_aniu {if $task[csc] >=100}dsm_tapply{else}&nbsp;{/if}">{if $task[csc] >=100}交任务{else}未完成{/if}</a></p>
						<!--{elseif $_GET['item'] == 'done'}-->
							<p style="white-space:nowrap">{lang task_complete_on} $task[dateline]
							<!--{if $task['period'] && $task[t]}--><br /><!--{if $task[allowapply]}--><a href="home.php?mod=task&do=apply&id=$task[taskid]">{lang task_applyagain_now}</a><!--{else}-->{$task[t]}{lang task_applyagain}<!--{/if}--><!--{/if}--></p>
						<!--{elseif $_GET['item'] == 'failed'}-->
							<p style="white-space:nowrap">{lang task_lose_on} $task[dateline]
							<!--{if $task['period'] && $task[t]}--><br /><!--{if $task[allowapply]}--><a href="home.php?mod=task&do=apply&id=$task[taskid]">{lang task_applyagain_now}</a><!--{else}-->{$task[t]}{lang task_reapply}<!--{/if}--><!--{/if}--></p>
						<!--{/if}-->
					
				</li>
			</div>
			<!--{/loop}-->
		
	<!--{else}-->
		<p class="emp"><!--{if $_GET['item'] == 'new'}-->{lang task_nonew}<!--{elseif $_GET['item'] == 'doing'}-->{lang task_nodoing}<!--{else}-->{lang data_nonexistence}<!--{/if}--></p>
	<!--{/if}-->
</div>