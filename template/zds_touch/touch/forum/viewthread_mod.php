<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{template common/header}-->
<!--{if empty($_GET['infloat'])}-->
<div class="pt">
         $navigation  
		  
    </div>
	<div id="ct" class="wp cl">
	
		
<!--{/if}-->

	
			<div class="dms_uxianginfo dsm_utotal" style="background: #fff;">
				<div class="dsm_tothd">
					<h2 class="title">{lang thread_moderations}</h2>
				</div>
				<ul class="dsm_totbd" style="padding: 0 9px;">
				<div style="width: 100%;">
					
					<table class="list" cellspacing="0" cellpadding="0" style="width: 100%;">
					<thead>
						<tr>
							<td>{lang thread_moderations_username}</td>
							<td>{lang time}</td>
							<td>{lang thread_moderations_action}</td>
							<td>{lang expire}</td>
						</tr>
					</thead>
					<!--{loop $loglist $log}-->
						<tr>
							<td><!--{if $log['uid']}--><a href="home.php?mod=space&uid=$log['uid']" target="_blank">$log[username]</a><!--{else}-->{lang thread_moderations_cron}<!--{/if}--></td>
							<td>$log[dateline]</td>
							<td $log[status]>{$modactioncode[$log['action']]}<!--{if $log['magicid']}-->($log[magicname])<!--{/if}-->
								<!--{if $log['action'] == 'REB'}-->{lang to} $log['reason']<!--{/if}-->
							</td>
							<td $log[status]><!--{if $log['expiration']}-->$log[expiration]<!--{elseif in_array($log['action'], array('STK', 'HLT', 'DIG', 'CLS', 'OPN'))}-->{lang expiration_unlimit}<!--{/if}--></td>
						</tr>
					<!--{/loop}-->
				</table>
					
				</div>
				</ul>
			</div>

	
	

<!--{if empty($_GET['infloat'])}-->
		
	</div>

<!--{/if}-->
<!--{template common/footer}-->