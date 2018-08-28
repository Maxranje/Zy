<?php
/**
 * session 和 cookie类
 */
class Zy_Library_Session
{
    private static $instance = NULL;

    private function __construct()
    {
        if ( isset($_COOKIE['zyuss']) && !empty($_COOKIE['zyuss']) )
        {
            $_session_id = trim($_COOKIE['zyuss']);
            session_id($_session_id);
        }
        if (session_status() !== PHP_SESSION_ACTIVE)
        {
            session_start();
        }
    }

    public static function getInstance ()
    {
        if (self::$instance === NULL)
        {
            self::$instance = new Zy_Library_Session ();
        }
        return self::$instance;
    }

    public function get($key)
    {
        if (isset($_SESSION[$key]))
        {
            return $_SESSION[$key];
        }
        elseif ($key === 'session_id')
        {
            return session_id();
        }

        return NULL;
    }

    public function set($key, $value)
    {
        $_SESSION[$key] = $value;
    }

    public function isset($key)
    {
        if ($key === 'session_id')
        {
            return (session_status() === PHP_SESSION_ACTIVE);
        }

        return isset($_SESSION[$key]);
    }

    public function unset ($key)
    {
        if (isset ($_SESSION[$key]))
        {
            unset($_SESSION[$key]);
        }
        return ;
    }


    public function sessionRegenerate($destroy = FALSE)
    {
        $_SESSION['zy_last_regenerate'] = time();
        session_regenerate_id($destroy);
    }

    public function sessionUnset($key)
    {
        unset($_SESSION[$key]);
    }

    public function sessionDestroy()
    {
        session_destroy();
    }

    public function hasInfo ()
    {
        return isset($_SESSION['uid']);
    }

    // 获取COOKIE中内容
    public function getcookie ($key)
    {
        return $_COOKIE[$key];
    }
}
