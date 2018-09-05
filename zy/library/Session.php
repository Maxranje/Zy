<?php
/**
 * session 和 cookie类
 *
 * @author wangxuewen <maxranje@aliyun.com>
 */

class Zy_Library_Session  {

    private static $session_instance   = null;



    private $session_zyuss      = null;

    /**
     * cookie 中 zyuss 作为session id, 获取该值重新获取session内容
     * 如果未登录没有cookie情况下, 或session_id启动错误, 直接跳过
     */
    private function __construct () {
        $this->session_zyuss = $this->getCookie('zyuss') ;
        if ($this->session_zyuss !== null) {

            session_id($this->session_zyuss);
        }
    }

    /**
     * 开启session, 自动创建session_id, 该值可应用的Redis中, 并在session中填充用户信息
     * @param  [type] $userInfo     [用户信息]
     * @return [type]               [void]
     */
    public function makeSession ($userInfo)  {
        $uid    = intval($userInfo['uid']);
        $str    = md5($uid.time());
        $this->session_zyuss  = Zy_Library_StrCrypt::encodeStr($str);

        // start session
        $this->resetSession($this->session_zyuss);
        if ( session_start() === false) {
            trigger_error ('[Error] session error, [Detail] can not start session ');
        }
        $this->sessionSetArray ($userInfo);
    }

    /**
     * 添加单个session key val 键值对
     * @param  [mix] $key          键
     * @param  [mix] $val          值
     * @return [bool]              结果
     */
    public function sessionSet ($key , $val) {
        if ($key == false || $val == false) {
            return false;
        }
        $ret = $this->sessionSetArray (array($key => $val));
        return $ret  == false ? false : true;
    }

    /**
     * 添加多个session key val 键值对
     * @param  [array] $arrParam         键值对数组
     * @return [bool]                    结果
     */
    public function sessionSetArray ($arrParam) {
        if (empty($arrParam)) {
            return false;
        }
        foreach ($arrParam as $key => $val) {
            if ($key == false || $val == false) {
                continue;
            }
            $_SESSION[$key] = $val;
        }
        return true;
    }

    // 获取session值
    public function getSession ($key) {
        if ($key === "session_id") {
            return $this->session_zyuss;
        }
        return isset($_SESSION[$key]) ? $_SESSION[$key] : null;
    }

    // 判断session是否处于激活状态
    public function hasSession () {
        return (session_status() === PHP_SESSION_ACTIVE);
    }

    // 删除session某个键值对
    public function sessionUnset($key) {
        unset($_SESSION[$key]);
    }

    public static function getInstance () {
        if (self::$session_instance === NULL) {
            self::$session_instance = new Zy_Library_Session ();
        }
        return self::$session_instance;
    }

    // 删除session, 这么做后建议重新make
    public function sessionDestroy() {
        session_destroy();
    }

    public function getCookie ($key) {
        return isset($_COOKIE[$key]) ? $_COOKIE[$key] : null;
    }

}
