<?php
/**
 * session 基础类
 *
 * @author wangxuewen <maxranje@aliyun.com>
 */

class Zy_Library_Sess_Files extends Zy_Session {

    public function __construct () {
        $this->session_zyuss = $this->getCookie('zyuss') ;
        if (!$this->sessionStatus()) {
            session_start();
        }
    }

    public function makeSession ($userInfo) {
        $zyuss = $this->bulidZyuss($userInfo['uid']);
        if (!$this->sessionStatus()) {
            session_start();
        }
        $this->setSessionArray($userInfo);
        $this->setCookie('zyuss', $zyuss, 7200);
    }

    public function setSessionInfo ($key , $val) {
        if ($key == false || $val == false) {
            return false;
        }
        $_SESSION[$key] = $val;
        return true;
    }

    public function setSessionArray ($arrParam) {
        if (!is_array($arrParam) || empty($arrParam) ) {
            return false;
        }
        foreach ($arrParam as $key => $val) {
            if (!is_array($val) && $val == false) {
                continue;
            }
            $_SESSION[$key] = $val;
        }
        return true;
    }

    public function getSessionInfo ($key) {
        if ($key == false) {
            return false;
        }
        return isset($_SESSION[$key]) ? $_SESSION[$key] : false;
    }

    public function sessionStatus () {
        return session_status() == PHP_SESSION_ACTIVE;
    }

    public function unsetSessionInfo ($key)  {
        if ( $key == false ) {
            return false;
        }
        unset ($_SESSION[$key]);
    }

    public function destroySession() {
        return session_destroy();
    }

    public function getCookie ($key) {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
    }

    public function setCookie($key, $value, $time=7200) {
        return setcookie($key, $value, $time);
    }
}
