<?php
if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}
require_once(DISCUZ_ROOT.'source/plugin/milu_seotool/config.inc.php');
sload('F:keyword,F:copyright');
$header_arr = array('keyword_set', 'forum_keyword', 'portal_keyword', 'trend_data_count');
if(!VIP) unset($header_arr[array_search('portal_keyword', $header_arr)]);
$args = array('default_ac' => 'keyword_set');

$keyword_set = tool_common_get('keyword');
$keyword_set['keyword_type'] = dunserialize($keyword_set['keyword_type']);
seo_tpl($args);

function keyword_set(){
	global $_G,$pluin_info,$keyword_type_arr,$keyword_set;
	if($_GET['editsubmit']){
		$set = $_POST['set'];
		$set['keyword_type'] = serialize($_POST['keyword_type']);
		//计划任务要重新设定
		tool_common_set('milu_seotool_cron', array('keyword_rank_check' => 0));
		tool_common_set('keyword', $set);
		cpmsg(stlang('op_success'), PLUGIN_GO."keyword", 'succeed');	
	}else{
		$info = $keyword_set;
		$show .= seoOutput::show_title(stlang('keyword_set_title'));
		$show .=  seoOutput::show_tr(
				array(
					'name' => stlang('auto_check_rank'),
					'desc' => stlang('auto_check_rank_notice'),
					'arr' => array(
						'name' => 'is_auto_rank',
						'info' => $info,
						'int_val' => 2,
						'lang_type' => 2,
					),
				)
				,'radio');
		
	
		$show .=  seoOutput::show_tr(
				array(
					'name' => stlang('tag_auto_add_keyword'),
					'desc' => stlang('tag_auto_add_keyword_notice'),
					'arr' => array(
						'name' => 'tag_add_keyword',
						'info' => $info,
						'int_val' => 2,
						'lang_type' => 2,
					),
				)
				,'radio');

		
		$info['show'] = $show;
		$info['tpl'] = 'common_set';
		return $info;
	}
	
}



function forum_keyword(){
	global $_G;
	$update = intval($_GET['update']) ? 1 : 0;
	if($update) {
		tool_common_set('keyword', array('rank_data' => array(), 'keyword_data' => array(), 'zhishu_cache_data' => array()));
	}
	$cat_arr = forum_data_format($update);
	$info['cat_arr'] = $cat_arr;
	$info['show'] = $show;
	$info['data_type'] = 0;
	$info['tpl'] = 'keyword_list';
	return $info;
}


