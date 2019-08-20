<?php

class Service_System_SystemInfo  {

    public function __construct() {
    }

    public function execute ($arrInput) {
        if (intval($arrInput['uid']) <= 0) {
            throw new Zy_Exception(Zy_ExceptionCode::PARAM_ERROR);   
        }
        
        return $userInfo;
    }  
}