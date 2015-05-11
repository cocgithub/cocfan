<?php
/**
 *用户更新 获取板块 接口
 *create time 2013-03-21 by aliyunrec
 */
/*返回错误编码
 *000001 验证失败
 *000002 获取的数据格式错误
 *000003 数据库初始化失败
 */

if (! defined ( 'IN_DISCUZ' )) {
    exit ( 'Access Denied' );
}

define ( 'ALIYUNREC', dirname ( __FILE__ ) );
require_once ALIYUNREC . '/table/table_common_plugin_aliyunrec.php';
require_once ALIYUNREC . '/table/json.class.php';
require_once ALIYUNREC . '/config/common.const.php';
class Aliyunrec_api {
    private $AliyunrecDB;
    private $aliyunrec_table;
    private $Aliyunrec_info;
    private $get;
    private $error = '';
    private $data_info;
    private $fid_info  =  array();
    private $whichtype =  '';
    /**
     *@ 初始化获得table名称，table信息，检验通信
     */
      function Aliyunrec_api(){
         $this  ->   get              =     $_GET;
         $this  ->   AliyunrecDB      =     table_common_plugin_aliyunrec::getstance();
         $this  ->   aliyunrec_table  =     $this  ->  AliyunrecDB -> getTable();
         $this  ->   Aliyunrec_info   =     $this  ->  AliyunrecDB ->  getInfo('select * from '.$this -> aliyunrec_table.' where cnzz_id = 100');
         $this  ->   checkSign();
      }

    /**
     * 更新数据库信息 更新缓存信息
     * return Boolean;
     */
      function updateInfo(){
		  $username           =       $this -> Aliyunrec_info['cnzz_username'];
		  $password           =       $this -> Aliyunrec_info['cnzz_password'];
		  $sign               =       $password;
		  $updatedata         =       ALIYUNREC_TUI_DOMAIN.'?type=login&plugin_type='.ALIYUNREC_PLUGIN_TYPE.'&getdata=1&username='.$username.'&sign='.$sign.'&version='.ALIYUNREC_VERSION.'&time='.time();
		  $gettuidata      =        CJSON::decode(dfsockopen($updatedata),true);
		  if(is_array($gettuidata)){
			  $aliyun_info    =       serialize($gettuidata['data']);
			  $this ->  AliyunrecDB     ->      setInfo($this ->  AliyunrecDB -> table,array('cnzz_info'=>$aliyun_info),'cnzz_id = 100');  
			  $this ->  AliyunrecDB     ->      saveSyscache('aliyun_info',$gettuidata['data']);//存入系统缓存
		  }
		  return true; 
      }

      /**
       * 检测登录 $_GET['sign'] == md5(password)
       * @ return Boolean;
       */
      function checkSign(){
         if(md5($this -> Aliyunrec_info['cnzz_password'])!= $this -> get['sign']){
            $this  ->  error   =     "0000011";
         }
      }


     
     /**
      *返回错误信息
      *return string
      */

      function returnApiData(){
          if($this  ->  error)
              return $this  ->   error;
         return $this -> returnFidInfo(); 
      }

      /**
       *返回板块信息
       *@return array
       */
      function returnFidInfo(){
        return CJSON::encode($this -> fid_info);
      }

     /**
      *通过a_type实现不同功能
      *@ return Boolean
      */
      function whichType($type){
          $this    -> whichtype  =   $type;
          if($this -> error){
                return false;
          }
          if($type  ==  'up' ){
              if(! $this -> updateInfo() )
                  return false;
              return true;
          }
          elseif($type  ==  'getfid'){
              global $_G; 
              loadcache('forums');
              $dzforums   =   $_G['cache']['forums'];
              foreach($dzforums as $forums){
                  $this  ->  fid_info[]   =   array('fid' => $forums['fid'] ,'type'=>$forums['type'], 'name' => $forums['name'],'fup'=>$forums['fup'] );  
              }
              return false;
          }
      }
}



$aliyunapi        =    new Aliyunrec_api;
if(!$aliyunapi    ->   whichType($_GET['a_type']))
    {
       exit($aliyunapi    -> returnApiData());
    }
echo 'ok';
?>