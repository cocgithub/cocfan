<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{template common/header}-->
<link rel="stylesheet" href="$_G['style']['tpldir']/touch/images/dsm_pd.css" type="text/css">
<div class="wps cl"> 
<div class="pt"><a href="./">{lang homepage}</a> <em> &gt; </em> <a href="forum.php?mod=announcement">{lang announcement}</a></div>
<!--{loop $announcelist $ann}-->
<div class="clt">

                    <h1>$ann[subject]</h1>                    
<p class="mess">
$ann[message]</p>                    
<div class="sp xg1">                 
<a href="home.php?mod=space&username=$ann[authorenc]">$ann[author]</a><span class="pipe">-</span>($ann[starttime])</div>                    
                    
                    </div>

<!--{/loop}-->



</div>
<!--{template common/footer}-->