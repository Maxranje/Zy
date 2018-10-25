<?php

class Service_Authmis_UserReset  {

    public function __construct() {
        $this->daoUcloud = new Dao_Na_Ucloud ();
    }

    public function execute($arrInput) {
        if ($arrInput['uid'] <= 0){
            throw new Zy_Exception(Zy_ExceptionCode::PARAM_ERROR, "uid error");   
        }    
        $conds = array(
            "uid" => $arrInput['uid'],
        );
        $fields = array(
            "password" => md5(123456),
        );
        $ret = $this->daoUcloud->updateByConds($conds, $fields);
        if ($ret == false) {
            throw new Zy_Exception(Zy_ExceptionCode::SYSTEM_CRAZY);   
        }
        return array();
    }
}