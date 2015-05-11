<?php
/**
 ** 升级文件
 ** @createtime 2013-03-19
 **/
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}
define ( 'ALIYUNREC', dirname ( __FILE__ ) );
require_once ALIYUNREC . '/common/aliyunrec.common.proxy.service.php';
require_once ALIYUNREC . '/table/json.class.php';
$sql = <<<EOF

CREATE TABLE IF NOT EXISTS pre_common_plugin_aliyunrec (
  `cnzz_id` mediumint(8) unsigned NOT NULL default '0',
  `cnzz_username` char(40) NOT NULL default '',
  `cnzz_password` char(40) NOT NULL default '',
  `cnzz_info` text ,
  PRIMARY KEY  (`cnzz_id`)
) ENGINE=MyISAM;
EOF;

runquery($sql);


$AliyunrecDB        =       table_common_plugin_aliyunrec::getstance();
$aliyunrec_table    =       $AliyunrecDB -> getTable();
$domain             =       $_G['siteurl'];
$Aliyunrec_info     =       $AliyunrecDB -> getInfo('select * from '.$aliyunrec_table.' where cnzz_id = 100');
$applicationIds     =       AliyunRec_Common_Proxy_Service::getOldApplicationIds();
$state              =       1;
if(!in_array('aliyunrec',$_G['setting']['plugins']['available']))
$state              =       0;
$url                =       ALIYUNREC_TUI_DOMAIN.'?type=register&plugin_type='.ALIYUNREC_PLUGIN_TYPE.'&from_type='.ALIYUNREC_FROM_TYPE.'&domain='.urlencode($domain).'&ridarray='.CJSON::encode($applicationIds).'&state='.$state.'&time='.time();
if(!is_array($Aliyunrec_info)||count($Aliyunrec_info)<1){//数据库不存在
    $data       =       CJSON::decode(dfsockopen($url),true);
    if(is_array($data)){
        $infoData                 =   $data['data'];
        $AliyunrecTABledata       =   array('cnzz_id'=>100,'cnzz_username'=>$data['username'],'cnzz_password'=>$data['password'],'cnzz_info'=>serialize($infoData));
        $AliyunrecDB -> insertInfo($AliyunrecTABledata);//存入数据库 
        $AliyunrecDB -> saveSyscache('aliyun_info',$infoData);//存入系统缓存
        if( !$AliyunrecDB -> delVstate()){//删除标记
            showmessage('操作数据库失败');   
        }  
    }
}

$finish = true;
