<?php
/**
 * hy_v 播放器插件
 * 原创版权 by 何勇
 */

if(!defined('IN_DISCUZ')) {
	exit('Access Denied');
}


// 播放器主类
class plugin_hy_v {
		
	// js
	public function global_header() {
		global $_G;
		$setting = $_G['cache']['plugin']['hy_v'];
		$setting['siteurl']  = $_G['siteurl'];
		$setting['v_name'] = trim($setting['v_name']);
		$setting['v_name'] = empty($setting['v_name']) ? $_G['setting']['bbname'] : $setting['v_name'];
		$setting['v_link'] = trim($setting['v_link']);
		$setting['v_link'] = empty($setting['v_link']) ? $setting['siteurl'] : $setting['v_link'];
		
		$js_str = '';
		$js_src = '';
		if($_G['setting']['plugins']['func'][HOOKTYPE]['hy_v_header']) {
			$_G['hy_v_js_str'] = & $js_str;
			$_G['hy_v_js_src'] = & $js_src;
			hookscript('hy_v_header', 'global', 'funcs', array('setting' => & $setting), 'hy_v_header');
		}
		
		return '<script type="text/javascript" src="' . $setting['siteurl']  . 'source/plugin/hy_v/cmp.js"></script>' . $js_src . '<script type="text/javascript">var hy_v_flashvars = {' . $this->_flashvars($setting) . '};' . $js_str . '</script>';
	}	
	
	//嵌入分析
	public function _parse($setting, $str, $wah, $url){
		global $_G;
		
		$_G['hy_v_allnum'] = isset($_G['hy_v_allnum']) ? intval($_G['hy_v_allnum']) : 0;
		$_G['hy_v_allnum']++;
		
		if(!empty($wah)){
			$wah = preg_replace('/[^\d,]/', '', $wah);
			$wah = preg_split('/,/', $wah, 2, PREG_SPLIT_NO_EMPTY);
			if(intval($wah[0]) > 0){
				$setting['v_width'] = $wah[0];
			}
			if(intval($wah[1]) > 0){
				$setting['v_height'] = $wah[1];
			}
		}
		$setting['siteurl']  = $_G['siteurl'];
		
		$parse = '';
		if($_G['setting']['plugins']['func'][HOOKTYPE]['hy_v_parse']) {		
			$_G['hy_v_parse'][$_G['hy_v_allnum']] = & $parse;
			hookscript('hy_v_parse', 'global', 'funcs', array('setting' => & $setting, 'str' => & $str, 'url' => & $url, 'num' => $_G['hy_v_allnum']), 'hy_v_parse');
		}
		
		if(in_array(fileext($url), array('flv', 'mp4')) || !empty($parse)){
			$this->_player($setting, $str, $url, '');
		}
		
		return $str;
	}
	
	//参数
	public function _flashvars(&$setting){		
		$flashvars = array();		
		$flashvars[] = 'url:""';
		$flashvars[] = 'lists:""';
		
		$flashvars[] = 'name:"' . ($setting['v_name'])  . '"';
		$flashvars[] = 'link:"' . ($setting['v_link'])  . '"';
		$flashvars[] = 'link_target:"_blank"';
		$flashvars[] = 'logo:"' . ($setting['v_logo'])  . '"';
		$flashvars[] = 'logo_alpha:"' . ($setting['v_logo_alpha'])  . '"';
		$flashvars[] = 'auto_play:"' . ($setting['v_auto_play'])  . '"';
		$flashvars[] = 'skin:"skin.swf"';
		return implode(',', $flashvars);
	}
	
	//播放器
	public function _player(&$setting, &$str, &$url, $type){
		global $_G;
		$player_js = '';		
		$player_str = '';
		if($_G['setting']['plugins']['func'][HOOKTYPE]['hy_v_player']) {
			$_G['hy_v_player_js'][$_G['hy_v_allnum']] = & $player_js;
			$_G['hy_v_player_str'][$_G['hy_v_allnum']] = & $player_str;
			hookscript('hy_v_player', 'global', 'funcs', array('setting' => & $setting, 'str' => & $str, 'url' => & $url, 'type' => & $type, 'num' => $_G['hy_v_allnum']), 'hy_v_player');
		}
		$merge_js = '';
		if(strpos($url, '|')){
			$url = $setting['siteurl'] . 'source/plugin/hy_v/merge.php?url=' . urlencode($url);
			$merge_js = 'flashvars.type="merge"; hy_v_add_model(flashvars, "' . $setting['siteurl'] . 'source/plugin/hy_v/merge.swf");';
		}
		
		$_G['hy_v_player_num'] = isset($_G['hy_v_player_num']) ? intval($_G['hy_v_player_num']) : 0;
		$_G['hy_v_player_num']++;
		
		$randomid = 'hy_v_' . $_G['hy_v_allnum'];
		$html = '<span style="position:relative;display:inline-block;">';
		$html .= '<span id="' . $randomid . '" style="display:inline-block;"></span>';
		$html .= $player_str . '</span>';
		$html .= '<script type="text/javascript">';
		$html .= 'var flashvars = cloneobj(hy_v_flashvars);';
		$html .= ($_G['hy_v_player_num'] > 1 ? 'flashvars.auto_play="0";' : '');
		$html .= 'flashvars.src="' . $url . '";';
		$html .= $merge_js . $player_js;
		$html .= '$("' . $randomid . '").innerHTML = CMP.create("hy_v_cmp_' . $_G['hy_v_allnum'] . '", "' . $setting['v_width'] . '", "' . $setting['v_height'] . '", "' . $setting['siteurl'] . 'source/plugin/hy_v/cmp.swf", flashvars, {wmode:"opaque"});';
		$html .= '</script>';
		$str = $html;
	}
	
	// 播放器 to discuzcode
	public function discuzcode() {
		global $_G;
		$setting = $_G['cache']['plugin']['hy_v'];
		$v_code = preg_replace('/\s*/', '', $setting['v_code']);
		if(!empty($v_code)){
			$v_code = str_replace(',', '|', $v_code);
			$_G['discuzcodemessage'] = preg_replace("/\[($v_code)(=[\w,]+?|)\]\s*([^\[\<\r\n]+?)\s*\[\/($v_code)\]/ies", "\$this->_parse(\$setting, '\\0', '\\2', '\\3')", $_G['discuzcodemessage']);
		}
	}
}

// 播放器 to 论坛
class plugin_hy_v_forum extends plugin_hy_v {}
