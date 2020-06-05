<?php
/**
 * Action层的基础父类, 根据请求处理是否是Ajax 请求, 通常直接在页面中直接通过tpl返回
 *
 */

abstract class Zy_Base_Actions {

    // _GET 或 _POST
    protected $requestParam = array();

    // _SERVER
    protected $publicParam = array();

    // userid
    protected $userInfo = [
        'userid'        => '',
        'islogin'       => 0,
    ];

    // response
    protected $output = array
    (
        'ec'     => 0,
        'em'    => 'success',
        'data'      => array(),
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
            $this->output['data'] = is_array($res) ? $res : array($res);
        }
        catch (Exception $exception)
        {
            $this->output['ec'] = $exception->getCode ();
            $this->output['em'] = $exception->getMessage ();
        }
        $this->_after();
    }

    protected function _before ()
    {
        // 构造请求参数
        $_GET   = !empty($_GET)  && is_array($_GET)  ? $_GET  : array();
        $_POST  = !empty($_POST) && is_array($_POST) ? $_POST : array();

        $this->requestParam = array_merge ($_GET, $_POST) ;

        if ( !empty($this->requestParam ))
        {
            foreach ($this->requestParam as $key => $value)
            {
                if ( is_numeric( $key ) )
                {
                    unset ($this->requestParam[$key]);
                }
            }
        }

        $this->publicParam = empty($_SERVER) ? array() : $_SERVER ;

        // session中有用户信息,  获取用户信息
        $objSession = Zy_Base_Session::getInstance();
        if ( $objSession !== NULL && $objSession->sessionStatus() )
        {
            $this->userInfo = $objSession->getSessionInfo();

            if (!empty($this->userInfo['userid']))
            {
                $this->userInfo['islogin'] = 1;
            }
        }
    }

    // 结果内容处理
    protected function _after () {
        $this->_output = new Zy_Output ();   
        $this->_output->display($this->_tplData);
    }
}
