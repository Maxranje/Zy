<?php
/**
 * 商品表
 *
 * @author maxranje maxranje@qq.com
 */
class Dao_Pay_PayProduct extends Zy_BaseDao {

    public static $arrFields = array('id','serviceId','itemId','type', 'name','title','description', 
        'img', 'link', 'price', 'quantity', 'sale', 'status', 'startTime', 'endTime', 'strategy', 
        'createTime', 'updateTime', 'opuid', 'duration', 'ext');

    public function __construct() {
        $this->_dbName      = "zy";
        $this->_table       = "tblPayProduct";
        $this->arrFieldsMap = array(
           'id'=> 'id',
           'serviceId'=> 'serviceId',
           'itemId'=> 'itemId',
           'type'=> 'type',
           'name'=> 'name',
           'title'=> 'title',
           'description'=> 'description',
           'img'=> 'img',
           'link'=> 'link',
           'price'=> 'price',
           'quantity'=> 'quantity',
           'sale'=> 'sale',
           'status'=> 'status',
           'startTime'=> 'startTime',
           'endTime'=> 'endTime',
           'strategy'=> 'strategy',
           'createTime'=> 'createTime',
           'updateTime'=> 'updateTime',
           'opuid'=> 'opuid',
           'duration'=> 'duration',
           'ext'=> 'ext',
        );
    }
}