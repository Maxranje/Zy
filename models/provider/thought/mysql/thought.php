<?php

class Dao_Tought_Mysql_Tought extends Zy_Base_Dao {

    public function __construct() {
        $this->_dbName      = "zdby";
        $this->_table       = "tblTought";

        $this->arrFieldsMap = array(
            "id"  => "id",
            "type"  => "type",
            "name"  => "name",
            "avatar"  => "avatar",
            "content"  => "content",
            "score"  => "score",
            "status"  => "status",
            "createtime"  => "createtime",
            "updatetime"  => "updatetime",
            "ext"  => "ext",
        );
    }
}