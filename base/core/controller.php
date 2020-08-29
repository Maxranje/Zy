<?php

class Zy_Core_Controller {

    // get / post 请求
    protected $_request     = array();

    // server 数据
    protected $_public      = array();

    // 输出数据结构
    protected $_output      = array();

    // 用户信息
    protected $_userInfo    = array();

    // 是否登录
    protected $_isLogin     = false;

    protected $_userid      = 0;

    protected $_data        = array();

    // ------------------------------

    // 初始化Action所需要内容
    public function _init ($method) {

        // 构造请求参数
        $_GET   = !empty($_GET)  && is_array($_GET)  ? $_GET  : array();
        $_POST  = !empty($_POST) && is_array($_POST) ? $_POST : array();

        $this->_request = array_merge ($_GET, $_POST) ;

        $this->_public = empty($_SERVER) ? array() : $_SERVER ;

        // session中有用户信息,  获取用户信息
        $this->_userInfo = Zy_Core_Session::getInstance()->getSessionUserInfo();
        if (!empty($this->_userInfo['userid'])) {
            $this->_userid = $this->_userInfo['userid'] ;
        } 

        $this->_output  = [
            'ec'        => 0,
            'em'        => 'success',
            'data'      => array(),
            'timestamp' => time(),
        ];

        try
        {
            Zy_Helper_Benchmark::start('ts_all');
            $res = $this->$method();
            $this->_output['data'] = is_array($res) ? $res : array($res);
            Zy_Helper_Benchmark::stop('ts_all');
        }
        catch (Zy_Core_Exception $exception)
        {
            $this->_output['ec'] = $exception->getCode ();
            $this->_output['em'] = $exception->getMessage ();
        }

        if (!isset($this->_output['data']['userInfo'])) {
            $this->_output['data']['userInfo'] = $this->_userInfo;
        }

        $this->displayJson();
    }

    public function isLogin () {
        return !empty ($this->_userInfo);
    }

    public function displayJson () {
        header('Content-Type: application/json; charset=utf-8');
        header('Cache-Control: no-cache');

		Zy_Helper_Log::addnotice("time: [" . Zy_Helper_Benchmark::elapsed_all() . "] request complete" );
		echo json_encode($this->_output);
		exit;
    }

    public function displayTemplate ($template) {
        header('Content-Type: text/html; charset=utf-8');
    }

    public function error($ec = 405, $em = '', $data = []) {
        $this->_output['ec'] = $ec;
        $this->_output['em'] = $em;
        $this->_output['data'] = $data;
        $this->displayJson();
    }


}