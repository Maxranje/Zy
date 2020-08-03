<?php

class Dao_Course_Mysql_Course extends Zy_Base_Dao {

    public function __construct() {
        $this->_dbName      = "zdby";
        $this->_table       = "tblCourse";
        $this->arrFieldsMap = array(
            "courseid"   => "courseid",
            "coursetype"   => "coursetype",
            "coursename"   => "coursename",
            "courseno"   => "courseno",
            "courseimg"   => "courseimg",
            "location"   => "location",
            "coursetime"   => "coursetime",
            "maxstunum"   => "maxstunum",
            "coursetype"   => "coursetype",
            "coursedesc"   => "coursedesc",
            "coursedetails"   => "coursedetails",
            "status"   => "status",
            "isvip"   => "isvip",
            "vipcount"   => "vipcount",
            "createtime"   => "createtime",
            "updatetime"   => "updatetime",
            "ext"   => "ext",
        );
    }
}