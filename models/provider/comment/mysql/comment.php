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
            "createtime"  => "createtime",
            "updatetime"  => "updatetime",
            "ext"  => "ext",
        );
    }
}