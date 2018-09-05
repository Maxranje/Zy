<?php
/**
 * session 基础类
 *
 * @author wangxuewen <maxranje@aliyun.com>
 */

class Zy_Library_Sess_Files extends Zy_Session {

    private function __construct () {
        $this->session_zyuss = $this->getCookie('zyuss') ;
        if ($this->session_zyuss !== null) {
            session_name('zyuss');
            session_id($this->session_zyuss);
        }
    }

    public function makeSession ($userInfo) {

    }

    public function setSessionInfo ($key , $val) ;

    public function setSessionArray ($arrParam) ;

    public function getSessionInfo ($key) ;

    public function sessionStatus ($key);

    public function unsetSessionInfo ($key) ;

    public function destroySession() ;

    public function getCookie ($key) ;

    public function getCookie ($key) {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
    }

    public function setCookie($key, $value, $time=7200) {
        return setcookie($key, $value, $time)
    }
}