//数据趋势
function trend_data_count(){
	global $_G;
	$keyword_set = tool_common_get('keyword');
	$view_info['keyword'] = dstripslashes($_GET['keyword']);
	$view_info['start_dateline'] = $_POST['start_dateline'] ? strtotime($_POST['start_dateline']) : '';
	$view_info['end_dateline'] = $_POST['end_dateline'] ? strtotime($_POST['end_dateline']) : '';
	$view_info['start_dateline'] =  $view_info['start_dateline'] ? $view_info['start_dateline'] : $_G['timestamp'] - 3600*24*7;
	$view_info['end_dateline'] = $view_info['end_dateline'] ? $view_info['end_dateline'] : $_G['timestamp'];
	$dateline_arr = st_dayRange($view_info['start_dateline'], $view_info['end_dateline']);
	$from = $dateline_arr[1]['from'];
	$end = $dateline_arr[count($dateline_arr)]['end'];
	if($_GET['inajax'] || $_POST['submit'] || $view_info['keyword']){
		$title = date('Y-m-d', $from).' '.stlang('to_').' '.date('Y-m-d', $end). ' '.stlang('trend_flash_title2');
		
		
		$sql = " AND (daytime<'".date('Ymd', $end)."' OR daytime='".date('Ymd', $end)."') AND (daytime>'".date('Ymd', $from)."' OR daytime='".date('Ymd', $from)."')";
		if($view_info['keyword']){
			$keyword_arr = array($view_info['keyword']);
		}else{
			$query = DB::query("SELECT * FROM ".DB::table('milu_seotool_keyword')."  LIMIT 0,150");
			$items = array();
			while($rs = DB::fetch($query)) {
				$keyword_arr[] = $rs['name'];
			}
		}
		$title_arr = $dateline_arr;
		$rank_value_arr  =  array();
		foreach($keyword_arr as $k0 => $kw){
			$query = DB::query("SELECT k.*,r.* FROM ".DB::table('milu_seotool_keyword')." as k Inner Join ".DB::table('milu_seotool_keyword_rank')." as r ON r.kid=k.kid WHERE name='".st_addslashes($kw)."' $sql ");
			while($rs = DB::fetch($query)) {
				foreach($dateline_arr as $k => $v){
					if($rs['daytime'] == date('Ymd',$v['end'])){
						$dateline_arr[$k]['rank'] = $rs['rank'];
						$rank_value_arr[$kw][$k] = $rs['rank'];
					}
				}
			}
		}
		if(is_array($dateline_arr)){
			$data_arr['keyword_rank'] = array(
				0 => array(
					 'name' => stlang('keyword'),
					 'value' => $keyword_arr
				),
			);
			foreach($dateline_arr as $k => $v){
				$i = 0;
				$data_arr['keyword_rank'][$k+1]['name'] = $v['name'];
				foreach($keyword_arr as $k0 => $v0){
					$data_arr['keyword_rank'][$k+1]['value'][$i] = $rank_value_arr[$v0][$k];
					$i++;
				}
			}
				
		}
		$show_arr[] = seoOutput::show_table($data_arr, 'keyword_rank', array('caption' => $_GET['inajax'] ? '<span style="float:right"><a href="?'.PLUGIN_GO.'keyword&myac=trend_data_count&keyword='.$view_info['keyword'].'">'.stlang('view_more').'</a></span>' : ''));
		$info['show'] = implode('', $show_arr);
	}
	if($_GET['inajax']) show_seotool_window(stlang('one_week_ranks'), $info['show'], array('f' => 1));
	$info['view_show'] = $view_info;
	$info['start_dateline'] = date('Y-m-d', $view_info['start_dateline']);
	$info['end_dateline'] = date('Y-m-d', $view_info['end_dateline']);
	$info['tpl'] = 'keyword_trend';
	return $info;
}

