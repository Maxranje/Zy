<?php

class Dao_User_Mysql_Order extends Zy_Core_Dao {

    public function __construct() {
        $this->_dbName      = "zy_platform";
        $this->_table       = "tblOrder";
        $this->arrFieldsMap = array(
            "id"  => "id" , 
            "userid"  => "userid" , 
            "courseid"  => "courseid" , 
            "status"  => "status" , 
            "tradeid"  => "tradeid" , 
            "productid"  => "productid" , 
            "price"  => "price" , 
            "realprice" => "realprice",
            "paytype"  => "paytype",
            "openid"  => "openid",
            "banktype" => "banktype",
            "createtime"  => "createtime" , 
            "updatetime"  => "updatetime" , 
            "ext"  => "ext" , 
        );
    }
}