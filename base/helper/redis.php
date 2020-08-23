<?php

/**
 * Redis连接工具类
 *
 */
class Zy_Helper_Redis  {

    // 简化固化REDIS连接方式

    # redis 服务器HOST地址
    private $redis_host         = '127.0.0.1';

    # redis 服务器PORT
    private $redis_port         = 16379;

    # redis instance
    private $redis_instance     = NULL;

    public function __construct() {
        $this->redis_instance   = new Redis ();
        $this->redis_instance->connect();
    }

}