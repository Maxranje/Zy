<?php

class Dao_Campus_Mysql_Campus extends Zy_Core_Dao {

    public function __construct() {
        $this->_dbName      = "zdby";
        $this->_table       = "tblCampus";
        $this->arrFieldsMap = array(
            "campusid"   => "campusid",
            "city"      => "city",
            "area"   => "area",
            "campusname"   => "campusname",
            "articleid"   => "articleid",
            "status"   => "status",
            "createtime"   => "createtime",
            "updatetime"   => "updatetime",
            "ext"   => "ext",
        );

        $this->simpleFields = [
            "campusid" => "campusid",
            "campusname"   => "campusname",
            "articleid"   => "articleid",
            "createtime"   => "createtime",
        ];
    }
}