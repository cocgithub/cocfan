<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{subtemplate common/header}-->
<div class="ct ctpd wps dsm_bgbai"> 
<div class="pt bb"><a href="javascript:history.back();" onclick="javascript:history.back();" >{lang back}</a> <em> &gt; </em> {lang pay_attachment}</div>
<div class="dsm_vaipc">
    <form id="attachpayform" method="post" autocomplete="off" action="forum.php?mod=misc&action=attachpay&tid={$_G[tid]}">
            <input type="hidden" name="formhash" value="{FORMHASH}" />
            <input type="hidden" name="referer" value="{echo dreferer()}" />
            <input type="hidden" name="aid" value="$aid" />
            
                <table cellspacing="5" cellpadding="5" class="p_attc">
                    <tr>
                        <td>{lang author}</td>
                        <td><a href="home.php?mod=space&uid=$attach[uid]&do=profile" class="xi2">$attach[author]</a></td>                      
                    </tr>
                    <tr>
                        <td>{lang attachment}</td>
                        <td><div style="overflow:hidden">$attach[filename] <!--{if $attach['description']}-->($attach[description])<!--{/if}--></div></td>
                    </tr>
                    <tr>
                        <td>{lang price}({$_G['setting']['extcredits'][$_G['setting']['creditstransextra'][1]][title]})</td>
                        <td>$attach[price] {$_G['setting']['extcredits'][$_G['setting']['creditstransextra'][1]][unit]}</td>
                    </tr>
                    <!--{if $status != 1}-->
                    <tr>
                        <td>{lang pay_author_income}({$_G['setting']['extcredits'][$_G['setting']['creditstransextra'][1]][title]})</td>
                        <td>$attach[netprice] {$_G['setting']['extcredits'][$_G['setting']['creditstransextra'][1]][unit]}</td>
                    </tr>
                    <tr>
                        <td>{lang pay_balance}({$_G['setting']['extcredits'][$_G['setting']['creditstransextra'][1]][title]})</td>
                        <td>$balance {$_G['setting']['extcredits'][$_G['setting']['creditstransextra'][1]][unit]}</td>
                    </tr>
                    <!--{/if}-->
                    <!--{if $status == 1}-->
                    <tr>
                        <td>&nbsp;</td>
                        <td>{lang status_insufficient}</td>
                    </tr>
                    <!--{elseif $status == 2}-->
                    <tr>
                        <td>&nbsp;</td>
                        <td>{lang status_download}, <a href="forum.php?mod=attachment&aid=$aidencode" target="_blank">{lang download}</a></td>
                    </tr>
                    <!--{/if}-->
                </table>
            
           
                <!--{if $status != 1}-->
                    <div class="ipcl xg1 pbm"><label><input name="buyall" type="checkbox" value="yes" /> {lang buy_all_attch}</label></div>
                    <div class="inbox mtm"><input type="submit" name="paysubmit" id="paysubmit" value="<!--{if $status == 0}-->{lang pay_attachment}<!--{else}-->{lang free_buy}<!--{/if}-->" class="ibt dsm_aniu" /></div>
                <!--{/if}-->
           
    </form>
</div>
</div>
<!--{subtemplate common/footer}-->