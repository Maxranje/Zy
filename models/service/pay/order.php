<?php

class Service_Pay_Order {

    private $daoOrder ;

    private $daoCourse;

    private $daoUser;

    private $pay;

    private $nowtime ;

    const PAY_TYPE_WX = 'WX';

    const PAY_TYPE_ALI = 'ALI';

    const PAY_TYPE = [
        self::PAY_TYPE_WX => '微信支付',
        self::PAY_TYPE_ALI => '支付宝支付',
    ];

    public function __construct() {
        $this->daoOrder = new Dao_User_Mysql_Order();
        $this->daoCourse = new Dao_Course_Mysql_Course();
        $this->daoUser   = new Dao_User_Mysql_User();
        $this->pay       = Zy_Helper_Pay::getInstance();
        $this->nowtime  = time();
    }

    public function isOftenPay($userid) {
        $historyOrder = $this->daoOrder->getRecordByConds(['userid' => $userid], $this->daoOrder->arrFieldsMap, null, ['order by id desc']);
        if (!empty($historyOrder) && $this->nowtime -$historyOrder['createtime'] <= 60) {
            return true;
        }
        return false;
    }

    public function payOrder ($userid, $courseid, $paytype) {

        $user = $this->daoUser->getRecordByConds(['userid' => $userid], $this->daoUser->simpleFields);
        if (empty($user)) {
            throw new Zy_Core_Exception(405, '用户信息不存在, 请重新登陆');
        }
        if ($user['status'] == Service_Account_User::USER_STATUS_BLOCK) {
            throw new Zy_Core_Exception(405, '您的账户已被锁定, 请联系工作人员解封');
        }

        $course = $this->daoCourse->getRecordByConds(['courseid' => $courseid], $this->daoCourse->simpleFields);
        if (empty($course) || $course['status'] == Service_Course_Lists::COURSE_STATUS_OFLINE) {
            throw new Zy_Core_Exception(405, '课程已下线');
        }

        $price = $course['price'];
        $realprice = $course['price'];
        if ($course['isvip'] == 1 && $user['vip'] > 0 && $user['vip'] < 100) {
            $realprice = (intval($course['price']) / 100 ) * intval($user['vip']);
        }

        $data = [
            "userid"  => $userid , 
            "courseid"  => $courseid , 
            "status"  => 2, 
            "tradeid"  => Zy_Helper_Guid::toString() , 
            "productid"  => '110000202011000011000000000' . $course['courseid'] , 
            "price"  =>  $price, 
            "realprice" => $realprice,
            "paytype"  => 'wx',
            "openid"  => "",
            "banktype" => "",
            "createtime"  => time() , 
            "updatetime"  => time() , 
        ];

        if ($paytype == self::PAY_TYPE_WX) {
            $this->daoOrder->insertRecords($data);
            $qrurl = $this->pay->wxpayorder($data['tradeid'], $data['productid'], $realprice);

        } else {
            $qrurl = $this->pay->alipayorder();
        }

        return $qrurl;
    }

    public function getOrderTotal ($userid) {
        if (empty($userid)) {
            throw new Zy_Core_Exception(405, '请先登陆');
        }

        $arrConds = [
            'userid' => $userid,
        ];

        $total = $this->daoOrder->getCntByConds($arrConds);
        return $total;
    }

    public function getOrderLists ($userid, $pn = 0, $rn = 20) {
        if (empty($userid)) {
            throw new Zy_Core_Exception(405, '请先登陆');
        }

        $arrConds = [
            'userid' => $userid,
        ];

        $arrFields = $this->daoOrder->simpleFields;

        $arrAppends = [
            'order by id desc',
            "limit {$pn}, {$rn} ",
        ];

        $lists = $this->daoOrder->getListByConds($arrConds, $arrFields, $arrAppends);
        if (empty($lists)) {
            return [];
        }

        $courseids = array_column($lists, 'courseid');
        $orderList = array_column($lists, null, 'courseid');

        $arrConds = [
            'courseid in (' . implode(',', $courseids) . ')', 
            'status = 1',
        ];
        $arrFields = $this->daoCourse->simpleFields;

        $lists = $this->daoCourse->getListByConds($arrConds, $arrFields);

        foreach ($lists as $index => $course) {
            $course['createtime'] = date('Y年m月d日', $course['createtime']);
            $course['paystatus']  = empty($orderList[$course['courseid']]) ? 4 : $orderList[$course['courseid']];
            $lists[$index] = $course;
        }

        return $lists;
    }
}