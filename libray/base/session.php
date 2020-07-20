<?php
/**
 * session 基础类
 *
 * @author wangxuewen <maxranje@aliyun.com>
 */

class Zy_Base_Session  {

    private static $instance    = null;

    private function __construct() {}

    public static function getInstance () {
        if (self::$instance === NULL) {
            session_start();
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getSessionUserInfo () {
        $userid = $this->getSessionUserId ();
        $name = $this->getSessionUserName ();
        $phone = $this->getSessionUserPhone ();

        if (empty($userid) || empty($name) || empty($phone)) {
            return [];
        }

        return [
            'userid' => $userid,
            'name'   => $name,
            'phone'  => $phone,
        ];
    }

    public function getSessionUserId () {
        return isset($_SESSION['userid']) ? $_SESSION['userid'] : '';
    }

    public function getSessionUserPhone () {
        return isset($_SESSION['phone']) ? $_SESSION['phone'] : '';
    }

    public function getSessionUserName () {
        return isset($_SESSION['name']) ? $_SESSION['name'] : '';
    }

    public function getSessionUserAvatar () {
        return isset($_SESSION['avatar']) ? $_SESSION['avatar'] : '';
    }

    public function getSessionUserVerify () {
        return isset($_SESSION['verify']) ? $_SESSION['verify'] : [];
    }

    public function setSessionUserName ($name) {
        $_SESSION['name'] = $name;
    }

    public function setSessionVerify ($verify) {
        $_SESSION['verify'] = $verify;
    }
}
