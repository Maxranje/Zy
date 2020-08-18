<?php

class Dao_Article_Mysql_Article extends Zy_Core_Dao {

    public function __construct() {
        $this->_dbName      = "zdby";
        $this->_table       = "tblArticle";
        $this->arrFieldsMap = array(
            'articleid'   => 'articleid',
            'articletitle'   => 'articletitle',
            'articledesc'   => 'articledesc',
            'articleimg'   => 'articleimg',
            'articleauthor'   => 'articleauthor',
            'articletype'   => 'articletype',
            'articledetails'   => 'articledetails',
            'status'   => 'status',
            'country'   => 'country',
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
            'country'   => 'country',
            'recommend'   => 'recommend',
            'createtime'   => 'createtime',
            'updatetime'   => 'updatetime',
        );
    }
}