<?php

class Zy_ExceptionCode {

    /********* 系统错误号 *********/
    const PARAM_ERROR               = 2;         # 请求参数错误
    const SYSTEM_CRAZY              = 9;         # 系统异常
    const SYSTEM_CONFIG_EMPTY       = 10;        # 系统 config 无法获取
    const TPL_NOT_EXIST             = 101;       # 查询的模板不存在
    const LOGIN_INFO_EMPTY          = 102;       # 用户名密码为空
    const LOGIN_TOKEN_ERR           = 103;       # 验证错误,TOKEN
    const LOGIN_USERINFO_ERR        = 104;       # 用户名密码错误
    const PHONE_REGIST_ERR          = 105;       # 手机号已经注册


    public static $errMsg = array(
        self::PARAM_ERROR                   => "请求参数错误~",
        self::SYSTEM_CRAZY                  => "系统异常~",
        self::SYSTEM_CONFIG_EMPTY           => "系统错误~",
        self::LOGIN_INFO_EMPTY              => "用户名密码不可以为空~",
        self::LOGIN_TOKEN_ERR               => "验证错误, 请重新登录~",
        self::LOGIN_USERINFO_ERR            => "用户名或密码错误~",
        self::TPL_NOT_EXIST                 => "请求失败, 数据不存在~",
        self::PHONE_REGIST_ERR              => "手机号已经他人注册~",
    );

    public static function getErrMsg($errno)
    {
        if (isset(self::$errMsg[$errno])) {
            return self::$errMsg[$errno];
        }
        return '未知错误';
    }
}