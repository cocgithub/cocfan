<?php
/**
 * 后台插件高级设置
 * @createtime 2013-03-19
 */

if (! defined ( 'IN_DISCUZ' ) || ! defined ( 'IN_ADMINCP' )) {
	exit ( 'Access Denied' );
}
define ( 'ALIYUNREC', dirname ( __FILE__ ) );
require_once ALIYUNREC . '/config/common.const.php';
require_once ALIYUNREC . '/table/table_common_plugin_aliyunrec.php';
require_once ALIYUNREC . '/table/json.class.php';
$AliyunrecDB        =       table_common_plugin_aliyunrec::getstance();
$aliyunrec_table    =       $AliyunrecDB -> getTable();
$Aliyunrec_info     =       $AliyunrecDB -> getInfo('select * from '.$aliyunrec_table.' where cnzz_id = 100');

if(!is_array($Aliyunrec_info)|| count($Aliyunrec_info)<1)
{
     showmessage('please try install again');
}
$username           =       $Aliyunrec_info['cnzz_username'];
$password           =       $Aliyunrec_info['cnzz_password'];
$sign               =       $password;
// 登录前会同步下用户推荐信息,防止提交不成功的情况

$updatedata         =       ALIYUNREC_TUI_DOMAIN.'?type=login&plugin_type='.ALIYUNREC_PLUGIN_TYPE.'&getdata=1&username='.$username.'&sign='.$sign.'&version='.ALIYUNREC_VERSION.'&time='.time();
$gettuidata         =       CJSON::decode(dfsockopen($updatedata),true);
if(is_array($gettuidata)){
    $aliyun_info    =       serialize($gettuidata['data']);
    $AliyunrecDB    ->      setInfo($AliyunrecDB -> table,array('cnzz_info'=>$aliyun_info),'cnzz_id = 100');  
    $AliyunrecDB    ->      saveSyscache('aliyun_info',$gettuidata['data']);//存入系统缓存
}
$state              =       1;
if(!in_array('aliyunrec',$_G['setting']['plugins']['available']))
$state              =       0;
$domain             =       $_G['siteurl'];
$tuidomain          =       ALIYUNREC_TUI_DOMAIN.'?type=login&plugin_type='.ALIYUNREC_PLUGIN_TYPE.'&username='.$username.'&sign='.$sign.'&state='.$state.'&domain='.urlencode($domain).'&version='.ALIYUNREC_VERSION.'&dz_ver='.$_G['setting']['version'].'&time='.time();
echo sprintf ( '<script>window.location.href="%s";</script>', $tuidomain );
?>
