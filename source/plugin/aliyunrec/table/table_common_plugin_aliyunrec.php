<?php
/**
 *      [Discuz!] (C)2001-2099 Comsenz Inc.
 *      This is NOT a freeware, use is subject to license terms
 *
 *      $Id: table_commmon_plugin_aliyunrec.php  2013-03-19 aliyunrec $
 */     
 
if(!defined('IN_DISCUZ')) {
        exit('Access Denied');
}
! defined ( 'ALIYUNREC' ) && exit ( 'Forbidden' );
class table_common_plugin_aliyunrec 
    {
        public $table  =  'common_plugin_aliyunrec';
        static private $_instance; 
        private $cacheInfo ='';    
        public static function getstance(){
            if( false == (self::$_instance instanceof self) ){
                self::$_instance = new self();
            }
            return self::$_instance;
        }

        /**
         *获取数据库内存放的info
         *@return array
         */
        public function getInfo($sql){
            return DB::fetch_first($sql);
        }

        /**
         *获取表名
         *@return array
         */
        public function getTable($table=''){
            if(!$table){
                $table  =   $this    ->   table;    
            }
            $therealtable   =    DB::table($table);
            if(!$this  ->  tableIsExists($therealtable)){
                showmessage("数据表".$table."不存在");
                exit;
            }
            return $therealtable;
        }
        
        /**
         *表是否存在
         *@param $table 表名
         *return bool
         */
       public function tableIsExists($table){
            if(!is_array($this -> getInfo("SHOW TABLES LIKE '".$table."'"))){
                return false;
            }
            return true;
        }

        /*
         *update info
         *
         */
        public function setInfo($table,$data,$condition){
            return DB::update($table,$data,$condition);
        }
        /*
         *插入初始化信息
         */    
        public function insertInfo($data){
            return DB::insert($this->table,$data);
        }

        /*
         *存入系统缓存
         *$cname = aliyun_info
         */
        public function saveSyscache($cname,$data){
            return  save_syscache($cname,$data); 
        }

        /*
         *读取缓存信息
         *$cname = aliyun_info
         */
        public function load_cache($cname){
            global $_G;    
            loadcache($cname);
            return $_G['cache'][$cname];
          //$aliyun_info  =   cachedata($cname);//2.5 没有
          //return $aliyun_info[$cname];

        }
        
        /**
         *执行sql
         */

        public function doQuery($sql){
            return DB::query($sql);
        }
        /**
         *获取错误代码
         *return int
         */
        public function getErrorno(){
            return DB::errno();
        }
        /**
         *获取info信息
         *分别从类的变量，系统缓存，数据库依次获取
         *return array
         */
        public function getIdInfo(){
            if($this->cacheInfo){
                return  $this->cacheInfo; 
            }
            $IdInfo               =     $this->load_cache('aliyun_info'); 
            if(!is_array($IdInfo)){
                $therealtable     =    DB::table($this -> table);
                if($this   ->   tableIsExists($therealtable)){
                    $IdInfodata   =    $this->getInfo('select * from '.$this->getTable().' where cnzz_id = 100');
                    if(!is_array($IdInfodata))
                    return '';
                    $IdInfo       =   unserialize( $IdInfodata['cnzz_info']);
                }
                else{
                        $IdInfo         = array();
                        $applicationIds = AliyunRec_Common_Config_Service::getConfig ( 'common.application' );
                        $Fixedarray     = AliyunRec_Common_General_Service::buildFixedIds($applicationIds);
                        $Floatarray     = AliyunRec_Common_General_Service::buildFloatIds($applicationIds);
                        foreach($Fixedarray as $f){
                            if($f){
                                $IdInfo[]  =  array('r_id'=>$f,'r_type'=>'1','r_mid'=>'3','r_pid'=>'5','r_fid'=>'-1','r_status'=>'1');
                            } 
                        } 
                   
                        foreach($Floatarray as $f){
                            if($f){
                                $IdInfo[]  =  array('r_id'=>"$f",'r_type'=>'2','r_mid'=>'3','r_pid'=>'1','r_fid'=>'-1','r_status'=>'1');
                            } 
                        }
                }
                $this             ->   saveSyscache('aliyun_info',$IdInfo);
            }
            $this->cacheInfo      =    $IdInfo;
            return $IdInfo;
        }
        /**
         *删除v1.0开关
         *return bool
         */
        public function delVstate(){
            $aliyunrec_pluginid       =   $this -> getPluginId();
            if(! $this  ->  delPluginVar($aliyunrec_pluginid))
                return false;
            if(! $this  ->  delPluginCacheVar())
                return false;
            return true;
        }
        /**
         *返回插件id
         *@return int;
         */
        public function getPluginId(){
            $common_plugin_table      =   $this -> getTable('common_plugin');
            $aliyunrec_plugin_array   =   $this -> getInfo('select * from '.$common_plugin_table.' where identifier = \'aliyunrec\'');
            $aliyunrec_pluginid       =   $aliyunrec_plugin_array['pluginid'];
            return $aliyunrec_pluginid;
        }
        /**
         *删除插件变量
         *return bool
         */
        public function delPluginVar($aliyunrec_pluginid){
            $common_pluginvar_table   =   $this -> getTable('common_pluginvar');       
            $this -> doQuery("delete from $common_pluginvar_table where pluginid = $aliyunrec_pluginid");//删除v1.0开关
            if($this  ->   getErrorno()){
                return false;
            }
            return true;    
        }

        /**
         *删除缓存中的插件变量
         *@return bool
         */
        public function delPluginCacheVar(){
            $common_syscache_table    =   $this -> getTable('common_syscache');
            $syscache_plugin_array    =   $this -> getInfo('select * from '.$common_syscache_table.' where cname = \'plugin\'');
            $syscache_plugin_data     =   unserialize($syscache_plugin_array['data']);
            unset($syscache_plugin_data['aliyunrec']);
            $syscache_plugin_data     =   serialize($syscache_plugin_data);
            $this     ->   setInfo('common_syscache',array('data'=>$syscache_plugin_data),"cname ='plugin'");
            if($this  ->   getErrorno()){
                return false;
            }    
            return true;
        }
    }

?>
