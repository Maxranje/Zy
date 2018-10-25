<?php

class Service_Authmis_UserList  {

    public function __construct() {
        $this->daoUcloud = new Dao_Na_Ucloud ();
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
        if ($arrInput['role'] > 0) {
            $conds['role'] = $arrInput['role'];
        }       

        $fields = Dao_Na_Ucloud::$arrFields;

        $appends = array(
            'order by createdTime asc',
            'limit '.$pn.', '.$rn,
        );    
        $ret = $this->daoUcloud->getListByConds($conds, $fields, null, $appends);
        $arrOutput['rows'] = $this->format($ret);
        $arrOutput['total'] = count($arrOutput['rows']);
        return $arrOutput;
    }

    private function checkRequestParam ($arrInput) {
        if (!in_array($arrInput['role'], array(0,1,2,9))){
            throw new Zy_Exception(Zy_ExceptionCode::PARAM_ERROR, "role error");   
        }
        return $arrInput;
    }

    private function format ($data) {
        $result = array();
        foreach ($data as $key => $val) {
            if ($val['role'] == 9) {
                continue;
            }
            $result[] = array(
                'uid' => intval($val['uid']),
                'uname' => trim($val['uname']),
                'phone' => $val['phone'],
                'email' => $val['email'],
                'avatar' => $val['avatar'],
                'role' => $val['role'] == 2 ? "管理员" : "普通用户",
                'createdTime' => date('Y年m月d日', $val['createdTime']),
            );
        }
        return $result;
    }
}