function forum_data_format($update = 0){
	global $_G;
	loadcache('milu_seotool_forum_catdata');
	if( !($cat_arr = $_G['cache']['milu_seotool_forum_catdata'] ) || $update == 1){
		$query = DB::query("SELECT f.fid, f.type, f.name, f.fup, ff.keywords, ff.seodescription, ff.seotitle FROM ".DB::table('forum_forum')." f
			LEFT JOIN ".DB::table('forum_forumfield')." ff ON ff.fid=f.fid LEFT JOIN ".DB::table('forum_access')." a ON a.fid=f.fid AND a.allowview>'0' WHERE f.status<>'3' ORDER BY f.type, f.displayorder");
		while($rs = DB::fetch($query)) {
			$forumseoset = array(
				'seotitle' => $rs['seotitle'],
				'seokeywords' => $rs['keywords'],
				'seodescription' => $rs['seodescription']
			);
			list($navtitle, $metadescription, $metakeywords) = get_seosetting('threadlist', array('fgroup' => $rs['name']), array('seokeywords' => $rs['keywords']), $forumseoset);
			$caturl_str = $rs['fup'] > 0 ? 'forum.php?mod=forumdisplay&fid='.$rs['fid'] : 'forum.php?gid='.$rs['fid'];
			$cat_arr[$rs['fid']] = array('catid' => $rs['fid'], 'upid' => $rs['fup'], 'catname' => $rs['name'], 'keywords' => explode(',', $metakeywords), 'caturl' => $_G['siteurl'].$caturl_str);
		}
		foreach($cat_arr as $k => $v){
			if($v['upid'] > 0){
				$cat_arr[$v['upid']]['children'][$k] = $k;
			}
		}
		foreach($cat_arr as $k => $v){
			if($v['upid'] == 0 && !$v['children']) unset($cat_arr[$k]);
		}
		save_syscache('milu_seotool_forum_catdata', $cat_arr);
	}
	return $cat_arr;
}

function show_keyword_info_tr($keyword_info){
	if(!$keyword_info) return;
	$str = "<td width=\"60\"><p><a href=\"javascript:void(0);\" onclick=\"keyword_ajax_bat('".$keyword_info['args_info']."');\">".stlang('bat_query')."</a></td><td width=\"230\">".$keyword_info['keyword']."</td><td width=\"65\">".$keyword_info['zhishu']."</td><td width=\"65\">".$keyword_info['count']."</td><td width=\"100\">".$keyword_info['density']."</td>
<td width=\"300\">".$keyword_info['rank']."</td><td width=\"65\">".$keyword_info['trend']."</td>";
	return $str;
}


function st_ajax_keyword(){
	global $_G;
	$data_type = intval($_GET['data_type']);
	$catid = intval($_GET['catid']);
	$keyword = st_format_url(st_iconv($_GET['keyword'], 'UTF-8', CHARSET), 1, 0);
	//关键词排名、排名趋势
	$rank_data = get_rank_data($keyword);
	
	//指数、密度、频率
	if($data_type == 1){//门户
		loadcache('milu_seotool_portal_catdata');
		$cat_arr = $_G['cache']['milu_seotool_portal_catdata'];
		$caturl = $cat_arr[$catid]['caturl'];
	}else{
		loadcache('milu_seotool_forum_catdata');
		$cat_arr = $_G['cache']['milu_seotool_forum_catdata'];
		$caturl = $cat_arr[$catid]['caturl'];
	}
	$keywords = $cat_arr[$catid]['keywords'];
	$keyword_data = keyword_density_set($caturl, $keywords, 2);
	$result_data = $keyword_data[$keyword];
	if(!$result_data) $result_data = array('count' => 0, 'density' => '0.0%', 'status' => 1);
	$result_data['zhishu'] = baidu_zhishu($keyword);//指数
	$result_data['rank'] = implode(',', $rank_data['rank']);
	$result_data['trend'] = $rank_data['trend'];
	return json_encode($result_data);
}

function st_redirect_zhishu(){
	$keyword = st_format_url(st_iconv($_GET['keyword'], 'UTF-8', 'GBK'), 1, 0);
	$url = 'http://index.baidu.com/main/word.php?word='.$keyword;
	dheader('location: '.$url);
}

function show_keyword($data_arr, $catid = 0){
	global $_G;
	if(!$data_arr || !$data_arr[0]) return;
	$str = '';
	$info = array();
	$data_type = $_GET['myac'] == 'portal_keyword' ? 1 : 0;
	$keyword_count_js = '<script type="text/javascript" language="javascript">cat_keyword_count('.$catid.')</script>'; 
	$keyword_data = tool_common_get('keyword');
	$count_keyword_data = $keyword_data['keyword_data'];
	$zhishu_cache_data = $keyword_data['zhishu_cache_data']['data'];
	$rank_cache_data = $keyword_data['rank_data']['data'];
	$data_flag = 1;
	if($keyword_data){
		foreach((array)$data_arr as $k => $v){
			$v_md5 = md5($v);
			if(!isset($count_keyword_data[$v]) || !isset($zhishu_cache_data[$v_md5])) {
				$data_flag = 0;
				continue;
			}
		}
	}else{
		$data_flag = 0;
	}
	if($data_flag == 1) $keyword_count_js = '';//存在缓存数据
	$result_data = array();
	foreach($data_arr as $k => $v){
		$key = $catid.md5($v);
		$v_md5 = md5($v);
		$info = $count_keyword_data[$v];
		$key_id = $catid.'_'.$key;
		$query_href = '<a class="query_keyword" key_id="'.$key_id.'" kw="'.$v.'" catid="'.$catid.'" href="javascript:void(0)">'.stlang('query').'</a>';
		//密度、频率
		if(!$info){
			$info['density'] = $query_href;
			$info['count'] = $query_href;
		}
		//指数
		if(!isset($zhishu_cache_data[$v_md5])){
			$info['zhishu'] = $query_href;
		}else{
			$info['zhishu'] = $zhishu_cache_data[$v_md5] > -1 ? $zhishu_cache_data[$v_md5] : stlang('view');
			$info['zhishu'] = '<a href="http://index.baidu.com/main/word.php?word='.$v.'" target="_blank">'.$info['zhishu'].'</a>';
		}
		//排名
		$rank_info = $rank_cache_data[$v_md5];
		if(!$rank_info){
			$rank_info['rank'] = $query_href;
			$rank_info['trend'] = $query_href;
		}else{
			$rank_info['rank'] = $rank_info['rank'][0] ? implode(',', $rank_info['rank']) : '-';
			$rank_info['rank'] = '<a href="http://www.baidu.com/s?wd='.$v.'" target="_brank">'.$rank_info['rank'].'</a>';
			$img_type = 'noupdown';
			$num = '';
			if(intval($rank_info['trend']) > 0 || (string)$rank_info['trend'] == '+'){
				$img_type = 'up';
				if($rank_info['trend'] != '+') $num = abs($rank_info['trend']);
			}else if($rank_info['trend'] < 0 || (string)$rank_info['trend'] == '-'){
				$img_type = 'down';
				if($rank_info['trend'] != '-') $num = abs($rank_info['trend']);
			}
			$rank_info['trend'] = '<a onclick="get_keyword_rank_trend(\''.$v.'\')" href="javascript:void(0)"><img style=" margin-top:5px;" name="" src="'.PLUGIN_URL.'static/image/'.$img_type.'.gif"  alt="" />'.$num.'</a>';
		}
		
		
		$field_arr = array( 'zhishu', 'count', 'density', 'rank', 'trend');
		foreach($field_arr as $v2){
			$data_info = $v2 == 'rank' || $v2 == 'trend' ? $rank_info : $info;
			$result_data[$v2] .= '<div class="keyword_show"><span class="k_'.$v2.'" id="'.$v2.$key_id.'">'.$data_info[$v2].'</span></div>';
		}
		$result_data['keyword'] .= '<div class="keyword_show"><span class="keyword" title="'.$v.'">'.cutstr($v, 30).'</span></div>';
		$result_data['args_info'][] = $catid.'|'.$key_id.'|'.$v;
	}
	$result_data['args_info'] = implode('@', $result_data['args_info']);
	return $result_data;
}

function st_ajax_keyword_list(){
	$s = trim(st_format_url($_GET['q']));
	if(!$s) return;
	$query = DB::query("SELECT * FROM ".DB::table('milu_seotool_keyword')."  WHERE name like '".st_addslashes($s)."%' LIMIT 0,20");
	$items = array();
	while($rs = DB::fetch($query)) {
		$name = st_iconv($rs['name'], CHARSET, 'UTF-8');
		$items[] = $name;
	}
	if(!$items) return;	
	return json_encode($items);
}

function baidu_zhishu($keyword, $update = 0){
	global $_G;
	$client_info = get_client_info();
	if(!$client_info || !$client_info['domain']) return -3;
	$cache_key = 'zhishu_cache_data';
	$keyword_data = tool_common_get('keyword');
	$keyword_cache_data = $keyword_data[$cache_key];
	$key = md5($keyword);
	$data = $keyword_cache_data['data'][$key];
	$diff_dateline = $_G['timestamp'] - $keyword_cache_data['dateline'];
	if($diff_dateline > 24*3600*2) {
		unset($data);
		tool_common_set('keyword', array($cache_key => array()) );
	}
	if(!isset($data) || $update == 1 ){
		$key_data = get_site_key();
		$data = st_rpcClient()->RPC_baidu_zhishu($keyword, $key_data);
		if(is_object($data)){
			if($data->Message || $data->Number == 0) {
				return -2;
			}	
		}
		if($data < 0) return -1;
		$keyword_cache_data['data'][$key] = $data;
		$st_cache_data['dateline'] = $keyword_cache_data['dateline'] ? $keyword_cache_data['dateline'] : $_G['timestamp'];
 		$st_cache_data['data'] = $keyword_cache_data['data'];
		tool_common_set('keyword', array($cache_key => $st_cache_data));
	}
	return $data;
	
}

function keyword_density_set($caturl, $keyword_arr = array(), $get = 1){
	if(!$keyword_arr) return;
	$keyword_set = tool_common_get('keyword');
	$content = st_get_contents($caturl, array('cache' => 3600*24*5));
	if(!$content) return;
	$content = st_striptext($content);
	$content_lenth = strlen($content);
	$count_data = array();
	foreach($keyword_arr as $k => $v){
		$count = substr_count($content, $v);
		$density = strlen($v) * $count / $content_lenth;
		$status = ($density < 0.08 && $density > 0.02) ? 1 : 0;
		$density =  sprintf("%01.1f", $density*100).'%';
		$count_data[$v] = $keyword_set['keyword_data'][$v] = array('count' => $count, 'density' => $density, 'status' => $status);
	}
	tool_common_set('keyword', $keyword_set);
	if($get != 1) return $count_data;
	return $keyword_set['keyword_data'];
}



?>