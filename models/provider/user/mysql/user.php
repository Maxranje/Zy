<?php

class Dao_User_Mysql_User extends Zy_Core_Dao {

    public function __construct() {
        $this->_dbName      = "zy_platform";
        $this->_table       = "tblUser";
        $this->arrFieldsMap = array(
            "userid"  => "userid" , 
            "type"  => "type" , 
            "uname"  => "uname" , 
            "school"  => "school" , 
            "graduate"  => "graduate" , 
            "class"  => "class" , 
            "birthday"  => "birthday" , 
            "sex"  => "sex" , 
            "phone"  => "phone" , 
            "email"  => "email" , 
            "isvip"  => "isvip" , 
            "discount"  => "discount" , 
            "regtime"  => "regtime" , 
            "updatetime"  => "updatetime" , 
            "ext"  => "ext" , 
        );

        $this->simpleFields = array(
            "userid"  => "userid" , 
            "uname"  => "uname" , 
            "school"  => "school" , 
            "graduate"  => "graduate" , 
            "class"  => "class" , 
            "birthday"  => "birthday" , 
            "sex"  => "sex" , 
            "phone"  => "phone" , 
            "email"  => "email" , 
            "regtime"  => "regtime" , 
        );
    }
}