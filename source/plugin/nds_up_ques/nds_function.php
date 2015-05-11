<?PHP
/*  nds_function v1.0
 *  Plugin FOR Discuz! X 
 *	WWW.NWDS.CN | NDS.西域数码工作室  版权保护 请勿抄袭
 *  Plugin update 20130816 BY singcee
 */
function ndsmktime($time) {
	if ($time == 0)
		return 1;
	if (preg_match ( "/[0-9]{4}(\-)[0-9]{1,2}(\\1)[0-9]{1,2}(|\s+[0-9]{1,2}(|:[0-9]{1,2}))/", $time )) {
		$time2 = explode ( ' ', $time );
		$isdata = explode ( '-', $time2 [0] );
		$istime = explode ( ':', $time2 [1] );
		return mktime ( $istime [0], $istime [1], 0, $isdata [1], $isdata [2], $isdata [0] );
	}
	return 0;
}
?>