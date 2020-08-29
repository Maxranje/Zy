<?php

class Dao_User_Mysql_Course extends Zy_Core_Dao {

    public function __construct() {
        $this->_dbName      = "zy_platform";
        $this->_table       = "tblUserCourse";
        
        $this->arrFieldsMap = array(
            "id"  => "id" ,
            "userid"  => "userid" ,
            "courseid"  => "courseid" ,
            "coursetype"  => "coursetype" ,
            "status"  => "status" ,
            "createtime"  => "createtime" ,
            "updatetime"  => "updatetime" ,
            "ext"  => "ext" ,
        );
    }
}