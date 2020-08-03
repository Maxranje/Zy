<?php

class Dao_Teacher_Mysql_Teacher extends Zy_Base_Dao {

    public function __construct() {
        $this->_dbName      = "zdby";
        $this->_table       = "tblTeacher";
        $this->arrFieldsMap = array(
            "teacherid" => "teacherid",
            "teachertype" => "teachertype",
            "teachername" => "teachername",
            "teacheravatar" => "teacheravatar",
            "teacherpic" => "teacherpic",
            "teacherdesc" => "teacherdesc",
            "teacherdetails" => "teacherdetails",
            "status" => "status",
            "createtime" => "createtime",
            "updatetime" => "updatetime",
            "ext" => "ext",
        );
    }
}