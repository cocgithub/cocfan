<?php

/*
	(C)2007-2013 [www.7ree.com]
	This is NOT a freeware, use is subject to license terms
	Agreement: http://addon.discuz.com/?@7.developer.doc/agreement_7ree_html
	More Plugins: http://addon.discuz.com/?@7ree
*/



if(!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
	exit('Access Denied');
}

	$pluginid_7ree = 'toplist_7ree';
	$langdir_7ree = 'plugin/'.$pluginid_7ree;

	$lang_production_7ree = lang($langdir_7ree, 'php_more_lang_production_7ree');
	$lang_free_7ree = lang($langdir_7ree, 'php_more_lang_free_7ree');
	$lang_trade_7ree = lang($langdir_7ree, 'php_more_lang_trade_7ree');
	$lang_shangjia_7ree = lang($langdir_7ree, 'php_more_lang_shangjia_7ree');
	$lang_baojia_7ree = lang($langdir_7ree, 'php_more_lang_baojia_7ree');
	$lang_moreinfo_7ree = lang($langdir_7ree, 'php_more_lang_moreinfo_7ree');
	$lang_service_7ree = lang($langdir_7ree, 'php_more_lang_service_7ree');
	$lang_logo_7ree = lang($langdir_7ree, 'php_more_lang_logo_7ree');
	$lang_logodetail_7ree = lang($langdir_7ree, 'php_more_lang_logodetail_7ree');
	$lang_analysis_7ree = lang($langdir_7ree, 'php_more_lang_analysis_7ree');
	$lang_analysisdetail_7ree = lang($langdir_7ree, 'php_more_lang_analysisdetail_7ree');
	$lang_discuz_7ree = lang($langdir_7ree, 'php_more_lang_discuz_7ree');
	$lang_discuzdetail_7ree = lang($langdir_7ree, 'php_more_lang_discuzdetail_7ree');
	$lang_about_7ree = lang($langdir_7ree, 'php_more_lang_about_7ree');
	$lang_trust_7ree = lang($langdir_7ree, 'php_more_lang_trust_7ree');
	$lang_trustdetail_7ree = lang($langdir_7ree, 'php_more_lang_trustdetail_7ree');
	$lang_detailinfo_7ree = lang($langdir_7ree, 'php_more_lang_detailinfo_7ree');
	$lang_siteweibo_7ree = lang($langdir_7ree, 'php_more_lang_siteweibo_7ree');
	$lang_site_7ree = lang($langdir_7ree, 'php_more_lang_site_7ree');
	$lang_weibo_7ree = lang($langdir_7ree, 'php_more_lang_weibo_7ree');
	$lang_qqweibo_7ree = lang($langdir_7ree, 'php_more_lang_qqweibo_7ree');
	$lang_sinaweibo_7ree = lang($langdir_7ree, 'php_more_lang_sinaweibo_7ree');
	$lang_club_7ree = lang($langdir_7ree, 'php_more_lang_club_7ree');
	$lang_qqqun_7ree = lang($langdir_7ree, 'php_more_lang_qqqun_7ree');
	$lang_qqcopy_7ree = lang($langdir_7ree, 'php_more_lang_qqcopy_7ree');
	$lang_qqtip_7ree = lang($langdir_7ree, 'php_more_lang_qqtip_7ree');
	$lang_document_7ree = lang($langdir_7ree, 'php_more_lang_document_7ree');
	$lang_agreement_7ree = lang($langdir_7ree, 'php_more_lang_agreement_7ree');
	$lang_lawyer_7ree = lang($langdir_7ree, 'php_more_lang_lawyer_7ree');
	$lang_wuxingliang_7ree = lang($langdir_7ree, 'php_more_lang_wuxingliang_7ree');
	$lang_appdy1_7ree = lang($langdir_7ree, 'php_more_lang_appdy1_7ree');
	$lang_appdy2_7ree = lang($langdir_7ree, 'php_more_lang_appdy2_7ree');
	$lang_yu_7ree = lang($langdir_7ree, 'php_more_lang_yu_7ree');
	$lang_cong_7ree = lang($langdir_7ree, 'php_more_lang_cong_7ree');
	$lang_zhongxin_7ree = lang($langdir_7ree, 'php_more_lang_zhongxin_7ree');
	$lang_anzhuang_7ree = lang($langdir_7ree, 'php_more_lang_anzhuang_7ree');
	$lang_gengxin_7ree = lang($langdir_7ree, 'php_more_lang_gengxin_7ree');	
	$lang_dytitle1_7ree = lang($langdir_7ree, 'php_more_lang_dytitle1_7ree');		
	$lang_dytitle2_7ree = lang($langdir_7ree, 'php_more_lang_dytitle2_7ree');		
	$lang_dytitle3_7ree = lang($langdir_7ree, 'php_more_lang_dytitle3_7ree');		
	$lang_dytitle4_7ree = lang($langdir_7ree, 'php_more_lang_dytitle4_7ree');		

 		$key = ''; 
		$day_7ree = 1;
		$dateline = $_G[timestamp] - 86400*$day_7ree;
		$type = 'all';	
		
		$param = array(
				'key' =>'7ree',
				'dateline' => $dateline,
				'page' =>1,
				'type'=>$type,
				'id'=>''		
				);
	

		ksort($param); 
		$params = '';
		foreach($param as $k => $v) {
			$params .= '&'.$k.'='.rawurlencode($v);
		}
		$md5hash = md5(substr($params, 1).$key);
		
	
		
		$get_info_7ree = "http://open.discuz.net/api/getusers?key=7ree&dateline=".$dateline."&page=1&type=".$type."&id=&md5hash=".$md5hash;
		$get_allapp_7ree ="http://open.discuz.net/api/getaddons/?key=7ree";
		
		$info_contents = file_get_contents($get_info_7ree);
		$app_countents = file_get_contents($get_allapp_7ree); 	

		$info_array_7ree = unserialize($info_contents);
		$app_array_7ree = unserialize($app_countents);
		foreach ($app_array_7ree[DATA] as $key_7ree=>$value_7ree){
		 		$IDlist_7ree[] = $value_7ree[ID]; 
		 		$NAMElist_7ree[] = $value_7ree[name]; 		
		}
	
		

	$count_7ree = 0;
	$sell_7ree = 0;
	
	foreach ($info_array_7ree[DATA] as $key=>$value){
			$info_array_7ree[DATA][$key][time_7ree] = dgmdate($value[createtime],'u');
			$info_array_7ree[DATA][$key][name_7ree] = str_replace($IDlist_7ree,$NAMElist_7ree,$info_array_7ree[DATA][$key][ID]);
			$formStr_7ree = array("http://");
			$replaceStr_7ree = array("");
			$info_array_7ree[DATA][$key][siteurl] = str_replace($formStr_7ree,$replaceStr_7ree,$info_array_7ree[DATA][$key][siteurl]);
			$info_array_7ree[DATA][$key][siteurl] = substr($info_array_7ree[DATA][$key][siteurl] , 0, -1);
			$info_array_7ree[DATA][$key][siteurl2] = cutstr($info_array_7ree[DATA][$key][siteurl],16,'');
			$info_array_7ree[DATA][$key][logo_7ree] = "http://addon.discuz.com/resource/plugin/".str_replace("plugin","png",$info_array_7ree[DATA][$key][ID]);
	}
		
	$all_download_count_7ree = 0;
	
	foreach ($app_array_7ree[DATA] as $key=>$value){
			$app_array_7ree[DATA][$key][lastupdate] = dgmdate($value[lastupdate],"Y-m-d");
			$all_download_count_7ree += $value[downloads];
	}
	$now_7ree =  dgmdate($_G['timestamp'],'Y-m-d H:i');	

	include template($pluginid_7ree.':more_7ree');

	
?>
