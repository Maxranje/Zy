<?php

class Dao_User_Mysql_Captcha extends Zy_Core_Dao {

    public function __construct() {
        $this->_dbName      = "zy_platform";
        $this->_table       = "tblCaptcha";
        $this->arrFieldsMap = array(
            "id"  => "id" , 
            "country"  => "country" , 
            "phone"  => "phone" , 
            "code"  => "code" , 
            "verifytime"  => "verifytime" , 
            "isverify"  => "isverify" , 
            "createtime"  => "createtime" , 
            "updatetime"  => "updatetime" , 
            "ext"  => "ext" , 
        );

        $this->simpleFields = array(
            "id"  => "id" , 
            "country"  => "country" , 
            "phone"  => "phone" , 
            "code"  => "code" , 
            "verifytime"  => "verifytime" , 
            "isverify"  => "isverify" , 
            "createtime"  => "createtime" , 
            "updatetime"  => "updatetime" , 
            "ext"  => "ext" , 
        );
    }
}