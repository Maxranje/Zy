<?php

class Dao_User_Mysql_User extends Zy_Base_Dao {

    public function __construct() {
        $this->_dbName      = "zdby";
        $this->_table       = "tblUser";
        $this->arrFieldsMap = array(
            "userid"  => "userid" , 
            "type"  => "type" , 
            "name"  => "name" , 
            "school"  => "school" , 
            "graduate"  => "graduate" , 
            "class"  => "class" , 
            "birthday"  => "birthday" , 
            "sex"  => "sex" , 
            "phone"  => "phone" , 
            "email"  => "email" , 
            "vip"  => "vip" , 
            "createtime"  => "createtime" , 
            "updatetime"  => "updatetime" , 
            "ext"  => "ext" , 
        );
    }
}