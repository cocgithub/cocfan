<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{template common/header}-->
<div class="tip">
<!--{if ($_GET['optgroup'] == 3 && $operation == 'delete') || ($_GET['optgroup'] == 4 && $operation == '') || ($_GET['optgroup'] == 1  && $operation == 'stick') || ($_GET['optgroup'] == 1  && $operation == 'digest') || ($_GET['optgroup'] == 2  && ($operation == 'type' || $operation == 'move'))}-->
    <form id="moderateform" method="post" autocomplete="off" action="forum.php?mod=topicadmin&action=moderate&optgroup=$optgroup&modsubmit=yes&mobile=2" >
        <input type="hidden" name="frommodcp" value="$frommodcp" />
        <input type="hidden" name="formhash" value="{FORMHASH}" />
        <input type="hidden" name="fid" value="$_G[fid]" />
        <input type="hidden" name="redirect" value="{echo dreferer()}" />
        <input type="hidden" name="reason" value="{lang topicadmin_mobile_mod}" />
        <!--{loop $threadlist $thread}-->
            <input type="hidden" name="moderate[]" value="$thread[tid]" />
        <!--{/loop}-->
		<!--{if $_GET['optgroup'] == 1 && $operation == 'stick'}-->
				<ul class="tpcl">
				<!--{if count($threadlist) > 1 || empty($defaultcheck[recommend])}-->
					<!--{if $_G['group']['allowstickthread']}-->
					<dt style="height:110px;">
						<p>{lang thread_stick}:&nbsp;</p>
						<input type="checkbox" name="operations[]" class="pc" onclick="if(this.checked) switchitemcp('itemcp_stick')" value="stick" $defaultcheck[stick] style="display:none;"/>
					<p>
									<div class="dopt">
										<select class="ps" name="sticklevel">
											<!--{if $_G['forum']['status'] != 3}-->
												<option value="0">{lang none}</option>
												<option value="1" $stickcheck[1]>$_G['setting']['threadsticky'][2]</option>
												<!--{if $_G['group']['allowstickthread'] >= 2}-->
													<option value="2" $stickcheck[2]>$_G['setting']['threadsticky'][1]</option>
													<!--{if $_G['group']['allowstickthread'] == 3}-->
														<option value="3" $stickcheck[3]>$_G['setting']['threadsticky'][0]</option>
													<!--{/if}-->
												<!--{/if}-->
											<!--{else}-->
												<option value="0">{lang no}&nbsp;</option>
												<option value="1" $stickcheck[1]>{lang yes}&nbsp;</option>
											<!--{/if}-->
										</select>
									</div>
							</p>
					
						<p class="hasd">
										<label for="expirationstick" class="labeltxt">{lang expire}</label>
										<input onclick="showcalendar(event, this, true)" type="text" autocomplete="off" id="expirationstick" name="expirationstick" class="px" value="$expirationstick" tabindex="1" />
										
									</p>
									 <p>{lang admin_close_expire_comment}</p>
							
					<!--{/if}-->
					<!--{/if}-->
				</ul>
				</dt>
				<dd><input type="submit" name="modsubmit" id="modsubmit"  value="{lang confirms}" class="formdialog button2"><a href="javascript:;" onclick="popup.close();">{lang cancel}</a></dd>
		<!--{elseif $_GET['optgroup'] == 1 && $operation == 'digest'}-->
				<ul class="tpcl">
				<!--{if count($threadlist) > 1 || empty($defaultcheck[recommend])}-->
					<!--{if $_G['group']['allowstickthread']}-->
					<dt style="height:110px;">
						<p>{lang admin_digest_add}:&nbsp;</p>
						<input type="checkbox" name="operations[]" class="pc" onclick="if(this.checked) switchitemcp('itemcp_digest')" value="digest" $defaultcheck[digest] style="display:none;"/>
					<p>
									<div class="dopt">
										<select name="digestlevel">
											<option value="0">{lang admin_digest_remove}</option>
											<option value="1" $digestcheck[1]>{lang thread_digest} 1</option>
											<!--{if $_G['group']['allowdigestthread'] >= 2}-->
												<option value="2" $digestcheck[2]>{lang thread_digest} 2</option>
												<!--{if $_G['group']['allowdigestthread'] == 3}-->
													<option value="3" $digestcheck[3]>{lang thread_digest} 3</option>
												<!--{/if}-->
											<!--{/if}-->
										</select>
									</div>
							</p>
					
						<p class="hasd">
										<label for="expirationdigest" class="labeltxt">{lang expire}</label>
										<input onclick="showcalendar(event, this, true)" type="text" name="expirationdigest" id="expirationdigest" class="px" autocomplete="off" value="$expirationdigest" tabindex="1" />
										
									</p>
									 <p>{lang admin_close_expire_comment}</p>
							
					<!--{/if}-->
					<!--{/if}-->
				</ul>
				</dt>
				<dd><input type="submit" name="modsubmit" id="modsubmit"  value="{lang confirms}" class="formdialog button2"><a href="javascript:;" onclick="popup.close();">{lang cancel}</a></dd>
		
		<!--{elseif $_GET['optgroup'] == 2 && ($operation == 'type' || $operation == 'move')}-->
				<dt style="height:110px;">
						
					<!--{if $operation != 'type'}-->
						<p>{lang thread_moved}:&nbsp;</p>
						<input type="hidden" name="operations[]" value="move" />
						<p class="mbn tahfx">
							{lang admin_target}: <select name="moveto" id="moveto" class="ps vm" onchange="ajaxget('forum.php?mod=ajax&action=getthreadtypes&fid=' + this.value, 'threadtypes');if(this.value) {$('moveext').style.display='';} else {$('moveext').style.display='';}">
								$forumselect
							</select>
						</p>
						
						<p><ul class="llst" id="moveext" style="display:none;margin:5px 0;">
							<li class="wide"><label><input type="radio" name="type" class="pr" value="normal" checked="checked" />{lang admin_move}</label></li>
							<li class="wide"><label><input type="radio" name="type" class="pr" value="redirect" />{lang admin_move_hold}</label></li>
						</ul></p>
					<!--{else}-->
					<p>{lang types}:&nbsp;</p>
						<!--{if $typeselect}-->
							<input type="hidden" name="operations[]" value="type" />
							<p>{lang types}: $typeselect</p>
						<!--{else}-->
							{lang admin_type_msg}<!--{eval $hiddensubmit = true;}-->
						<!--{/if}-->
					<!--{/if}-->
				</dt>
				<dd><input type="submit" name="modsubmit" id="modsubmit"  value="{lang confirms}" class="formdialog button2"><a href="javascript:;" onclick="popup.close();">{lang cancel}</a></dd>
        <!--{elseif $_GET['optgroup'] == 3}-->
            <!--{if $operation == 'delete'}-->
                <!--{if $_G['group']['allowdelpost']}-->
                    <input name="operations[]" type="hidden" value="delete"/>
                    <dt>{lang admin_delthread_confirm}</dt>
					<dd><input type="submit" class="formdialog button2" name="modsubmit" id="modsubmit"  value="{lang confirms}"><a href="javascript:;" onclick="popup.close();">{lang cancel}</a></dd>
                <!--{else}-->
                    <dt>{lang admin_delthread_nopermission}</dt>
                <!--{/if}-->
            <!--{/if}-->
        <!--{elseif $_GET['optgroup'] == 4}-->
				<dt style="height:110px;">
                <p>{lang expire}:&nbsp;</p>
                <p>
                    <input type="text" name="expirationclose" id="expirationclose" class="px" autocomplete="off" value="$expirationclose"  />
                </p>
                <p>{lang admin_close_expire_comment}</p>
                <p>
                    <label><input type="radio" name="operations[]" class="pr" value="open" $closecheck[0] />{lang admin_open}</label></p>
                <p>
                    <label><input type="radio" name="operations[]" class="pr" value="close" $closecheck[1] />{lang admin_close}</label></p>
				</dt>
				<dd><input type="submit" name="modsubmit" id="modsubmit"  value="{lang confirms}" class="formdialog button2"><a href="javascript:;" onclick="popup.close();">{lang cancel}</a></dd>
        <!--{/if}-->
    </form>
<!--{else}-->
    	<dt>{lang admin_threadtopicadmin_error}</dt>
		<dd><input type="button" onclick="popup.close();" value="{lang confirms}" class="button2"></dd>
<!--{/if}-->
</div>

<!--{template common/footer}-->
