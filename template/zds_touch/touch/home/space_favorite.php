<?php echo 'DS��� ��֧������  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{subtemplate common/header}-->
<div class="wps">
<div class="pt"><a href="forum.php">��ҳ</a> <em> &gt; </em> �ҵ��ղ�</div> 


<ul class="ttp bm cl">
<li {if $_GET[type] == 'forum'} class="a"{/if}>
<a href="home.php?mod=space&uid={$_G[uid]}&do=favorite&view=me&type=forum">����ղ�</a></li><li {if $_GET[type] == 'thread'} class="a"{/if}><a href="home.php?mod=space&uid={$_G[uid]}&do=favorite&view=me&type=thread">�����ղ�</a></li>
</ul>

<div class="threadlist displaylist "> 
    <!--{if $list}-->

        <!--{loop $list $k $value}-->
            <li class="bm_c">
                <a href="$value[url]" class="xs1">$value[title]</a>
				
				<p class="cl">
<span class="num"><a id="a_delete_$k" href="home.php?mod=spacecp&ac=favorite&op=delete&favid=$k&type={$_GET[type]}" style="font-size: 12px;line-height: 15px;" class="dialog">({lang favorite_delete})</a></span>
<span class="by"><!--{if $value[description]}-->
				$value[description]
                <!--{/if}--> <!--{date($value[dateline], 'u')}--> </span>

</p>
                
            </li>
        <!--{/loop}-->
    <!--{else}-->
    	<div class="bm_c ptb pbb">{lang no_favorite_yet}</div>
    <!--{/if}-->
</div>

<!--{if $multi}--><div class="pgbox">$multi</div><!--{/if}-->
</div>

<!--{subtemplate common/footer}-->
