<?php echo 'DS��� ��֧������  http://addon.discuz.com/?@5404.developer';exit;?>
            <!--{if $data[commentnum]}-->
            <div class="titls bb"><a href="$common_url" class="xi2">�鿴����</a><span class="y">����{$data[commentnum]}��{lang comment}</span></div>
            <!--{/if}--> 

<div class="ct ctpd"> 

<div class="ipc">		
		<form id="cform" name="cform" action="$form_url" method="post" autocomplete="off">

				<div class="dsm_kuanipcc mtn">
				<textarea name="message" rows="3" id="message" onkeydown="ctrlEnter(event, 'commentsubmit_btn');"></textarea>
				</div>

			<!--{if checkperm('seccode') && ($secqaacheck || $seccodecheck)}-->
			<!--{subtemplate common/seccheck}-->
			<!--{/if}-->
			<!--{if !empty($topicid) }-->
				<input type="hidden" name="referer" value="portal.php?mod=topic&topicid=$topicid#comment" />
				<input type="hidden" name="topicid" value="$topicid">
			<!--{else}-->
				<input type="hidden" name="portal_referer" value="portal.php?mod=view&aid=$aid#comment">
				<input type="hidden" name="referer" value="portal.php?mod=view&aid=$aid#comment" />
				<input type="hidden" name="id" value="$data[id]" />
				<input type="hidden" name="idtype" value="$data[idtype]" />
				<input type="hidden" name="aid" value="$aid">
			<!--{/if}-->
			<input type="hidden" name="formhash" value="{FORMHASH}">
			<input type="hidden" name="replysubmit" value="true">
			<input type="hidden" name="commentsubmit" value="true" />
			<div class="inbox"><button type="submit" name="commentsubmit_btn" id="commentsubmit_btn" value="true" class="dsm_kuanibt ibtp">{lang comment}</button></div>
		</form>	
</div>


</div>