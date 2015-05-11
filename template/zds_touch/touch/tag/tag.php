<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{template common/header}-->
<!--{if $type != 'countitem'}-->
<div class="wps cl">
	<div class="pt">
		<a href="forum.php">{lang homepage}</a> <em>&rsaquo;</em>
		<a href="misc.php?mod=tag">{lang tag}</a>
		
	</div>
</div>

	<div id="ct" class="wps cl">
		
		<div class="search">
			<form method="post" action="misc.php?mod=tag" class="pns">
				<input type="text" name="name" class="input" size="30"  style="width: 80%;" />&nbsp;
				<button type="submit" class="button2" style="width: 15%;"><em>{lang search}</em></button>
			</form>
			
		</div>
		<div class="zds_taglist">
				<!--{if $tagarray}-->
					<!--{loop $tagarray $tag}-->
						<a href="misc.php?mod=tag&id=$tag[tagid]" title="$tag[tagname]" target="_blank" class="xi2">$tag[tagname]</a>
					<!--{/loop}-->
				<!--{else}-->
					<p class="emp">{lang no_tag}</p>
				<!--{/if}-->
		</div>
	</div>

<!--{else}-->
$num
<!--{/if}-->
<!--{template common/footer}-->