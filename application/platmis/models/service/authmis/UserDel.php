<?php

class Service_Authmis_UserDel  {

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
        $ret = $this->daoUcloud->deleteByConds($conds);
        if ($ret == false) {
            throw new Zy_Exception(Zy_ExceptionCode::SYSTEM_CRAZY);   
        }
        return array();
    }
}