<?php
/**
 * session 基础类
 *
 * @author wangxuewen <maxranje@aliyun.com>
 */

class Zy_Core_Session  {

    private static $instance    = null;

    private function __construct() {}

    public static function getInstance () {
        if (self::$instance === NULL) {
            if (array_key_exists('zyuuid', $_COOKIE)) {
                $session_id = $_COOKIE['zyuuid'];
                session_id($session_id);
                session_start();
            }
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getSessionUserInfo () {
        if (session_status() == PHP_SESSION_NONE) {
            return [];
        }

        $userid = $this->getSessionUserId ();
        $name = $this->getSessionUserName ();
        $phone = $this->getSessionUserPhone ();
        $type = $this->getSessionUserType ();

        if (empty($userid) || empty($name) || empty($phone) || empty($type)) {
            return [];
        }

        return [
            'userid' => $userid,
            'name'   => $name,
            'phone'  => $phone,
            'type'   => $type,
        ];
    }

    public function setSessionUserInfo ($userid, $name, $phone, $type, $avatar = "") {
        if (empty($userid) || empty($name) || empty($phone) || empty($type)) {
            return false;
        }

        session_name('zyuuid');
        session_start();
        $session_id = session_id();
        $expire = time()+864000;
        setcookie('zyuuid', $session_id, $expire , "/");

        $this->setSessionUserId($userid);
        $this->setSessionUserName($name);
        $this->setSessionUserPhone($phone);
        $this->setSessionUserType($type);
        $this->setSessionUserAvatar($avatar);

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

    public function getSessionUserType () {
        return isset($_SESSION['type']) ? $_SESSION['type'] : '';
    }

    public function setSessionUserName ($name) {
        $_SESSION['name'] = $name;
    }

    public function setSessionUserId ($userid) {
        $_SESSION['userid'] = $userid;
    }

    public function setSessionUserPhone ($phone) {
        $_SESSION['phone'] = $phone;
    }

    public function setSessionUserAvatar ($avatar) {
        $_SESSION['avatar'] = $avatar;
    }

    public function setSessionUserType ($type) {
        $_SESSION['type'] = $type;
    }

    public function getSessionUserVerify () {
        return isset($_SESSION['verify']) ? $_SESSION['verify'] : [];
    }

    public function setSessionVerify ($verify) {
        $_SESSION['verify'] = $verify;
    }
}
