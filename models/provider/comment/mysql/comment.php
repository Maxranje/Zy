<?php

class Dao_Comment_Mysql_Comment extends Zy_Core_Dao {

    public function __construct() {
        $this->_dbName      = "zy_platform";
        $this->_table       = "tblComment";

        $this->arrFieldsMap = array(
            "id"  => "id",
            "type"  => "type",
            "name"  => "name",
            "avatar"  => "avatar",
            "content"  => "content",
            "score"  => "score",
            "time" => 'time',
            "createtime"  => "createtime",
            "updatetime"  => "updatetime",
            "ext"  => "ext",
        );

        $this->simpleFields = array(
            "type"  => "type",
            "name"  => "name",
            "avatar"  => "avatar",
            "content"  => "content",
            "time" => 'time',
            "score"  => "score",
        );
    }
}