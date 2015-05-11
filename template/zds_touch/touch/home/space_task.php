<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{template common/header}-->
<div class="wps cl"> 
<div class="pt"><a href="forum.php">{lang homepage}</a> <em> &gt; </em> <a href="home.php?mod=task">{lang task}</a></div>
<ul class="ttp bm cl">
	<li $actives[new] >
		<a href="home.php?mod=task&item=new">{lang task_new}</a>
	</li>
	<li $actives[doing] >
		<a href="home.php?mod=task&item=doing" >任务ing</a>
	</li>
	<li $actives[done]>
		<a href="home.php?mod=task&item=done" >已交任务</a>
	</li>
	<li $actives[failed]>
		<a href="home.php?mod=task&item=failed" >失败任务</a>
	</li>
	</ul>
<div class="clear"></div>



<div id="ct" class="ct2_a wp cl">
	<div class="mn">
		<div class="bm bw0">
		<!--{if empty($do)}-->
			<!--{subtemplate home/space_task_list}-->
		<!--{elseif $do == 'view'}-->
			<!--{subtemplate home/space_task_detail}-->
		<!--{/if}-->
		</div>
	</div>
	
</div>
</div>
<!--{template common/footer}-->