<?php

class Dao_Course_Mysql_Course extends Zy_Core_Dao {

    public function __construct() {
        $this->_dbName      = "zy_platform";
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
            "coursemodel"   => "coursemodel",
            "coursedesc"   => "coursedesc",
            "coursedetails"   => "coursedetails",
            "status"   => "status",
            "isvip"   => "isvip",
            "price"   => "price",
            "recommend" => "recommend",
            "createtime"   => "createtime",
            "updatetime"   => "updatetime",
            "ext"   => "ext",
        );

        $this->simpleFields = [
            "courseid"   => "courseid",
            "coursetype"   => "coursetype",
            "coursename"   => "coursename",
            "courseno"   => "courseno",
            "courseimg"   => "courseimg",
            "location"   => "location",
            "coursetime"   => "coursetime",
            "maxstunum"   => "maxstunum",
            "coursemodel"   => "coursemodel",
            "coursedesc"   => "coursedesc",
            "status"   => "status",
            "createtime"   => "createtime",
            "updatetime"   => "updatetime",
        ];
    }
}