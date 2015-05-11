<?PHP
/*  nds_up_ques  v3.2
 *  Plugin FOR Discuz! X 
 *	WWW.NWDS.CN | NDS.西域数码工作室 
 *  Plugin update 20130812 BY singcee
 */
if (! defined ( 'IN_DISCUZ' )) {
	exit ( 'Access Denied' );
}
include_once DISCUZ_ROOT . './source/plugin/nds_up_ques/nds_function.php';
$count = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_topic' ) . " WHERE `authorid`='$_G[uid]' AND `topicid`='$topicid' LIMIT 1" ), 0 );
if ($count)
	$ar = 1;
if ($_G ['adminid'] != 1 && ! $ar)
	showmessage ( 'nds_up_ques:noopa', '', '', array ('alert' => 'error' ) );

if (! submitcheck ( 'editsubmit' )) {
	$questopic = DB::fetch_first ( "SELECT * FROM " . DB::table ( 'ques_topic' ) . " WHERE`topicid`='$topicid'" );
	if ($ndsopcheck && $questopic [opcheck] && $_G ['adminid'] != 1) {
		showmessage ( 'nds_up_ques:notedit', '', '', array ('alert' => 'error' ) );
	}
	$questopic ['exp'] = $questopic ['exp'] == 1 ? 0 : dgmdate ( $questopic ['exp'] );
	$sysmode = $questopic ['ques_mode'];
	$query1 = DB::query ( " SELECT * FROM " . DB::table ( 'ques_option' ) . " WHERE `topicid`='$topicid' ORDER by `order`" );
	$order = 1;
	WHILE ( $options = DB::fetch ( $query1 ) ) {
		$optionlist .= '<tr><td class="d2td" height="118" colspan="2">
				<label><b>' . lang ( 'plugin/nds_up_ques', 'order' ) . '</b>&nbsp;</label><input type="text" name="order[' . $options ['oid'] . ']" size="1" value="' . $order ++ . '">&nbsp;&nbsp;
				<label><b>' . lang ( 'plugin/nds_up_ques', 'nds_tmtypeno' ) . '</b>&nbsp;</label><input type="text" name="tmclass[' . $options ['oid'] . ']" size="1" value="' . $options ['tmclass'] . '">
				<label><b>' . lang ( 'plugin/nds_up_ques', 'title' ) . '</b>&nbsp;</label><input type="text" name="title[' . $options ['oid'] . ']" size="88" value="' . $options ['title'] . '">&nbsp;&nbsp;
				' . lang ( 'plugin/nds_up_ques', 'del' ) . '<input type="checkbox" class="checkbox" name="delete[]" id ="delte' . $order . '"  value="' . $options ['oid'] . '">
				<div class="ndsdiv">
				<select name="least[' . $options ['oid'] . ']]">
					<option value="0" ' . ($options ['least'] == 0 ? 'selected' : 'selectd') . '>' . lang ( 'plugin/nds_up_ques', 'least0' ) . '</option>
					<option value="1" ' . ($options ['least'] == 1 ? 'selected' : 'selectd') . '>' . lang ( 'plugin/nds_up_ques', 'least1' ) . '</option>
				 </select>&nbsp;&nbsp;						
				<label><b>' . lang ( 'plugin/nds_up_ques', 'types' ) . '</b>&nbsp;</label>		
				 <select name="types[' . $options ['oid'] . ']" onchange="Showmax(' . $options ['oid'] . ',this.options[this.options.selectedIndex].value,' . $sysmode . ')">
					<option value="1" ' . ($options ['type'] == 1 ? 'selected' : 'selectd') . '>' . lang ( 'plugin/nds_up_ques', 'type1' ) . '</option>
					<option value="2" ' . ($options ['type'] == 2 ? 'selected' : '') . '>' . lang ( 'plugin/nds_up_ques', 'type2' ) . '</option>
					<option value="3" ' . ($options ['type'] == 3 ? 'selected' : '') . '>' . lang ( 'plugin/nds_up_ques', 'type3' ) . '</option>
					<option value="4" ' . ($options ['type'] == 4 ? 'selected' : '') . '>' . lang ( 'plugin/nds_up_ques', 'type4' ) . '</option>
					<option value="5" ' . ($options ['type'] == 5 ? 'selected' : '') . '>' . lang ( 'plugin/nds_up_ques', 'type5' ) . '</option>
					<option value="6" ' . ($options ['type'] == 6 ? 'selected' : '') . '>' . lang ( 'plugin/nds_up_ques', 'type6' ) . '</option>
					<option value="7" ' . ($options ['type'] == 7 ? 'selected' : '') . '>' . lang ( 'plugin/nds_up_ques', 'type7' ) . '</option>
					' . ($sysmode == 1 ? '<option value="8" ' . ($options ['type'] == 8 ? 'selected' : '') . '>' . lang ( 'plugin/nds_up_ques', 'type8' ) . '</option>
                    <option value="9" ' . ($options ['type'] == 9 ? 'selected' : '') . '>' . lang ( 'plugin/nds_up_ques', 'type9' ) . '</option></option>
                    <option value="10" ' . ($options ['type'] == 10 ? 'selected' : '') . '>' . lang ( 'plugin/nds_up_ques', 'type10' ) . '</option>' : '') . '
				 </select>
				 <span id="chmaxsp' . $options ['oid'] . '"  name="chmaxsp' . $options ['oid'] . '"' . ((! ($options ['type'] == 4 || $options ['type'] == 5 || $options ['type'] == 9)) ? 'style="display:none"' : '') . '>
          <label  class="ndslable" ><b>' . lang ( 'plugin/nds_up_ques', 'nds_chmin' ) . '</b></label><input name="chmin[' . $options ['oid'] . ']" type="text" value="' . $options ['chmin'] . '" size="2" maxlength="2">
		<label class="ndslable"><b>' . lang ( 'plugin/nds_up_ques', 'nds_chmax' ) . '</b></label><input name="chmax[' . $options ['oid'] . ']" type="text" value="' . $options ['chmax'] . '" size="2" maxlength="2">       		     </span>
   <span  id="chmaxsph"  name="chmaxsph"> 
    </span>    
        
      <span id ="ohtinputsp' . $options ['oid'] . '" name="ohtinputsp' . $options ['oid'] . '"' . (($options ['type'] > 5 || $options ['type'] < 2) ? 'style="display:none"' : '') . '>
         <label class="ndslable"><b>' . lang ( 'plugin/nds_up_ques', 'nds_endnote' ) . '</b></label>
                    <select id= "types" name="othinput[' . $options ['oid'] . ']">
<option value="0" ' . ($options ['othinput'] == 0 ? 'selected' : '') . '>' . lang ( 'plugin/nds_up_ques', 'nds_endnotenull' ) . '</option>
<option value="1" ' . ($options ['othinput'] == 1 ? 'selected' : '') . '>' . lang ( 'plugin/nds_up_ques', 'nds_endnotept' ) . '</option>
' . ($sysmode == 1 ? '
<option value="2" ' . ($options ['othinput'] == 2 ? 'selected' : '') . '>' . lang ( 'plugin/nds_up_ques', 'nds_endnotetk' ) . '</option>
<option value="3" ' . ($options ['othinput'] == 3 ? 'selected' : '') . '>' . lang ( 'plugin/nds_up_ques', 'nds_endnotepttk' ) . '</option>' : '') . '
		 </select>
      </span>
      <span id ="textsizesp' . $options ['oid'] . '" name="textsizesp' . $options ['oid'] . '"' . ((! ($options ['type'] == 6 || $options ['type'] == 7)) ? 'style="display:none"' : '') . '>
       <label class="ndslable"><b>' . lang ( 'plugin/nds_up_ques', 'nds_textsize' ) . '</b></label><input type="text" name="textsize[' . $options ['oid'] . ']"  value="' . $options ['textsize'] . '" size="2" maxlength="2" value="0" > 
        </span>

      
    <span id ="textareawidthsp' . $options ['oid'] . '" name="textareawidthsp' . $options ['oid'] . '"' . (! ($options ['type'] == 7) ? 'style="display:none"' : '') . '>
    <label class="ndslable"><b>' . lang ( 'plugin/nds_up_ques', 'nds_textareawidth' ) . '</b></label><input type="text" name="textareawidth[' . $options ['oid'] . ']" size="2" value="' . $options ['textareawidth'] . '" maxlength="2" value="0" >    </span>
   <span id ="hdtsp' . $options ['oid'] . '" name="hdtsp' . $options ['oid'] . '"' . (! ($options ['type'] == 8) ? 'style="display:none"' : '') . '>
        <label  class="ndslable" ><b>' . lang ( 'plugin/nds_up_ques', 'nds_hdmin' ) . '</b></label><input name="hdmin[' . $options ['oid'] . ']" type="text"  size="2" maxlength="2" value="' . $options ['chmin'] . '" />
		<label class="ndslable"><b>' . lang ( 'plugin/nds_up_ques', 'nds_hdmax' ) . '</b></label><input name="hdmax[' . $options ['oid'] . ']" type="text" size="5" maxlength="5" value="' . $options ['chmax'] . '" /> 
        <label class="ndslable"><b>' . lang ( 'plugin/nds_up_ques', 'nds_hdkld' ) . '</b></label><input name="hdkld[' . $options ['oid'] . ']" type="text" size="2" maxlength="2" value="' . $options ['textsize'] . '" /> 
        <label class="ndslable"><b>' . lang ( 'plugin/nds_up_ques', 'nds_hdstyl' ) . '</b></label>
        <select id= "types" name="hdtstyle[' . $options ['oid'] . ']">
        <option value="1" ' . ($options ['othinput'] == 1 ? 'selected' : '') . '>' . lang ( 'plugin/nds_up_ques', 'nds_hdstylsz' ) . '</option>
        <option value="2" ' . ($options ['othinput'] == 2 ? 'selected' : '') . '>' . lang ( 'plugin/nds_up_ques', 'nds_hdstylxx' ) . '</option>
	   </select>
   </span>
      <span id ="imgstylesp' . $options ['oid'] . '" name="imgstylesp' . $options ['oid'] . '"' . (! ($options ['type'] == 9) ? 'style="display:none"' : '') . '>
         <label class="ndslable"><b>' . lang ( 'plugin/nds_up_ques', 'nds_tpstyl' ) . '</b></label>
           <select id= "types" name="imgstyle[' . $options ['oid'] . ']">
           <option value="3" ' . ($options ['othinput'] == 3 ? 'selected' : '') . '>200*240_3</option>
			<option value="5" ' . ($options ['othinput'] == 5 ? 'selected' : '') . '>120*159_5</option>
			<option value="13" ' . ($options ['othinput'] == 13 ? 'selected' : '') . '>198*130_3</option>
			<option value="14" ' . ($options ['othinput'] == 14 ? 'selected' : '') . '>140*140_4</option>
			</select>
     </span>
     <span id ="tktsp' . $options ['oid'] . '"  name="tktsp' . $options ['oid'] . '"' . ($options ['type'] != 6 ? 'style="display:none"' : '') . '>
         <label class="ndslable"><b>' . lang ( 'plugin/nds_up_ques', 'nds_optexps' ) . '</b></label>
           <select id= "types" name="tktstyle[' . $options ['oid'] . ']">
<option value="51" ' . ($options ['othinput'] == 51 ? 'selected' : '') . '>' . lang ( 'plugin/nds_up_ques', 'nds_tkthh' ) . '</option>
<option value="52" ' . ($options ['othinput'] == 52 ? 'selected' : '') . '>' . lang ( 'plugin/nds_up_ques', 'nds_tktbhh' ) . '</option>
		 </select>
      </span>
      <span id ="selstylesp' . $options ['oid'] . '"  name="selstylesp' . $options ['oid'] . '"' . (! ($options ['type'] == 2 || $options ['type'] == 4) ? 'style="display:none"' : '') . '>
         <label class="ndslable"><b>' . lang ( 'plugin/nds_up_ques', 'nds_selstyle' ) . '</b></label>
           <select id= "types" name="selstyle[' . $options ['oid'] . ']">
<option value="2" ' . ($options ['textsize'] == 2 ? 'selected' : '') . '>2</option>
<option value="3" ' . ($options ['textsize'] == 3 ? 'selected' : '') . '>3</option>
<option value="4" ' . ($options ['textsize'] == 4 ? 'selected' : '') . '>4</option>
<option value="5" ' . ($options ['textsize'] == 5 ? 'selected' : '') . '>5</option>
<option value="6" ' . ($options ['textsize'] == 6 ? 'selected' : '') . '>6</option>
<option value="7" ' . ($options ['textsize'] == 7 ? 'selected' : '') . '>7</option>
<option value="8" ' . ($options ['textsize'] == 8 ? 'selected' : '') . '>8</option>
		 </select>
      </span>   
    </div>          
			 <label><b>' . lang ( 'plugin/nds_up_ques', 'desp' ) . '</b></label>
				<label><b><span style="padding-right:102px;padding-left:40px;">' . lang ( 'plugin/nds_up_ques', 'options' ) . '</span></b></label>
				' . ($sysmode == 2 ? '  
				<label><b><span style="padding-right:50px;">' . lang ( 'plugin/nds_up_ques', 'key' ) . '</span></b></label>
				<label><b>' . lang ( 'plugin/nds_up_ques', 'mark' ) . '</b></label>
				' : '<span id="imgttsp' . $options ['oid'] . '"  name ="imgttsp' . $options ['oid'] . '"' . (! ($options ['type'] == 9) ? 'style="padding-left: 80px;display:none"' : '') . '><label><b>' . lang ( 'plugin/nds_up_ques', 'nds_imgttsp' ) . '</b></label>
</span><span id="jzdxtsp' . $options ['oid'] . '"  name ="jzdxtsp' . $options ['oid'] . '"' . (! ($options ['type'] == 10) ? 'style="padding-left: 80px;display:none"' : '') . '>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<label><b>' . lang ( 'plugin/nds_up_ques', 'nds_ty9sp' ) . '</b></label>
</span>') . '
				<br /><textarea name="desp[' . $options ['oid'] . ']" wrap="off" style="width: 180px; height: 90px; font-size: 12px;">' . $options ['desp'] . '</textarea>&nbsp;&nbsp;&nbsp;		  
			    ' . ($sysmode == 2 ? ' 
			    <textarea name="options[' . $options ['oid'] . ']" wrap="off" style="width: 168px; height: 90px; font-size: 12px;">' . $options ['option'] . '</textarea>&nbsp;&nbsp;&nbsp;	 
				<textarea name="keys[' . $options ['oid'] . ']" wrap="off" style="width: 120px; height: 90px; font-size: 12px;">' . $options ['key'] . '</textarea>&nbsp;&nbsp;&nbsp;
				<textarea name="mark[' . $options ['oid'] . ']" wrap="off" style="width: 80px; height: 90px; font-size: 12px;">' . $options ['mark'] . '</textarea>
				<div style="padding-top:6px;"><label><b>' . lang ( 'plugin/nds_up_ques', 'nds_answer' ) . '</b></label><input type="text" size="111" name="answer[' . $options ['oid'] . ']" value="' . $options ['answer'] . '">
				 </div>' : '<textarea name="options[' . $options ['oid'] . ']" wrap="off" style="width: 250px; height: 90px; font-size: 12px;">' . $options ['option'] . '</textarea>&nbsp;&nbsp;&nbsp;
				<span id="imginsp' . $options ['oid'] . '"  name ="imginsp' . $options ['oid'] . '"' . (! ($options ['type'] == 9 || $options ['type'] == 10) ? 'style="display:none"' : '') . '>  
				<textarea name="keys[' . $options ['oid'] . ']" wrap="off" style="width: 250px; height: 90px; font-size: 12px;">' . $options ['key'] . '</textarea>') . '  
				</span>
				</td>
			<td></td></tr>';
	}
	$mischecktm = lang ( 'plugin/nds_up_ques', 'checktm' );
} else {
	$subject = $_G ['gp_subject'];
	if ($_G ['adminid'] != 1)
		$subject = cutstr ( dhtmlspecialchars ( $subject ), 200 );
	$qclass = $_G ['gp_quesclass'];
	$person = intval ( $_G ['gp_person'] );
	$exp = ndsmktime ( $_G ['gp_exp'] );
	if (! $exp)
		showmessage ( 'nds_up_ques:nds_exptimeerr', '', '', array ('alert' => 'error' ) );
	$tid = intval ( $_G ['gp_tid'] );
	$tid = $tid ? $tid : 0;
	$message = $_G ['gp_message'];
	if ($_G ['adminid'] != 1)
		$message = cutstr ( htmlspecialchars ( $_G ['gp_message'] ), 800 );
	$credit = $_G ['gp_credit'] ? 1 : 0;
	$postmust = $_G ['gp_postmust'] ? 1 : 0;
	$secret = $_G ['gp_secret'] ? 1 : 0;
	$copyauthor = cutstr ( htmlspecialchars ( $_G ['gp_copyauthor'] ), 20 );
	$credit ? $qreward = intval ( $_G ['gp_qreward'] ) : 0;
	$ques_mode = intval ( $_G ['gp_ques_mode'] );
	$isprivate = $_G ['gp_isprivate'] ? 1 : 0;
	$isprivatetext = $isprivate ? cutstr ( htmlspecialchars ( $_G ['gp_isprivatetext'] ), 120 ) : '';
	$seeanswer = $_G ['gp_seeanswer'] ? 1 : 0;
	$ndsflag = $_G ['gp_ndsflag'] ? 1 : 0;
	$ndsright = $_G ['gp_ndsright'] ? 1 : 0;
	if ($tid && $ndsright)
		showmessage ( 'nds_up_ques:nds_tidnotright', '', '', array ('alert' => 'error' ) );
	$tmclass = $_G ['gp_tmtype'] ? 1 : 0;
	$tmtypehd = $_G ['gp_tmtypehd'] ? 1 : 0;
	$timeleft = $_G ['gp_timeleft'] ? intval ( $_G ['gp_timeleft'] ) : 0;
	$expenses = $_G ['gp_expenses'] ? intval ( $_G ['gp_expenses'] ) : 0;
	$nwds = $_G ['gp_nwds'] ? intval ( $_G ['gp_nwds'] ) : 0;
	$strategy = $ques_mode == 2 ? dhtmlspecialchars ( $_G ['gp_strategy'] ) : '';
	$posturl = $ques_mode == 1 ? cutstr ( trim ( $_G ['gp_posturl'] ), 150 ) : '';
	$tmtypetext = $tmclass ? cutstr ( dhtmlspecialchars ( str_replace ( "，", ",", $_G ['gp_tmtypetext'] ) ), 200 ) : '';
	$userrepost = $_G ['gp_userrepost'] ? 1 : 0;
	$ndsabc = $_G ['gp_ndsabc'] ? 1 : 0;
	$isguest = $_G ['gp_isguest'] ? 1 : 0;
	if (! $person)
		showmessage ( 'nds_up_ques:noperson' );
	if (! $subject)
		showmessage ( 'nds_up_ques:nosubject' );
	if ($tid) {
		if ($tid && ! in_array ( $_G ['member'] ['groupid'], $allowhookgroups ))
			showmessage ( 'nds_up_ques:notice7', '', '', array ('alert' => 'error' ) );
		$questid = DB::fetch_first ( "SELECT fid,authorid FROM " . DB::table ( 'forum_thread' ) . " WHERE `tid`='$tid' " );
		if (! $questid ['fid'])
			showmessage ( 'nds_up_ques:notidkook2', '', '', array ('alert' => 'error' ) );
		if ($_G ['adminid'] != 1 && (! in_array ( $questid ['fid'], $allowhookforums ) || $_G ['uid'] != $questid ['authorid']))
			showmessage ( 'nds_up_ques:notidkook1', '', '', array ('alert' => 'error' ) );
	}
	if (! empty ( $_GET ['delete'] )) {
		$t_count = DB::result ( DB::query ( "SELECT COUNT(*) FROM " . DB::table ( 'ques_option' ) . " WHERE `topicid`='$topicid' " ), 0 );
		if (count ( array_map ( 'addslashes', $_GET ['delete'] ) ) >= $t_count)
			showmessage ( 'nds_up_ques:notdelall' );
		if ($ids = dimplode ( $_G ['gp_delete'] )) {
			DB::query ( "DELETE FROM " . DB::table ( 'ques_option' ) . " WHERE oid IN ($ids)" );
			DB::query ( "DELETE FROM " . DB::table ( 'ques_result' ) . " WHERE oid IN ($ids)" );
		}
	}
	if (is_array ( $_G ['gp_order'] )) {
		foreach ( $_G ['gp_order'] as $id => $val ) {
			$maxorder = 1;
			if ($_G ['adminid'] != 1)
				$_G ['gp_desp'] [$id] = dhtmlspecialchars ( $_G ['gp_desp'] [$id] );
			if ($_G ['adminid'] != 1)
				$_G ['gp_options'] [$id] = dhtmlspecialchars ( $_G ['gp_options'] [$id] );
			$title = dhtmlspecialchars ( $_G ['gp_title'] [$id] );
			$desp = $_G ['gp_desp'] [$id];
			$mark = dhtmlspecialchars ( $_G ['gp_mark'] [$id] );
			$option = $_G ['gp_options'] [$id];
			$key = dhtmlspecialchars ( $_G ['gp_keys'] [$id] );
			$option = preg_replace ( '/"([^"]*)"/', '“${1}”', $_G ['gp_options'] [$id] );
			$option = str_replace ( "'", "‘", $option );
			$ques_mode == 2 ? $option = str_replace ( ",", "，", $option ) : '';
			$option = dhtmlspecialchars ( $option );
			$key = preg_replace ( '/"([^"]*)"/', '“${1}”', $_G ['gp_keys'] [$id] );
			$key = str_replace ( "'", "‘", $key );
			$answer = dhtmlspecialchars ( $_G ['gp_answer'] [$id] );
			$order = intval ( $_G ['gp_order'] [$id] );
			$order > $maxorder ? $maxorder = $order : '';
			$least = intval ( $_G ['gp_least'] [$id] );
			$type = intval ( $_G ['gp_types'] [$id] );
			// $ques_mode =  intval($_G['gp_ques_mode']);
			if ($type == 8) {
				$chmin = intval ( $_G ['gp_hdmin'] [$id] );
				$chmax = intval ( $_G ['gp_hdmax'] [$id] );
				$textsize = intval ( $_G ['gp_hdkld'] [$id] );
				$othinput = intval ( $_G ['gp_hdtstyle'] [$id] );
			} else {
				$chmin = intval ( $_G ['gp_chmin'] [$id] );
				$chmax = intval ( $_G ['gp_chmax'] [$id] );
				$textsize = intval ( $_G ['gp_textsize'] [$id] );
				$othinput = intval ( $_G ['gp_othinput'] [$id] );
				if ($type == 2 || $type == 4) {
					$textsize = intval ( $_G ['gp_selstyle'] [$id] );
				}
				if ($type == 9) {
					$othinput = intval ( $_G ['gp_imgstyle'] [$id] );
				}
				if ($type == 6) {
					$othinput = intval ( $_G ['gp_tktstyle'] [$id] );
				}
			}
			$textareawidth = intval ( $_G ['gp_textareawidth'] [$id] );
			$optmclass = intval ( $_G ['gp_tmclass'] [$id] );
			$data = array ('title' => $title, 'order' => $order, 'least' => $least, 'type' => $type, 'desp' => $desp, 'option' => $option, 'key' => $key, 'answer' => $answer, 'mark' => $mark, 'chmin' => $chmin, 'chmax' => $chmax, 'othinput' => $othinput, 'textsize' => $textsize, 'textareawidth' => $textareawidth, 'tmclass' => $optmclass );
			$wdata = array ('oid' => $id );
			DB::update ( 'ques_option', $data, $wdata );
		}
	}
	$data = array ('secret' => $secret, 'postmust' => $postmust, 'credit' => $credit, 'exp' => $exp, 'subject' => $subject, 'message' => $message, 'qclass' => $qclass, 'person' => $person, 'tid' => $tid, 'copyauthor' => $copyauthor, 'qreward' => $qreward, 'isprivate' => $isprivate, 'isprivatetext' => $isprivatetext, 'seeanswer' => $seeanswer, 'ndsflag' => $ndsflag, 'ndsright' => $ndsright, 'tmclass' => $tmclass, 'tmtypehd' => $tmtypehd, 'tmtypetext' => $tmtypetext, 'timeleft' => $timeleft, 'expenses' => $expenses, 'nwds' => $nwds, 'strategy' => $strategy, 'posturl' => $posturl, 'userrepost' => $userrepost, 'ndsabc' => $ndsabc, 'isguest' => $isguest );
	$wdata = array ('topicid' => $topicid );
	DB::UPDATE ( 'ques_topic', $data, $wdata );
	if (is_array ( array_merge ( $_G ['gp_title'], $_G ['gp_newtitle'] ) )) {
		if (implode ( "", array_merge ( $_G ['gp_title'], $_G ['gp_newtitle'] ) ) == '')
			showmessage ( 'nds_up_ques:vilques' );
		foreach ( $_G ['gp_newtitle'] as $id => $value ) {
			if (! empty ( $_G ['gp_newtitle'] [$id] ) && ! (($_G ['gp_newtypes'] [$id] < 6) && empty ( $_G ['gp_newoptions'] [$id] ))) {
				$title = htmlspecialchars ( $_G ['gp_newtitle'] [$id] );
				$desp = ($_G ['adminid'] == 1) ? $_G ['gp_newdesp'] [$id] : dhtmlspecialchars ( $_G ['gp_newdesp'] [$id] );
				$gp_options = preg_replace ( '/"([^"]*)"/', '“${1}”', $_G ['gp_newoptions'] [$id] );
				$gp_options = str_replace ( "'", "‘", $gp_options );
				$gp_options = htmlspecialchars ( $gp_options );
				$gp_keys = preg_replace ( '/"([^"]*)"/', '“${1}”', $_G ['gp_newkeys'] [$id] );
				$gp_keys = str_replace ( "'", "‘", $gp_keys );
				$gp_keys = htmlspecialchars ( $gp_keys );
				$gp_type = intval ( $_G ['gp_newtypes'] [$id] );
				if ($gp_type == 8) {
					$gp_chmin = intval ( $_G ['gp_newhdmin'] [$id] );
					$gp_chmax = intval ( $_G ['gp_newhdmax'] [$id] );
					$gp_textsize = intval ( $_G ['gp_newhdkld'] [$id] );
					$gp_othinput = intval ( $_G ['gp_newhdtstyle'] [$id] );
				} else {
					$gp_chmin = intval ( $_G ['gp_newchmin'] [$id] );
					$gp_chmax = intval ( $_G ['gp_newchmax'] [$id] );
					$gp_textsize = intval ( $_G ['gp_newtextsize'] [$id] );
					$gp_othinput = intval ( $_G ['gp_newothinput'] [$id] );
					if ($gp_type == 2 || $gp_type == 4) {
						$gp_textsize = intval ( $_G ['gp_newselstyle'] [$id] );
					}
					if ($gp_type == 9) {
						$gp_othinput = intval ( $_G ['gp_newimgstyle'] [$id] );
					}
					if ($gp_type == 6) {
						$gp_othinput = intval ( $_G ['gp_newtktstyle'] [$id] );
					}
				}
				$neworder = intval ( $_G ['gp_neworder'] [$id] );
				if ($neworder == 0)
					$neworder = $maxorder ++;
				
				$data = array ('topicid' => $topicid, 'title' => $title, 'desp' => $desp, 'mark' => dhtmlspecialchars ( $_G ['gp_newmark'] [$id] ), 'option' => $gp_options, 'key' => $gp_keys, 'answer' => dhtmlspecialchars ( $_G ['gp_newanswer'] [$id] ), 'order' => $neworder, 'least' => intval ( $_G ['gp_newleast'] [$id] ), 'type' => $gp_type, 'chmin' => $gp_chmin, 'chmax' => $gp_chmax, 'othinput' => $gp_othinput, 'textsize' => $gp_textsize, 'textareawidth' => intval ( $_G ['gp_newtextareawidth'] [$id] ), 'tmclass' => intval ( $_G ['gp_newtmclass'] [$id] ) );
				$oid = DB::insert ( 'ques_option', $data, true );
			}
		}
	}
	showmessage ( 'nds_up_ques:editok', 'plugin.php?id=nds_up_ques:nds_up_ques&action=viewques&topicid=' . $topicid );
}
$navtitle = lang ( 'plugin/nds_up_ques', 'action_5' ) . ' - ' . $navtitle;
include template ( 'nds_up_ques:ques_edit' );

?>