<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{template common/header}-->


<div class="wps">
			
	<div class="pt">
<!--{if $_GET['op'] == 'edit'}-->
{lang edit}留言内容
<!--{elseif $_GET['op'] == 'delete'}-->
	{lang delete_reply}
<!--{elseif $_GET['op'] == 'reply'}-->
	{lang reply} {$comment[author]} 的 留言
<!--{/if}-->
</div>

<!--{if $_GET['op'] == 'edit'}-->
	
	<form id="editcommentform_{$cid}" name="editcommentform_{$cid}" method="post" autocomplete="off" action="home.php?mod=spacecp&ac=comment&op=edit&cid=$cid{if $_GET[modcommentkey]}&modcommentkey=$_GET[modcommentkey]{/if}" {if $_G[inajax]} onsubmit="ajaxpost(this.id, 'return_$_GET[handlekey]');"{/if}>
		<input type="hidden" name="referer" value="{echo dreferer()}" />
		<input type="hidden" name="editsubmit" value="true" />
		<!--{if $_G[inajax]}--><input type="hidden" name="handlekey" value="$_GET[handlekey]" /><!--{/if}-->
		<input type="hidden" name="formhash" value="{FORMHASH}" />
		<div class="dsm_kuanipcc">
			
			<textarea id="message_{$cid}" name="message" cols="70" onkeydown="ctrlEnter(event, 'editsubmit_btn');" rows="5" >$comment[message]</textarea>
		</div>
		<p class="o pns">
			<button type="submit" name="editsubmit_btn" id="editsubmit_btn" value="true" class="formdialog dsm_kuanibt"><strong>{lang submit}</strong></button>
		</p>
	</form>
	<script type="text/javascript">
		function succeedhandle_$_GET['handlekey'] (url, message, values) {
			comment_edit(values['cid']);
		}
	</script>

<!--{elseif $_GET['op'] == 'delete'}-->
	
	<form id="deletecommentform_{$cid}" name="deletecommentform_{$cid}" method="post" autocomplete="off" action="home.php?mod=spacecp&ac=comment&op=delete&cid=$cid" {if $_G[inajax]}onsubmit="ajaxpost(this.id, 'return_$_GET[handlekey]');"{/if}>
		<input type="hidden" name="referer" value="{echo dreferer()}" />
		<input type="hidden" name="deletesubmit" value="true" />
		<input type="hidden" name="formhash" value="{FORMHASH}" />
		<!--{if $_G[inajax]}--><input type="hidden" name="handlekey" value="$_GET[handlekey]" /><!--{/if}-->
		<div class="c">{lang delete_reply_message}</div>
		<p class="o pns">
			<button type="submit" name="deletesubmitbtn" value="true" class="formdialog pn pnc"><strong>{lang determine}</strong></button>
		</p>
	</form>
	<script type="text/javascript">
		function succeedhandle_$_GET['handlekey'] (url, message, values) {
			comment_delete(values['cid']);
		}
	</script>
<!--{elseif $_GET['op'] == 'reply'}-->
	
	<form id="replycommentform_{$comment[cid]}" name="replycommentform_{$comment[cid]}" method="post" autocomplete="off" action="home.php?mod=spacecp&ac=comment" {if $_G[inajax]} onsubmit="ajaxpost('replycommentform_{$comment[cid]}', 'return_$_GET[handlekey]');"{/if}>
		<input type="hidden" name="referer" value="{echo dreferer()}" />
		<input type="hidden" name="id" value="$comment[id]" />
		<input type="hidden" name="idtype" value="$comment[idtype]" />
		<input type="hidden" name="cid" value="$comment[cid]" />
		<input type="hidden" name="commentsubmit" value="true" />
		<!--{if $_G[inajax]}--><input type="hidden" name="handlekey" value="$_GET[handlekey]" /><!--{/if}-->
		<input type="hidden" name="formhash" value="{FORMHASH}" />
		<div id="reply_msg_{$comment[cid]}">
			<div class="dsm_kuanipcc">
				
				
				<textarea id="message_pop_{$comment[cid]}" name="message" onkeydown="ctrlEnter(event, 'commentsubmit_btn');" rows="5"  ></textarea>
				
			</div>
			<p class="o pns">
				<button type="submit" name="commentsubmit_btn" id="commentsubmit_btn" value="true" class="formdialog dsm_kuanibt"><strong>{lang reply}{$comment[author]}</strong></button>
			</p>
		</div>
	</form>
	<script type="text/javascript">
		function succeedhandle_$_GET['handlekey'] (url, message, values) {
			<!--{if $comment['idtype']!='uid'}-->
				<!--{if $_GET['feedid']}-->
					feedcomment_add(values['cid'], $_GET['feedid']);
				<!--{else}-->
					comment_add(values['cid']);
				<!--{/if}-->
			<!--{/if}-->
		}
	</script>

<!--{/if}-->


	
</div>
<div class="clear"></div>
<!--{template common/footer}-->
