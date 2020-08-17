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

    public function getListByConds($arrConds, $arrFields, $arrOptions = NULL, $arrAppends = NULL, $strIndex = NULL){
        $arrConds['status'] = 1;
        return parent::getListByConds($arrConds, $arrFields, $arrOptions, $arrOptions, $strIndex);
    }

    public function getRecordByConds($arrConds, $arrFields, $arrOptions = NULL, $arrAppends = NULL, $strIndex = NULL){
        $arrConds['status'] = 1;
        return parent::getRecordByConds($arrConds, $arrFields, $arrOptions, $arrOptions, $strIndex);
    }

    public function getCntByConds($arrConds){
        $arrConds['status'] = 1;
        return parent::getCntByConds($arrConds);
    }
}