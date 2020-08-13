<?php

class Dao_Article_Mysql_Article extends Zy_Base_Dao {

    public function __construct() {
        $this->_dbName      = "zdby";
        $this->_table       = "tblArticle";
        $this->arrFieldsMap = array(
            'articleid'   => 'articleid',
            'articlename'   => 'articlename',
            'articledesc'   => 'articledesc',
            'articleimg'   => 'articleimg',
            'articleauthor'   => 'articleauthor',
            'articletype'   => 'articletype',
            'articledetails'   => 'articledetails',
            'status'   => 'status',
            'recommend'   => 'recommend',
            'createtime'   => 'createtime',
            'updatetime'   => 'updatetime',
            'ext'   => 'ext',
        );

        $this->simpleFields = array(
            'articleid'   => 'articleid',
            'articlename'   => 'articlename',
            'articledesc'   => 'articledesc',
            'articleimg'   => 'articleimg',
            'articleauthor'   => 'articleauthor',
            'articletype'   => 'articletype',
            'status'   => 'status',
            'recommend'   => 'recommend',
            'createtime'   => 'createtime',
            'updatetime'   => 'updatetime',
        );
    }
}