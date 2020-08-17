
<?php

class Zy_Helper_Guid {

    public static $valueBeforeMD5;
    public static $valueAfterMD5;

    public static function toString() {
        self::$valueBeforeMD5 = self::netAddress() . ':' . self::currentTimeMillis() . ':' . self::nextLong();
        self::$valueAfterMD5 = md5(self::$valueBeforeMD5);
        $raw =strtolower(self::$valueAfterMD5);
        return substr($raw, 0, 8) . substr($raw, 8, 4) . substr($raw, 12, 4) . substr($raw, 16, 4)  . substr($raw, 20);
    }

    public static function netAddress() {
        return empty($_SERVER["SERVER_ADDR"]) ? '127.0.0.1' : strtolower('/' . $_SERVER["SERVER_ADDR"]);
    }

    private static function currentTimeMillis() {
        return microtime();
    }

    public static  function nextLong() {
        $tmp = mt_rand(0, 1) ? '-' : '';
        return $tmp . mt_rand(1000, 9999) . mt_rand(1000, 9999) . mt_rand(1000, 9999) . mt_rand(100, 999) . mt_rand(100, 999);
    }

    public static function traceId($uid) {
        list($usec, $sec) = explode(" ", microtime());
        return $uid . $sec . intval($usec * 1000000) . rand(100000, 999999);
    }

}