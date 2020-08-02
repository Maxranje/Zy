<?php
/**
 * Action层的基础父类, 根据请求处理是否是Ajax 请求, 通常直接在页面中直接通过tpl返回
 *
 */

abstract class Zy_Base_Actions {

    // _GET 或 _POST
    protected $_requestParam = array();

    // _SERVER
    protected $_publicParam = array();

    protected $_userInfo = array();

    protected $isLogin = false;

    protected $_output = array(
        'ec'  => 0,
        'em'  => 'success',
        'data' => array(),
        'timestamp' => 0,
    );

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
            $this->_output['data'] = is_array($res) ? $res : array($res);
            Zy_Helper_Benchmark::stop('ts_all');
        }
        catch (Exception $exception)
        {
            $this->_output['ec'] = $exception->getCode ();
            $this->_output['em'] = $exception->getMessage ();
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
        $session = Zy_Base_Session::getInstance();
        $this->_userInfo = $session->getSessionUserInfo();

        $this->checkLogin ();
    }

    // 结果内容处理
    protected function _after () {
        $this->_output['timestamp'] = time();
        if (!isset($this->_output['userInfo'])) {
            $this->_output['userInfo'] = $this->_userInfo;
        }
        $this->outputJson();
    }

    public function checkLogin () {
        if (empty($this->_userInfo)) {
            if (Zy_Helper_URI::spencryptApi() ) {
                $this->error(401, '请先登录');
            }
            $this->isLogin = false;
            return false;
        }
        return true;
    }

    public function outputJson () {
		Zy_Helper_Log::addnotice("time: [" . Zy_Helper_Benchmark::elapsed_all() . "] request complete" );
		echo json_encode($this->_output);
		exit;
    }

    public function error($ec = 405, $em = '', $data = []) {
        $this->_output['ec'] = $ec;
        $this->_output['em'] = $em;
        $this->_output['data'] = $data;
        $this->_output['timestamp'] = time();
        $this->outputJson();
    }
}
