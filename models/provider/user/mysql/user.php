<?php

class Dao_User_Mysql_ extends Zy_Base_Dao {

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