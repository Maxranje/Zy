<?php
/**
 * Action层的基础父类, 根据请求处理是否是Ajax 请求, 通常直接在页面中直接通过tpl返回
 *
 */

abstract class Zy_BaseAction
{

    // _GET 或 _POST
    protected $_requestParam = array();

    // _SERVER
    protected $_publicParam = array();

    // user 个人信息
    protected $_userInfo = array
    (
        "isLogin" => 0,
        "uname"   => "",
        'avatar'  => "",
        "uid"     => 0,
    );

    // response
    protected $_tplData = array
    (
        'errNo' => 0,
        'errStr' => 'success',
        'data' => array(),
    );

    // ------------------------------

    abstract protected function invoke();

    // 初始化Action所需要内容
    public function _init ()
    {
        $this->_before();
        try
        {
            $res = $this->invoke();
            $this->_tplData['data'] = is_array($res) ? $res : array($res);
        }
        catch (Exception $ec)
        {
            $this->_tplData['errNo']  = $ec->getCode ();
            $this->_tplData['errStr'] = $ec->getMessage ();
        }

        $this->_after();
    }

    protected function _before ()
    {
        // 构造请求参数
        $_GET   = !empty($_GET)  && is_array($_GET)  ? $_GET  : array();
        $_POST  = !empty($_POST) && is_array($_POST) ? $_POST : array();

        $this->_requestParam = array_merge ($_GET, $_POST) ;

        if ( !empty($this->_requestParam ))
        {
            foreach ($this->_requestParam as $key => $value)
            {
                if ( is_numeric( $key ) )
                {
                    unset ($this->_requestParam[$key]);
                }
            }
        }

        $this->_publicParam = empty($_SERVER) ? array() : $_SERVER ;

        // session中有用户信息,  获取用户信息
        $objSession = Zy_Library_Session::getInstance();
        if ( $objSession !== NULL && $objSession->hasInfo() )
        {
            $this->_userInfo['uid']     = $objSession->get('uid');
            $this->_userInfo['uname']   = $objSession->get('uname');
            $this->_userInfo['avatar']  = $objSession->get('avatar');
            $this->_userInfo['role']    = $objSession->get('role');
            $this->_userInfo['for']    = $objSession->get('role');

            if ($this->_userInfo['uid'] > 0)
            {
                $this->_userInfo['isLogin'] = 1;
            }
        }
    }

    // 结果内容处理
    protected function _after () {
        $this->_output = new Zy_Output ();
        $this->_output->display($this->_tplData);
    }
}
