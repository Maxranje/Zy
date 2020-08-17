<?php

class Dao_Banner_Mysql_Banner extends Zy_Core_Dao {

    public function __construct() {
        $this->_dbName      = "zdby";
        $this->_table       = "tblBanner";
        
        $this->arrFieldsMap = array(
            'bannerid' => 'bannerid',
            'bannertitle' => 'bannertitle',
            'bannerurl' => 'bannerurl',
            'bannerimg' => 'bannerimg',
            'status' => 'status',
            'createtime' => 'createtime',
            'updatetime' => 'updatetime',
            'ext' => 'ext',
        );

        $this->simpleFields = array(
            'bannerid',
            'bannerurl',
            'bannerimg',
        ) ;
    }
}