<?php

class Zy_ExceptionCode {

    /********* 系统错误号 *********/
    const SYSTEM_CRAZY              = 9;            # 系统异常
    const SYSTEM_CONFIG_EMPTY       = 1001;         # 系统 config 无法获取


    /********* 登录错误号 *********/
    const LOGIN_INFO_EMPTY          = 2001;         # 用户名密码为空
    const LOGIN_TOKEN_ERR           = 2002;         # 验证错误,TOKEN
    const LOGIN_USERINFO_ERR        = 2003;         # 用户名密码错误


    public static $errMsg = array(
        self::SYSTEM_CRAZY                  => "系统异常~",
        self::SYSTEM_CONFIG_EMPTY           => "系统错误~",
        self::LOGIN_INFO_EMPTY              => "用户名密码不可以为空~",
        self::LOGIN_TOKEN_ERR               => "验证错误, 请重新登录~",
        self::LOGIN_USERINFO_ERR            => "用户名或密码错误~",
    );

    public static function getErrMsg($errno)
    {
        if (isset(self::$errMsg[$errno])) {
            return self::$errMsg[$errno];
        }
        return '未知错误';
    }
}