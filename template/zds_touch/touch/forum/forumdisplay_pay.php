<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{template common/header}-->
<div class="wps cl">
	 <div class="pt">
         <a href="forum.php">{lang homepage}</a>$navigation 
    </div>


			<div class="f_c">
				<h3 class="xs2 xi2 mbm">{lang youneedpay} $paycredits {$_G['setting']['extcredits'][$_G['setting']['creditstransextra'][1]]['unit']}{$_G['setting']['extcredits'][$_G['setting']['creditstransextra'][1]]['title']} {lang onlyintoforum}</h3>
				<div class="o">
					<form method="post" autocomplete="off" action="forum.php?mod=forumdisplay&fid=$_G[fid]&action=paysubmit">
						<input type="hidden" name="formhash" value="{FORMHASH}" />
						<button class="pn pnc formdialog dsm_kuanibt" type="submit" name="loginsubmit" value="true" style="width: 90px;float: none;font-size: 15px;height: 28px;line-height: 0px;"><strong>{lang confirmyourpay}</strong></button>
						&nbsp;<button class="pn" type="button" onclick="history.go(-1)" style="width: 60px;float: none;font-size: 15px;height: 28px;line-height: 0px;"><strong>{lang cancel}</strong></button>
					</form>
				</div>
			</div>
	
</div>
<!--{template common/footer}-->
