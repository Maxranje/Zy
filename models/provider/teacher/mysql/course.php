<?php

class Dao_Teacher_Mysql_Course extends Zy_Base_Dao {

    public function __construct() {
        $this->_dbName      = "zdby";
        $this->_table       = "tblTeacherCourse";
        
        $this->arrFieldsMap = array(
            "id"  => "id",
            "teacherid"  => "teacherid",
            "courseid"  => "courseid",
            "coursetype"  => "coursetype",
            "status"  => "status",
            "createtime"  => "createtime",
            "updatetime"  => "updatetime",
            "ext"  => "ext",
        );

        $this->simpleFields = array(
            "teacherid"  => "teacherid",
            "courseid"  => "courseid",
        );
    }
}