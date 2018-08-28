<?php

class Zy_ExceptionCode {

    /**    system error code  **/
    const SYSTEM_CONFIG_EMPTY   =  1001;        # 系统 config 无法获取

    public static $errMsg = array(
        self::SYSTEM_CONFIG_EMPTY          => "系统错误~",
    );

    public static function getErrMsg($errno)
    {
        if (isset(self::$errMsg[$errno])) {
            return self::$errMsg[$errno];
        }

        return '未知错误';
    }
}