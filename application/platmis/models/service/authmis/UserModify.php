<?php

class Service_Authmis_UserModify  {

    public function __construct() {
        $this->daoUcloud = new Dao_Na_Ucloud ();
    }

    public function execute ($arrInput) {
        $ret = [];
        switch ($arrInput['act']) {
            case 'add':
                $ret = $this->addUser($arrInput);
                break;
            case 'edit':
                $ret = $this->editUser($arrInput);
                break;                
            default:
                break;
        }
        return $ret;
    }

    private function addUser($arrInput) {
        $arrInput = $this->checkRequestParam($arrInput) ;

        $conds = [
            'phone' => $arrInput['phone'],
        ];

        $ret = $this->daoUcloud->getCntByConds ($conds);
        if ( intval($ret) > 0 ) {
            throw new Zy_Exception(Zy_ExceptionCode::PHONE_REGIST_ERR);
        }

        $fields = array(
            'uname' => $arrInput['uname'],
            'avatar'=> $arrInput['avatar'],
            'email' => $arrInput['email'],
            'phone' => $arrInput['phone'],
            'role'  => $arrInput['role'],
            'password' => md5(123456),
            'createdTime' => time(),
            'updatedTime' => time(),
        );

        $ret = $this->daoUcloud->insertRecords($fields);
        if ($ret == false) {
            throw new Zy_Exception(Zy_ExceptionCode::SYSTEM_CRAZY);   
        }
        return array();
    }

    private function editUser($arrInput) {
        if ($arrInput['uid'] <= 0){
            throw new Zy_Exception(Zy_ExceptionCode::PARAM_ERROR, "uid error");   
        }    
        $arrInput = $this->checkRequestParam($arrInput) ;

        $conds = [
            'phone = '.$arrInput['phone'],
            "uid != ".$arrInput['uid'],
        ];        
        $ret = $this->daoUcloud->getCntByConds ($conds);
        if ( intval($ret) > 0 ) {
            throw new Zy_Exception(Zy_ExceptionCode::PHONE_REGIST_ERR);
        }

        $conds = array(
            "uid" => $arrInput['uid'],
        );
        $fields = array(
            'uname' => $arrInput['uname'],
            'avatar'=> $arrInput['avatar'],
            'email' => $arrInput['email'],
            'phone' => $arrInput['phone'],
            'role'  => $arrInput['role'],
            'updatedTime' => time(),
        );
        $ret = $this->daoUcloud->updateByConds($conds, $fields);
        if ($ret == false) {
            throw new Zy_Exception(Zy_ExceptionCode::SYSTEM_CRAZY);   
        }
        return array();
    }    

    private function checkRequestParam ($arrInput) {
        if (!in_array($arrInput['role'], array(1,2))){
            throw new Zy_Exception(Zy_ExceptionCode::PARAM_ERROR, "role error");   
        }
        if (empty($arrInput['uname'])){
            throw new Zy_Exception(Zy_ExceptionCode::PARAM_ERROR, "uname error");   
        }
        if (empty($arrInput['phone'])){
            throw new Zy_Exception(Zy_ExceptionCode::PARAM_ERROR, "phone error");   
        }    
        if (empty($arrInput['email'])){
            throw new Zy_Exception(Zy_ExceptionCode::PARAM_ERROR, "email error");   
        }
        return $arrInput;
    }
}