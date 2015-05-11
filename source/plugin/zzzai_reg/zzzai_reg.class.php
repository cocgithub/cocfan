<?php
if(!defined('IN_DISCUZ')) {
    exit('Access Denied');
}

class plugin_zzzai_reg {
    function common() {
        if(CURMODULE == 'register' && submitcheck('regsubmit') && (!isset($_POST['activationauth']) || (isset($_POST['activationauth']) && !$_POST['activationauth']))) {
            global $_G;
            $pvar = $_G['cache']['plugin']['zzzai_reg'];
            $akey = new zzzai_key('key');
            $k = $_G['clientip'];
            if(!isset($_G['gp_regkey'])) {
                $_G['gp_regkey'] = daddslashes($_GET['regkey']);
            }
            if($akey->checkkeydata($k, $_G['gp_regkey'], $pvar['errornum'])) {
                $akey->deletekey($k);
                $akey->savedata();
            } else {
                $akey->addnum($k);
                $akey->savedata();
                showmessage('zzzai_reg:keyerror');
            }
        }        
    }
}

class plugin_zzzai_reg_member extends plugin_zzzai_reg {    
    function register_top(){
        global $_G;
        $return = '';
        if(!submitcheck('regsubmit') && ($action = isset($_GET['action']) ? $_GET['action'] : '') <> 'activation') {
            $pvar = $_G['cache']['plugin']['zzzai_reg'];
            $akey = new zzzai_key('key', $pvar['errortime']);
            $k = $_G['clientip'];
                                  
            if(isset($_GET['regkey']) && !isset($_G['gp_regkey'])) {
                $_G['gp_regkey'] = daddslashes($_GET['regkey']);
            }
            
            if(isset($_G['gp_regkey'])) {
                if($akey->checkkeydata($k, $_G['gp_regkey'], $pvar['errornum'])) {
                    $akey->newdata($k);
                    $akey->savedata();
                    $return = '<input type="hidden" name="regkey" value="'.$akey->getdata($k).'">';
                } else {
                    $akey->addnum($k);
                    $akey->savedata();
                    showmessage($pvar['reselect'] ? 'zzzai_reg:keyerrorreselect' : 'zzzai_reg:keyerror', $pvar['reselect'] ? "member.php?mod={$_G[setting][regname]}" : 'forum.php', array(), array('showdialog' => true, 'locationtime' => true));
                }
            } else {
                $lefttime = $akey->checkenter($k, $pvar['errornum'], $pvar['errortime']);
                if( $lefttime !== true) showmessage('zzzai_reg:errormore', 'forum.php', array('lefttime'=>$lefttime), array('showdialog' => true, 'locationtime' => true));
                //create link string
                $css = '<style type="text/css">
                            .linknum {'.(($pvar['numcolor'] && $pvar['numcolor']<>'transparent')?'color: '.$pvar['numcolor'].';':'').'
                                        '.(($pvar['numfont'] == '3' || $pvar['numfont'] == '4')?'font-style: italic;':'').'
                                        '.(($pvar['numfont'] == '2' || $pvar['numfont'] == '4')?'font-weight: bold;':'').'
                            }
                        </style>';                
                $_v1 = mt_rand(5, 15);
                $_v2 = mt_rand(5, 15);
                switch($pvar['op']) {
                    case '+':
                        $_v3 = $_v1 + $_v2;
                        $op = '+';
                        break;
                    case '*':
                        $_v3 = $_v1 * $_v2;
                        $op = 'X';
                        break;
                    default:
                        $_v3 = $_v1 * $_v2;
                        $op = 'X';
                }
                $_t[$_v3] = $akey->getdata($k);
                for($i = 1; $i < $pvar['linknum']; $i++) {
                    $tv = mt_rand(1, 2 * $_v3);
                    while(array_key_exists($tv, $_t)){
                        $tv += mt_rand(1, 10);
                    }
                    $_t[$tv] = random(8);
                }
                ksort($_t);
                if (isset($_SERVER['REQUEST_URI'])){
                    $uri = $_SERVER['REQUEST_URI'];
                }else{
                    if (isset($_SERVER['argv'])){
                        $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['argv'][0];
                    }else{
                        $uri = $_SERVER['PHP_SELF'] .'?'. $_SERVER['QUERY_STRING'];
                    }
                }
                $exp = "<span class='linknum'>{$_v1} {$op} {$_v2} = ?</span>";
                $strlink = $css.($pvar['sanswer']?'':'<br>');
                foreach($_t as $k=>$v) {
                    switch($pvar['numinclude']) {
                        case '2':
                            $k = '['.$k.']';
                            break;
                        case '3':
                            $k = lang('plugin/zzzai_reg', 'lbrackets').$k.lang('plugin/zzzai_reg', 'rbrackets');
                            break;                    
                    }
                    if($pvar['sanswer']) {
                        $strlink .= "<a href=\"".$uri."&regkey={$v}\"><span class='linknum'>{$k}</span></a>";
                    } else {
                        $strlink .= ($pvar['linkprefix'] ? '<span>'.$exp.$pvar['linkprefix'].'</span>' : '')."<a href=\"".$uri."&regkey={$v}\"><span class='linknum'>{$k}</span>".$pvar['linktext']."</a><br>";
                    }                    
                }
                showmessage("zzzai_reg:msg".($pvar['sanswer']?'2':''),null,array('bbname'=>$_G['setting']['bbname'], 'exp'=>$exp,'link'=>$strlink), array('alert'=>'info'));
            }
        }
        return $return;
    }
}

