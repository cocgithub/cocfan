<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{subtemplate common/header}-->

<div class="ct ctpd">
<div class="pt bb"><a href="javascript:history.back();" onclick="javascript:history.back();" >{lang back}</a> <em> &gt; </em> {lang article_delete}</div> 
<div class="ipc">
<!--{if $op == 'delete'}-->

<form method="post" autocomplete="off" action="portal.php?mod=portalcp&ac=article&op=delete&aid=$_GET[aid]">

		<div class="xw1 xg1 mbm mtn">
        <!--{if $_G['group']['allowpostarticlemod'] && $article['status'] == 1}-->
		{lang article_delete_sure}
		<input type="hidden" name="optype" value="0" class="pc" />
		<!--{else}-->
		<label class="lb"><input type="radio" name="optype" value="0" class="pc" /> {lang article_delete_direct}</label>
		<label class="lb"><input type="radio" name="optype" value="1" class="pc" checked="checked" /> {lang article_delete_recyclebin}</label>
		<!--{/if}-->
        </div>

	<div class="inbox mtb">
		<button type="submit" name="btnsubmit" value="true" class="ibt"><strong>{lang confirms}</strong></button>
	</div>
	<input type="hidden" name="aid" value="$_GET[aid]" />
	<input type="hidden" name="referer" value="{echo dreferer()}" />
	<input type="hidden" name="deletesubmit" value="true" />
	<input type="hidden" name="formhash" value="{FORMHASH}" />
</form>
<!--{else}-->
<div class="vfnxt"><a href="javascript:history.back();" onclick="javascript:history.back();" >{lang goback}</a></div>
<!--{/if}-->

</div>
</div>

<!--{subtemplate common/footer}-->