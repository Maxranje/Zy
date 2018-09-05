<?php
/**
 * session 基础类
 *
 * @author wangxuewen <maxranje@aliyun.com>
 */

abstract class Zy_Session  {

    private static $instance   = null;

    private $_driver = null;

    private $_config = null;

    private function __construct () {
        $this->_config = [
            'sess_driver'       => Zy_Config::getConfig("sess_driver"),
            'sess_cookie_name'  => Zy_Config::getConfig("sess_cookie_name"),
            'sess_expiration'   => Zy_Config::getConfig("sess_expiration"),
        ];
        $sess_class_name = "Zy_Library_Sess_" . ucfirst($this->_config['sess_driver']);
        $this->_driver = new $sess_class_name ($this->_config;
    }

    public static function getInstance () {
        if (self::$instance === NULL) {
            self::$instance = new Zy_Session ();
        }
        return self::$instance->_driver;
    }


    abstract public function makeSession ($userInfo) ;

    abstract public function setSessionInfo ($key , $val) ;

    abstract public function setSessionArray ($arrParam) ;

    abstract public function getSessionInfo ($key) ;

    abstract public function sessionStatus ($key);

    abstract public function unsetSessionInfo ($key) ;

    abstract public function destroySession() ;

    abstract public function getCookie ($key) ;

    public function getCookie ($key) {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
    }

    public function setCookie($key, $value, $time=7200) {
        return setcookie($key, $value, $time)
    }
}
