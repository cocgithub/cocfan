<?php
/**      删除文件
 **      [Discuz!] (C)2001-2099 Comsenz Inc.
 **      This is NOT a freeware, use is subject to license terms
 **      $Id: uninstall.php createtime 2013-03-26 by aliyunrec $
 **/

if(!defined('IN_DISCUZ')) {
        exit('Access Denied');
}

$sql = <<<EOF
DROP TABLE pre_common_plugin_aliyunrec;
EOF;

//runquery($sql);
 
$finish = TRUE;
?>
