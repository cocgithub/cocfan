<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{subtemplate common/header}-->
<style>

.ipc {
font-size: 15px;
padding: 10px 10px 15px;
}
.ipcc {
min-height: 100px;
padding: 5px;
margin-bottom: 12px;
background: #fff;
border: 2px solid #eaeaea;
border-radius: 5px;
}
.ipcc textarea {
float: left;
height: 100px;
width: 100%;
font-size: 15px;
color: #777;
border: none;
background: none;
}
.inbox {
padding: 0;
overflow: hidden;
}
.ibt {
float: left;
height: 36px;
line-height: 36px;
font-size: 18px;
padding: 0 32px;
margin: 0 10px 0 0;
color: #fff;
background: #f60;
border: none;
color: #fff;
text-shadow: 0 0 1px #930;
width: 100%;
padding: 0;
text-align: center;
}
</style>
<!--{if $_GET['op'] == 'edit'}-->
  <div class="ct ctpd">   
    <div class="pt bb" style="padding: 10px 10px;"><a href="javascript:history.back();" onclick="javascript:history.back();" >{lang back}</a> <em> &gt; </em> {lang comment_edit_content}</div>  
    
    <div class="ipc">
	<form id="editcommentform_{$cid}" name="editcommentform_{$cid}" method="post" autocomplete="off" action="portal.php?mod=portalcp&ac=comment&op=edit&cid=$cid{if $_GET[modarticlecommentkey]}&modarticlecommentkey=$_GET[modarticlecommentkey]{/if}">
		<input type="hidden" name="referer" value="{echo dreferer()}" />
		<input type="hidden" name="editsubmit" value="true" />
		<!--{if $_G[inajax]}--><input type="hidden" name="handlekey" value="$_GET[handlekey]" /><!--{/if}-->
		<input type="hidden" name="formhash" value="{FORMHASH}" />	
            <div class="ipcc mtn">
			<textarea id="message_{$cid}" name="message" cols="70" onkeydown="ctrlEnter(event, 'editsubmit_btn');" rows="8" >$comment[message]</textarea>
            </div>	
		<div class="inbox mtb">
			<button type="submit" name="editsubmit_btn" id="editsubmit_btn" value="true" class="ibt ibtp">{lang submit}</button>
		</div>
	</form>
    </div>
    </div>

<!--{elseif $_GET['op'] == 'delete'}-->
    <div class="ct ctpd">
    <div class="pt bb"><a href="javascript:history.back();" onclick="javascript:history.back();" >{lang back}</a> <em> &gt; </em> {lang comment_delete}</div>    
	<div class="ipc">
    <form id="deletecommentform_{$cid}" name="deletecommentform_{$cid}" method="post" autocomplete="off" action="portal.php?mod=portalcp&ac=comment&op=delete&cid=$cid">
		<input type="hidden" name="referer" value="{echo dreferer()}" />
		<input type="hidden" name="deletesubmit" value="true" />
		<input type="hidden" name="formhash" value="{FORMHASH}" />
		<!--{if $_G[inajax]}--><input type="hidden" name="handlekey" value="$_GET[handlekey]" /><!--{/if}-->
		<div class="xw1 xg1 mbm mtm">{lang comment_delete_confirm}</div>
		<div class="inbox mtb">
			<button type="submit" name="deletesubmitbtn" value="true" class="ibt"><strong>{lang confirms}</strong></button>
		</div>
	</form>
    </div>
    </div>

<!--{/if}-->

<!--{subtemplate common/footer}-->