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

    // output

    protected $output = [
        'ec'    => 0,
        'em'    => 'success',
        'data'  => [],
    ];

    // userinfo
    protected $userInfo = [];

    protected $islogin = false;

    // ------------------------------

    abstract protected function invoke();

    // 初始化Action所需要内容
    public function _init ()
    {
        $this->_before();
        try
        {
            Zy_Helper_Benchmark::start('ts_all');
            $res = $this->invoke();
            $this->output['data'] = is_array($res) ? $res : array($res);
            Zy_Helper_Benchmark::stop('ts_all');
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
        $this->userInfo = $this->getSessionUserInfo();
        if (empty($this->userInfo)) {
            $this->islogin = false;
        }
    }

    // 结果内容处理
    protected function _after () {
        $this->outputJson();
    }

    public function getSessionUserInfo() {
        $session = Zy_Base_Session::getInstance()->getSessionUserInfo;
        return $session->getSessionUserId();
    }

    public function outputJson () {
		Zy_Helper_Log::addnotice("time: [" . Zy_Helper_Benchmark::elapsed_all() . "] request complete" );
		echo json_encode($this->output);
		exit;
    }

    // public function outputTemplate () {

    // }
}
