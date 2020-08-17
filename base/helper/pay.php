<?php

class Zy_Helper_Pay {

    public static $instance = null;

    private function __construct(){}

    public static function getInstance () {
        if (self::$instance == null) {
            self::$instance = new Zy_Helper_Pay();
        }
        return self::$instance;
    }

    public function wxpayorder ($out_trade_no, $productid, $total_amount) {
        $service = Zy_Helper_Pay_Wxpay_Api::getInstance();
        $qrurl = $service->getQrCode($out_trade_no, $productid, $total_amount);
        return $qrurl;
    }

    public function wxpayquery () {
        
    }

    public function alipayorder () {

    }

}