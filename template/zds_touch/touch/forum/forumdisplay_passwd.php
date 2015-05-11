<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{template common/header}-->
<div class="wps cl">
	 <div class="pt">
         <a href="forum.php">{lang homepage}</a>$navigation 
    </div>


			<div class="f_c">
				<h3 class="xs2 xi2 mbm">{lang forum_password_require}</h3>
				<div class="o" style="text-align: center;height: 60px;">
					<form method="post" autocomplete="off" action="forum.php?mod=forumdisplay&fid=$_G[fid]&action=pwverify">
						<input type="hidden" name="formhash" value="{FORMHASH}" />
						<input type="password" name="pw" class="px vm" size="25" />
						&nbsp;<button class="pn pnc formdialog dsm_kuanibt " type="submit" name="loginsubmit" value="true" style="width: 45px;float: none;font-size: 15px;height: 28px;line-height: 0px;"><strong>{lang submit}</strong></button>
					</form>
				</div>
			</div>
		
</div>
<!--{template common/footer}-->