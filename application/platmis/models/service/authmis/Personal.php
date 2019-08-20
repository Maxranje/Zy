<?php

class Service_Authmis_Personal  {

    public function __construct() {
        $this->daoUcloud = new Dao_Na_Ucloud ();
    }

    public function execute ($arrInput) {
        $ret = [];
        switch ($arrInput['act']) {
            case 'getpersoninfo':
                $ret = $this->getPersonInfo($arrInput);
                break;
            case 'editinfo':
                $ret = $this->editPersonalInfo($arrInput);
                break;                
            default:
                break;
        }
        return $ret;
    }

    private function getPersonInfo ($arrInput) {
        if (intval($arrInput['uid']) <= 0) {
            throw new Zy_Exception(Zy_ExceptionCode::PARAM_ERROR);   
        }

        $fields = Dao_Na_Ucloud::$arrFields;
        $userInfo = $this->daoUcloud->getRecordByConds (array( 'uid' => $arrInput['uid']), $fields);
        if (empty($userInfo)) {
            throw new Zy_Exception(Zy_ExceptionCode::SYSTEM_CRAZY);   
        }

        return $userInfo;
    }

    private function editPersonalInfo ($arrInput) {
        if ($arrInput['uid'] <= 0){
            throw new Zy_Exception(Zy_ExceptionCode::PARAM_ERROR, "uid error");   
        }
        if (empty($arrInput['password'])){
            throw new Zy_Exception(Zy_ExceptionCode::PARAM_ERROR, "password error");   
        } 

        $conds = array(
            "uid" => $arrInput['uid'],
        );
        $fields = array(
            'avatar'=> $arrInput['avatar'],
            'email' => $arrInput['email'],
            'updatedTime' => time(),
        );

        $userInfo = $this->daoUcloud->getRecordByConds ($conds, array('password'));
        if (empty($userInfo)) {
            throw new Zy_Exception(Zy_ExceptionCode::SYSTEM_CRAZY);   
        }
        if ($userInfo['password'] != $arrInput['password']) {
            $fields['password'] = md5($arrInput['password']);
        }
        $ret = $this->daoUcloud->updateByConds($conds, $fields);
        if ($ret == false) {
            throw new Zy_Exception(Zy_ExceptionCode::SYSTEM_CRAZY);   
        }
        return array();
    }    
}