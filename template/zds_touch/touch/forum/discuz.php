<?php echo 'DS��� ��֧������  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{subtemplate touch/common/header}-->
<!--{if $_G['setting']['mobile']['mobilehotthread'] && $_GET['forumlist'] != 1}-->
	<!--{eval dheader('Location:forum.php?mod=guide&view=hot');exit;}-->
<!--{/if}-->
<!--{if $_GET['mod'] == '9gonge'}-->
	<!--{subtemplate touch/dsportal/jiugongge}--> 
<!--{else}-->
    <!--{subtemplate touch/dsportal/discuz}-->
<!--{/if}-->


<!--{subtemplate touch/common/footer}-->
