<?php
/**
 * Action层的TPL页的父类, 如果页面继承该类, 则表明该ACTION为调用页面请求
 * 重新定义_init写法, 所有错误全部冲定向到错误页面
 *
 */

abstract class Zy_BaseWebAction extends Zy_BaseAction
{
    // 重新定义 init 方法
    public function _init ()
    {
        $this->_before();
        $this->invoke();
    }
}
