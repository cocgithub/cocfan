<?php
// ����ʹ�ô��жϱ����ⲿ����
if (! defined('IN_PLUGIN')) {
	exit('Access Denied');
}




// ��������
$crimsCount = DB::result_first('SELECT COUNT(*) FROM '. DB::table('hdx_player') .' WHERE available = 1 AND out_jail_time > '. $_timenow);

// ��Ϣ����
$msgCount = DB::result_first('SELECT COUNT(*) FROM '. DB::table('hdx_msg') .' WHERE to_uid = '. $_uid);



?>