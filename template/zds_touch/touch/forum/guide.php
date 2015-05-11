<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{template common/header}-->
<div class="wps cl">

<!-- main threadlist start -->
<div class="threadlists" style="padding-top:20px">
<ul id="thread_types" class="ttp bm cl">
				<li $currentview['hot']><a href="forum.php?mod=guide&view=hot">热门</a></li>
				<li $currentview['new']><a href="forum.php?mod=guide&view=new">{lang guide_new}</a></li>
				<li $currentview['newthread']><a href="forum.php?mod=guide&view=newthread">{lang guide_newthread}</a></li>
				<li $currentview['my']><a id="filter_special" href="forum.php?mod=guide&view=my" onmouseover="showMenu(this.id)">我的帖子</a></li>
				<!--{hook/guide_nav_extra}-->
			</ul>
	<!--{loop $data $key $list}-->
		<!--{subtemplate forum/guide_list_row}-->
	<!--{/loop}-->

</div>
<!-- main threadlist end -->

$multipage


</div>
<!--{template common/footer}-->
