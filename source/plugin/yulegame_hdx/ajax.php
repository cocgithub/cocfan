<?php

/* ===============================================================
 * @�������			�ڵ�����X
 * @�����Ȩ			2007-2011 ������Ϸ.NET www.yulegame.net
 * @�������			Ricky Lee (ricky_yahoo@hotmail.com)
 * ******** ���������ߵ��Ͷ��ɹ�, �������ϰ�Ȩ��Ϣ *********************
 * ******** ��վ�����ڸ������������, �������Ҫ���������QQ 231753
 * *** ����EMAIL: ricky_yahoo@hotmail.com
 * *** ���߷���: http://bbs.yulegame.net ������̳����Ϣ�� ricky_yahoo

 * *** ����Ϊ<������Ϸ��>��Ʒ��������Ʒ���(�뵽��̳�������ð�):
 * 1: �ڵ����� 
 * 2: ��Ϸ���� 
 * 3: �²��� 
 * 5: ���ִ��� 
 * *** ��л��Ա�վ�����֧�ֺͺ�!
 * *** <������Ϸ��> - ��������Ŷ�
 * ================================================================
 */

// ����ʹ�ô��жϱ����ⲿ����
if (!defined('IN_DISCUZ')) {
    exit('Access Denied');
}




switch ($subop) {
    case 'rob':
        require PLUGIN_PATH . '/ajax/rob.inc.php';
        break;
    case 'msg':
        require PLUGIN_PATH . '/ajax/msg.inc.php';
        break;
    case 'jail':
        require PLUGIN_PATH . '/ajax/jail.inc.php';
        break;
    case 'mybag':
        require PLUGIN_PATH . '/ajax/mybag.inc.php';
    case 'shop':
        require PLUGIN_PATH . '/ajax/shop.inc.php';
        break;
    case 'yule':
    case 'gift':
        require PLUGIN_PATH . '/ajax/yule.inc.php';
        break;
    case 'guard':
        require PLUGIN_PATH . '/ajax/guard.inc.php';
        break;
    case 'away':
        require PLUGIN_PATH . '/ajax/away.inc.php';
        break;
    case 'quit':
        require PLUGIN_PATH . '/ajax/quit.inc.php';
        break;
    case 'setting':
        require PLUGIN_PATH . '/ajax/setting.inc.php';
        break;
    default:
        showError('Invalid Action!');
}
?>
