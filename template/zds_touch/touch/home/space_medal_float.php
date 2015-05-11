<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{template common/header}-->
<!--{if empty($_GET['infloat'])}-->
<div class="wps cl"> 

	<div class="pt"><a href="./" class="nvhm" title="{lang homepage}">{lang homepage}</a> <em>&rsaquo;</em><a href="home.php?mod=medal">{lang medals}</a><em>&rsaquo;</em> <!--{if $medal['price']}-->{lang space_medal_buy}{lang medals}<!--{else}-->{lang space_medal_apply}<!--{/if}--></div>

<div id="ct" class="wp cl">
	<div class="mn">
		<div class="bm bw0">
<!--{/if}-->

<form id="medalform" method="post" autocomplete="off" action="home.php?mod=medal&action=apply&medalsubmit=yes">
	<div class="f_c">
		
		<input type="hidden" name="formhash" value="{FORMHASH}" />
		<input type="hidden" name="medalid" value="$medal[medalid]" />
		<input type="hidden" name="operation" value="" />
		<!--{if !empty($_GET['infloat'])}--><input type="hidden" name="handlekey" value="$_GET['handlekey']" /><!--{/if}-->

		<div class="c">
			<dl class="xld cl">
				<dd class="m">
					<div class="mg_img hm">
						<img src="{STATICURL}image/common/$medal[image]" alt="$medal[name]" style="margin: 20px 0 0 0" />
					</div>
				</dd>
				<dt class="z">
					<p class="xw1">$medal[name]</p>
					<p class="pbm mbm bbda xw0">$medal[description]</p>
					<p>
						<!--{if $medal[expiration]}-->
							{lang expire} $medal[expiration] {lang days}<br />
						<!--{/if}-->
						<!--{if $medal[permission]}-->
							$medal[permission]<br />
						<!--{/if}-->
						<!--{if $medal[type] == 0}-->
							{lang medals_type_0}
						<!--{elseif $medal[type] == 1}-->
							<!--{if $medal['price']}-->
								<!--{if {$_G['setting']['extcredits'][$medal[credit]][unit]}}-->
									{$_G['setting']['extcredits'][$medal[credit]][title]} <strong class="xi1 xw1 xs2">$medal[price]</strong> {$_G['setting']['extcredits'][$medal[credit]][unit]}
								<!--{else}-->
									<strong class="xi1 xw1 xs2">$medal[price]</strong> {$_G['setting']['extcredits'][$medal[credit]][title]}
								<!--{/if}-->
							<!--{else}-->
								{lang medals_type_1}
							<!--{/if}-->
						<!--{elseif $medal[type] == 2}-->
							{lang medals_type_2}
						<!--{/if}-->
					</p>
				</dt>
			</dl>
		</div>
	</div>
	<div class="o pns">
		<!--{if $medal[type] && $_G['uid']}-->
			<button class="pn pnc vm" type="submit" value="true" name="medalsubmit"><span>
				<span>
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
			</button>
		<!--{/if}-->
	</div>
</form>

<!--{if empty($_GET['infloat'])}-->
		</div>
	</div>
</div>
</div>
<!--{/if}-->

<!--{template common/footer}-->