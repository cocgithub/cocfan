<?php
if(!defined('IN_ADMINCP')) exit('Access Denied');

if($_GET['formhash']!=FORMHASH){
    echo '<div class="infobox"><h4 class="infotitle2">'.lang('plugin/saion_seo', 'operation').'</h4>&nbsp;<input type="button" class="btn" onclick="location.href=\''.ADMINSCRIPT.'?action=plugins&operation=config&identifier=saion_seo&pmod=tools&formhash='.FORMHASH.'&mod=flag\'" value="'.lang('plugin/saion_seo', 'operation_flag').'">&nbsp;&nbsp;<input type="button" class="btn" onclick="location.href=\''.ADMINSCRIPT.'?action=plugins&operation=config&identifier=saion_seo&pmod=tools&formhash='.FORMHASH.'&mod=total\'" value="'.lang('plugin/saion_seo', 'operation_total').'">&nbsp;&nbsp;<input type="button" class="btn" onclick="location.href=\''.ADMINSCRIPT.'?action=plugins&operation=config&identifier=saion_seo&pmod=tools&formhash='.FORMHASH.'&mod=clean\'" value="'.lang('plugin/saion_seo', 'operation_clean').'">&nbsp;&nbsp;<br/></div>';
			exit;
}elseif($_GET['mod']=='flag'){
    $today=strtotime(date('Y-m-d', TIMESTAMP));
    $bt=DB::result_first('SELECT count(*) FROM '.DB::table('saion_seo_visit').' WHERE dateline>'.$today." AND spider='baidu'");
    $btt=DB::result_first('SELECT count(*) FROM '.DB::table('saion_seo_visit').' WHERE dateline>'.$today." AND spider='baidu' AND time=1");
    $gg=DB::result_first('SELECT count(*) FROM '.DB::table('saion_seo_visit').' WHERE dateline>'.$today." AND spider='google'");
    $ggt=DB::result_first('SELECT count(*) FROM '.DB::table('saion_seo_visit').' WHERE dateline>'.$today." AND spider='google' AND time=1");

    $min=DB::result_first('SELECT min(tid) FROM '.DB::table('forum_thread').' WHERE dateline>='.(TIMESTAMP-86400));
    $max=DB::result_first('SELECT max(tid) FROM '.DB::table('forum_thread').' WHERE dateline<='.(TIMESTAMP-43200));

    if(!$min || !$max || $min==$max) $atp=0;
    else $atp=$max-$min+1;

    $btp=DB::result_first('SELECT count(*) FROM '.DB::table('saion_seo_visit')." WHERE type='thread' AND id>='$min' AND id<='$max' AND spider='baidu'");
    $gtp=DB::result_first('SELECT count(*) FROM '.DB::table('saion_seo_visit')." WHERE type='thread' AND id>='$min' AND id<='$max' AND spider='baidu'");

    $btpatp=round($btp/$atp*100);
    $gtpatp=round($gtp/$atp*100);

    showtableheader();
    echo '<tr><th class="partition" colspan="15">'.lang('plugin/saion_seo', 'operation_flag').'</th></tr>';
    echo '<tr><th>'.lang('plugin/saion_seo', 'operation_flag_data', array('bt'=>$bt, 'btt'=>$btt, 'gg'=>$gg, 'ggt'=>$ggt, 'atp'=>$atp, 'btp'=>$btp, 'gtp'=>$gtp, 'btpatp'=>$btpatp, 'gtpatp'=>$gtpatp)).'</th></tr>';
    showtablefooter();
}elseif($_GET['mod']=='total'){
    $time=DB::result_first('SELECT sum(time) FROM '.DB::table('saion_seo_visit').' WHERE 1');
    $time1=DB::result_first('SELECT count(*) FROM '.DB::table('saion_seo_visit').' WHERE 1');
    $tt=DB::result_first('SELECT count(DISTINCT(id)) FROM '.DB::table('saion_seo_visit')." WHERE type='thread'");
    $ttt=DB::result_first('SELECT count(*) FROM '.DB::table('forum_thread')." WHERE 1");
    $ttttt=round($tt/$ttt*100);

    showtableheader();
    echo '<tr><th class="partition" colspan="15">'.lang('plugin/saion_seo', 'operation_total').'</th></tr>';
    echo '<tr><th>'.lang('plugin/saion_seo', 'operation_total_data', array('time'=>$time, 'time1'=>$time1, 'tt'=>$tt, 'ttt'=>$ttt, 'ttttt'=>$ttttt)).'</th></tr>';
    showtablefooter();
}elseif($_GET['mod']=='clean'){
    DB::query('TRUNCATE TABLE '.DB::table('saion_seo_visit'));
    cpmsg(lang('plugin/saion_seo', 'operation_clean_successful'), '', 'succeed');
}else{
    cpmsg('undefined_action', '', 'error');
}
