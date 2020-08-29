<?php

class Dao_Teacher_Mysql_Teacher extends Zy_Core_Dao {

    public function __construct() {
        $this->_dbName      = "zy_platform";
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

        $this->simpleFields = array(
            "teacherid" => "teacherid",
            "teachertype" => "teachertype",
            "teachername" => "teachername",
            "teacheravatar" => "teacheravatar",
            "teacherdesc" => "teacherdesc",
            "status" => "status",
            "createtime" => "createtime",
            "updatetime" => "updatetime",
        );
    }
}