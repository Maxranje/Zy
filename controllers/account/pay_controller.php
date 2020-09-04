<?php
require_once SYSPATH . '/helper/pay/wxpay/ext/qrcode/phpqrcode.php';

class Controller_Pay extends Zy_Core_Controller{

    public function index () {

        if (!$this->isLogin()) {
            $this->error(405, '清先登陆');
        }

        $courseid = empty($this->_request['courseid']) ? 0 : intval($this->_request['courseid']);
        $paytype = empty($this->_request['paytype']) ? 'wx' : strval($this->_request['paytype']);

        if (empty($courseid)) {
            $this->error(405, '请选择课程');
        }

        if (!in_array($paytype, [Service_Pay_Order::PAY_TYPE_WX, Service_Pay_Order::PAY_TYPE_ALI])) {
            $this->error(405, '请选择支付方式');
        }

        $service = new Service_Pay_Order();
        if ($service->isOftenPay ($this->_userid) ) {
            $this->error(405, "购买过于频繁");
        }

        $data = $service->payOrder ($this->_userid, $courseid, $paytype) ;
        if ($data == false) {
            $this->error(405, '系统错误, 请重试');
        }

        return ['qrurl' => $data, 'token' => md5('maxranje' . $data . $this->_userid)];
    }

    public function makeimg () {

        if (!$this->isLogin()) {
            $this->error(405, '清先登陆');
        }

        $qrurl = empty($this->_request['qrurl']) ? '' : strval($this->_request['qrurl']);
        $token = empty($this->_request['token']) ? '' : strval($this->_request['token']);

        if (empty($qrurl) || empty($token) || $token !=  md5('maxranje' . $qrurl . $this->_userid)) {
            $this->error(405, 'token错误, 请重试');
        }

        if(substr($qrurl, 0, 6) == "weixin"){
            QRcode::png($qrurl);
            exit;
        }else{
            $this->error(405, 'token错误');
        }
    }

    public function lists () {
        if (!$this->isLogin()) {
            $this->error(405, '清先登陆');
        }

        $pn = empty($this->_request['pn']) ? 0 : intval($this->_request['pn']);
        $rn = empty($this->_request['rn']) ? 20 : intval($this->_request['rn']);

        $pn = $pn * 20;

        $service = new Service_Pay_Order();
        $total = $service->getOrderTotal ($this->_userid);
        $lists = $service->getOrderLists ($this->_userid, $pn, $rn);

        return ['lists' => $lists, 'total' => $total];
    }

    public function callback () {
        
    }

}
