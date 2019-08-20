<?php

class Service_Correct_OrderList  {

    public function __construct() {
        $this->daoOrder = new Dao_Pay_PayOrder ();
    }

    public function execute ($arrInput) {

        $arrInput = $this->checkRequestParam($arrInput) ;
        $arrOutput = array(
            "rows"  => array(),
            "total" => 0,
        );

        $rn = 100 >= intval($arrInput['rn']) ? 100 : intval($arrInput['rn']);
        $pn = intval($arrInput['pn']) == 0 ? 0 : intval($arrInput['pn']) - 1 ;

        $conds = null;
        if ($arrInput['uid'] > 0) {
            $conds['uid'] = $arrInput['uid'];
        }
        if (!empty($arrInput['uname'])) {
            $conds[] = "uname like '%".$arrInput['uname']."%'";
        }
        if (!empty($arrInput['name'])) {
            $conds[] = "name like '%".$arrInput['name']."%'";
        }
        if ($arrInput['startTime'] > 0) {
            $conds[] = "createTime between ".$arrInput['startTime']." and ".$arrInput['endTime'];
        }     

        $fields = Dao_Pay_PayOrder::$arrFields;

        $appends = array(
            'order by createTime asc',
            'limit '.$pn.', '.$rn,
        );    
        $ret = $this->daoOrder->getListByConds($conds, $fields, null, $appends);
        $arrOutput['rows'] = $this->format($ret);
        $arrOutput['total'] = count($arrOutput['rows']);
        return $arrOutput;
    }

    private function checkRequestParam ($arrInput) {
        $arrInput['startTime'] = empty($arrInput['startTime']) ? 0: strtotime($arrInput['startTime']);
        $arrInput['endTime'] = empty($arrInput['endTime']) ? 0: strtotime($arrInput['endTime']);
        if ($arrInput['startTime'] != 0 || $arrInput['endTime'] != 0) {
            if ($arrInput['startTime'] == 0 || $arrInput['endTime'] == 0) {
                throw new Zy_Exception(Zy_ExceptionCode::PARAM_ERROR, "time not empty "); 
            }
            if ($arrInput['endTime'] >= $arrInput['startTime']) {
                throw new Zy_Exception(Zy_ExceptionCode::PARAM_ERROR, "endTime must  greater than startTime");      
            }
        }
        return $arrInput;
    }

    private function format ($data) {
        $result = array();
        foreach ($data as $key => $val) {
            $uinfo = json_decode($val['uinfo'], true);
            $result[] = array(
                'id'        => $val['id'],
                'orderId'   => trim($val['orderId']),
                'uid'       => $val['uid'],
                'uname'     => $uinfo['uname'],
                'phone'     => $uinfo['phone'],
                'serviceId' => $val['serviceId'],
                'name'      => trim($val['name']),
                'quantity'  => $val['quantity'],
                'totalFee'  => $val['totalFee'] / 100,
                'payChannel' => $val['payChannel'] == 1 ? "微信支付" : "支付宝支付",
                'createTime' => date('Y-m-d H:i:s', $val['createTime']),
                'payStatus' => $val['payStatus'],
            );
        }
        return $result;
    }
}