class zzzai_key {
    private $_filename;
    private $_data;
    function zzzai_key($filename, $savetime=600) {
        global $_G;
        $this->_filename = 'zzzai_reg_'.$filename;
        loadcache($this->_filename);
        $this->_data = $_G['cache'][$this->_filename];
        if(is_array($this->_data) && count($this->_data) > 100) {
            foreach($this->_data as $k=>$item) {
                if(TIMESTAMP - $item['lasttime'] > intval($savetime)) unset($this->_data[$k]);
            }
        }
    }
    function newkey($key) {
        $this->_data[$key] = array('key'=>random(8), 'num'=>0, 'lasttime'=>TIMESTAMP);
    }
    function deletekey($key) {
        unset($this->_data[$key]);
    }
    function newdata($key) {
        if(isset($this->_data['$key'])) {
            $this->_data[$key]['key'] = random(8);
            $this->_data[$key]['lasttime'] = TIMESTAMP;
        }
    }
    function checkkeydata($key, $data, $errornum) {
        $return = false;
        if(isset($this->_data[$key]) && isset($this->_data[$key]['key']) && $this->_data[$key]['key'] == $data && $this->_data[$key]['num'] < $errornum) {
            $return = true;
        } else {
            logip();
        }
        return $return;
    }
    function getdata($key) {
        return $this->_data[$key]['key'];
    }
    function checkenter($key, $errornum, $errortime) {
        $return = true;
        if(!isset($this->_data[$key])) {
            $this->newkey($key);
            $this->savedata();
            return $return;
        }
        if(TIMESTAMP - $this->_data[$key]['lasttime'] > intval($errortime)) {
            $this->newkey($key);
            $this->savedata();
            $return = true;
        } elseif($this->_data[$key]['num'] >= intval($errornum)) {
            logip();
            $return = $errortime - (TIMESTAMP - $this->_data[$key]['lasttime']);
        }
        return $return;
    }
    function addnum($key) {
        if(isset($this->_data[$key])) {
            $this->_data[$key]['num']++;
            $this->_data[$key]['lasttime'] = TIMESTAMP;
        }
    }
    function savedata() {
        save_syscache($this->_filename, $this->_data);
    }
}

function logip() {
    global $_G;
    loadcache('zzzai_reg_log');
    $str = $_G['cache']['zzzai_reg_log'];
    if(strlen($str) > 260000) {
        $str_arr = explode('|', substr($str, 0, -1));
        $str_arr = array_splice($str_arr, -10000);
        $str = implode('|', $str_arr).'|';
    }
    save_syscache('zzzai_reg_log', $str.$_G['clientip'].','.TIMESTAMP.'|');
}