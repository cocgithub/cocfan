<?php

	/*
	MZG����֧��С��
	���� fjyxian
	�ͷ���ѯ������QQ:1063790899
	��������QQ:51353835(ֻ�ṩ�г�����֧��)
	*/
	if (!defined('IN_DISCUZ') || !defined('IN_ADMINCP')) {
			exit('Access Denied');
	}

	if (submitcheck('savepost', 1)) {
			$name = trim($_POST['name']);
			$url = trim($_POST['url']);
			$did = intval($_POST['did']);
			if (!$name) cpmsg("���Ʋ���Ϊ��");
			if (!strstr($url, 'http://')) cpmsg("��������Ч��ַ");
			$pic = trim($_POST['url_pic']);
			if (is_uploaded_file($_FILES['up_pic']['tmp_name']) and !$_FILES['up_pic']['error']) {
			     $picarr = explode(".",$_FILES['up_pic']['name']);
                 $newpic = $pic?$pic:'source/plugin/mzg_advertise/logo/'.$_G['timestamp'].'.'.$picarr[count($picarr)-1];
					if (move_uploaded_file($_FILES['up_pic']['tmp_name'],$newpic)) {
							$pic = $newpic;
					} else {
							cpmsg("ͼƬ�ϴ�����", $_POST['resturl']);
					}
			}
			$sqlarr = array('name' => daddslashes(dhtmlspecialchars($name)), 'url' =>
					daddslashes(dhtmlspecialchars($url)), 'pic' => daddslashes(dhtmlspecialchars($pic)), 'method' => intval($_POST['method']),
					'maxcount' => intval($_POST['maxcount']), 'price' => intval($_POST['price']),
					'price_type' => intval($_POST['price_type']), 'status' => intval($_POST['status']),
					'stime' => ($_POST['stime'] ? strtotime($_POST['stime']) : 0), 'etime' => ($_POST['etime'] ?
					strtotime($_POST['etime']) : 0));
			if ($sqlarr['etime'] > 0 and $sqlarr['etime'] < $sqlarr['stime']) $sqlarr['etime'] =
							0;
			if (!empty($did)) {

					DB::update('plugin_advertise', $sqlarr, array('did' => $did));
			} else {
					$sqlarr['posttime'] = $_G['timestamp'];
					DB::insert('plugin_advertise', $sqlarr);

			}
			cpmsg("���ݱ���ɹ�", $_POST['resturl']);
	} elseif (submitcheck('savetopid', 1)) {
			if ($_POST['topid']) {
					foreach ($_POST['topid'] as $did => $topid) {
					   $did = intval($did);
                       $topid = intval($topid);
							DB::update('plugin_advertise', array('topid' => $topid), array('did' => $did));
					}
			}
			cpmsg("���򱣴�ɹ�", $_POST['resturl']);
	} elseif (submitcheck('deldata', 1)) {
	           $dids =array();
					foreach ($_POST['userbox'] as $did) {
					   $dids[] = intval($did);
					}
			$dids = dimplode($dids);
			if ($dids) {
					$query = DB::query("SELECT pic FROM " . DB::table('plugin_advertise') .
							" WHERE did IN($dids)");
					while ($value = DB::fetch($query)) {
							@unlink($value['pic'], null);
					}
					DB::query("DELETE FROM " . DB::table('plugin_advertise') . " WHERE did IN($dids)");
			}
			cpmsg("�ɹ�ɾ�� <strong>" . count($_POST['userbox']) . "</strong> ������.", $rurl .
					"&mod=adview");


	} elseif (submitcheck('dellog', 1)) {
	           $dids =array();
					foreach ($_POST['userbox'] as $did) {
					   $dids[] = intval($did);
					}
			$dids = dimplode($dids);
			if ($dids) {
					DB::query("DELETE FROM " . DB::table('plugin_advertise_log') .
							" WHERE lid IN($dids)");
			}

			cpmsg("�ɹ�ɾ�� <strong>" . count($_POST['userbox']) . "</strong> ������.", $_POST['resturl']);
	}

?>