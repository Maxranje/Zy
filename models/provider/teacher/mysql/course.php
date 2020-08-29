<?php

class Dao_Teacher_Mysql_Course extends Zy_Core_Dao {

    public function __construct() {
        $this->_dbName      = "zy_platform";
        $this->_table       = "tblTeacherCourse";
        
        $this->arrFieldsMap = array(
            "id"  => "id",
            "teacherid"  => "teacherid",
            "courseid"  => "courseid",
            "coursetype"  => "coursetype",
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