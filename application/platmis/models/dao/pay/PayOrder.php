<?php
/**
 * 支付订单表
 *
 * @author maxranje maxranje@qq.com
 */
class Dao_Pay_PayOrder extends Zy_BaseDao {
	public static $arrFields = array('id','uid','orderId', 'productId', 'serviceId', 'itemId', 'name',
		'quantity', 'price', 'bargain', 'fee', 'totalFee', 'payChannel', 'payStatus', 'tranStatus', 'evalStatus', 
		'uinfo', 'createTime', 'updateTime', 'ext', 'payTime');

	public function __construct() {
		$this->_dbName      = "zy";
		$this->_table       = "tblPayOrder";
		$this->arrFieldsMap = array(
			'id'=>'id',
			'uid'=>'uid',
			'orderId'=>'orderId',
			'productId'=>'productId',
			'serviceId'=>'serviceId',
			'itemId'=>'itemId',
			'name'=>'name',
			'quantity'=>'quantity',
			'price'=>'price',
			'bargain'=>'bargain',
			'fee'=>'fee',
			'totalFee'=>'totalFee',
			'payChannel'=>'payChannel',
			'payStatus'=>'payStatus',
			'tranStatus'=>'tranStatus',
			'evalStatus'=>'evalStatus',
			'uinfo'=>'uinfo',
			'createTime'=>'createTime',
			'updateTime'=>'updateTime',
			'ext'=>'ext',
			'payTime'=>'payTime',
		);
	}
}