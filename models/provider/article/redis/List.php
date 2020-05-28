<?php

class Dao_Na_List extends Zy_BaseDao {

    public function __construct() {
        $this->_dbName      = "maxdb";
        $this->_table       = "tblTestColumn";
        $this->arrFieldsMap = array(
            'id' => 'id',
            'name' => 'name',
            'info' => 'info',
        );
    }
}