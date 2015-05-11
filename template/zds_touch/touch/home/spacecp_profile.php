<?php echo 'DS设计 请支持正版  http://addon.discuz.com/?@5404.developer';exit;?>
<!--{template common/header}-->
<div class="wps">
<div class="ct">
	<div class="pt">
		<a href="./" class="nvhm" title="$_G[setting][bbname]">{lang homepage}</a> <em>&rsaquo;</em>
		<!--<a href="home.php?mod=spacecp">{lang setup}</a> <em>&rsaquo;</em>-->
		<!--{template home/spacecp_header_name}-->
	</div>
	</div>
</div>
<!--{template home/spacecp_footer}-->







<div id="ct" class="ct2_a wp cl">
	<div class="mn">
		<div class="bm bw0">
			







	<!--{if $validate}-->
		<p class="tbmu mbm">{lang validator_comment}</p>
		<form action="member.php?mod=regverify" method="post" autocomplete="off" style="padding-left: 5px;">
		<input type="hidden" value="{FORMHASH}" name="formhash" />
		<table summary="{lang memcp_profile}" cellspacing="0" cellpadding="0" class="tfm" style="width:100%">
		<tr>
			<th>{lang validator_remark}</th>
			<td>$validate[remark]</td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th>{lang validator_message}</th>
			<td><input type="text" class="px" name="regmessagenew" value="" /></td>
			<td>&nbsp;</td>
		</tr>
		<tr>
			<th>&nbsp;</th>
			<td colspan="2">
				<button type="submit" name="verifysubmit" value="true" class="pn pnc" /><strong>{lang validator_submit}</strong></button>
			</td>
		</tr>
		</table>
		</div></div>
		
		<!--{template home/spacecp_footer}-->
	
	<!--{else}-->
		<!--{if $operation == 'password'}-->
			<script type="text/javascript" src="{$_G[setting][jspath]}register.js?{VERHASH}"></script>
			<p class="bbda pbm mbm">
				<!--{if !$_G['member']['freeze']}-->
					<!--{if !$_G['setting']['connect']['allow'] || !$conisregister}-->{lang old_password_comment}<!--{else}-->{lang connect_config_newpassword_comment}<!--{/if}-->
				<!--{elseif $_G['member']['freeze'] == 1}-->
					<strong class="xi1">{lang freeze_pw_tips}</strong>
				<!--{elseif $_G['member']['freeze'] == 2}-->
					<strong class="xi1">{lang freeze_email_tips}</strong>
				<!--{/if}-->
			</p>
			<form action="home.php?mod=spacecp&ac=profile" method="post" autocomplete="off" style="padding-left: 5px;">
				<input type="hidden" value="{FORMHASH}" name="formhash" />
				<table summary="{lang memcp_profile}" cellspacing="0" cellpadding="0" class="tfm dsm_homeszkuan dsm_homemima" style="width: 100%;">
					<!--{if !$_G['setting']['connect']['allow'] || !$conisregister}-->
						<tr>
							<th><span class="rq" title="{lang required}">*</span>{lang old_password}</th>
							<td><input type="password" name="oldpassword" id="oldpassword" class="px" /></td>
						</tr>
					<!--{/if}-->
					<tr>
						<th>{lang new_password}</th>
						<td>
							<input type="password" name="newpassword" id="newpassword" class="px" placeholder="若不需更改密码，此处留空" />
							
						</td>
					</tr>
					<tr>
						<th>{lang new_password_confirm}</th>
						<td>
							<input type="password" name="newpassword2" id="newpassword2"class="px" placeholder="若不需更改密码，此处留空" />
							
						</td>
					</tr>
					<tr id="contact"{if $_GET[from] == 'contact'} style="background-color: {$_G['style']['specialbg']};"{/if}>
						<th>{lang email}</th>
						<td>
							<input type="text" name="emailnew" id="emailnew" value="$space[email]" class="px" />
							<p class="d">
								<!--{if empty($space['newemail'])}-->
									{lang email_been_active}
								<!--{else}-->
									$acitvemessage
								<!--{/if}-->
							</p>
							<!--{if $_G['setting']['regverify'] == 1 && (($_G['group']['grouptype'] == 'member' && $_G['adminid'] == 0) || $_G['groupid'] == 8) || $_G['member']['freeze']}--><p class="d">{lang memcp_profile_email_comment}</p><!--{/if}-->
						</td>
					</tr>
					
					<!--{if $_G['member']['freeze'] == 2}-->
					<tr>
						<th>{lang freeze_reason}</th>
						<td>
							<textarea rows="3" cols="20" name="freezereson" class="pt">$space[freezereson]</textarea>
							<p class="d" id="chk_newpassword2">{lang freeze_reason_comment}</p>
						</td>
					</tr>
					<!--{/if}-->

					<tr>
						<th>{lang security_question}</th>
						<td>
							<select name="questionidnew" id="questionidnew">
								<option value="" selected>{lang memcp_profile_security_keep}</option>
								<option value="0">{lang security_question_0}</option>
								<option value="1">{lang security_question_1}</option>
								<option value="2">{lang security_question_2}</option>
								<option value="3">{lang security_question_3}</option>
								<option value="4">{lang security_question_4}</option>
								<option value="5">{lang security_question_5}</option>
								<option value="6">{lang security_question_6}</option>
								<option value="7">{lang security_question_7}</option>
							</select>
							
						</td>
					</tr>

					<tr>
						<th>{lang security_answer}</th>
						<td>
							<input type="text" name="answernew" id="answernew" class="px" placeholder="有设新提问,请在此填写答案"/>
							
						</td>
					</tr>					
					<!--{if $secqaacheck || $seccodecheck}-->
					</table>
						<!--{eval $sectpl = '<table cellspacing="0" cellpadding="0" class="tfm"><tr><th><sec></th><td><sec><p class="d"><sec></p></td></tr></table>';}-->
						<!--{subtemplate common/seccheck}-->
					<table summary="{lang memcp_profile}" cellspacing="0" cellpadding="0" class="tfm">
					<!--{/if}-->					
					<tr>
						<th>&nbsp;</th>
						<td><button type="submit" name="pwdsubmit" value="true" class="formdialog dsm_kuanibt pn pnc" style="padding: 0 32px;" /><strong>{lang save}</strong></button></td>
					</tr>
				</table>
				<input type="hidden" name="passwordsubmit" value="true" />
			</form>
			<script type="text/javascript">
				var strongpw = new Array();
				<!--{if $_G['setting']['strongpw']}-->
					<!--{loop $_G['setting']['strongpw'] $key $val}-->
					strongpw[$key] = $val;
					<!--{/loop}-->
				<!--{/if}-->
				var pwlength = <!--{if $_G['setting']['pwlength']}-->$_G['setting']['pwlength']<!--{else}-->0<!--{/if}-->;
				checkPwdComplexity($('newpassword'), $('newpassword2'), true);
			</script>
		<!--{else}-->
		暂不支持手机修改
		<!--{/if}-->
		</div>
	</div>
	<div class="appl">
		
	</div>
	<!--{/if}-->
</div>


<!--{template common/footer}-->
