<?php

class Dao_Na_Ucloud extends Zy_BaseDao {


    public function __construct() {
        $this->_dbName      = "zy";
        $this->_table       = "tblUcloud";
        $this->arrFieldsMap = array(
            'uid' => 'uid',
            'uname' => 'uname',
            'phone' => 'phone',
            'password' => 'password',
            'avatar' => 'avatar',
            'email' => 'email',
            'role' => 'role',
            'forbidden' => 'forbidden',
            'last_log_time' => 'last_log_time',
            'createdTime' => 'createdTime',
            'updatedTime' => 'updatedTime',
        );
        $this->arrFields = array('uid','uname','phone','password','avatar','email','role',
            'forbidden','last_log_time','createdTime','updatedTime');
    }

    public function getUserInfoById ($uid) {
        $uid = intval($uid);
        if (0 >= $uid) {
            return array();
        }
        $ret = $this->getUserInfoByArray(array($uid));
        return isset($ret[0]) ? $ret[0] : array();
    }

    public function getUserInfoByArray ($uids) {
        if (empty($uids) || !is_array($uids)) {
            return array();
        }
        $uids = implode(",", $uids);
        $ret = $this->getListByConds(array('uid in ('.$uids.')'), $this->arrFields);
        return $ret;
    }

    public function getUserInfoByParam ($conds) {
        if (empty($conds) || !is_array($conds)) {
            return array();
        }
        $ret = $this->getListByConds($conds, $this->arrFields);
        return $ret;
    }

    public function addUser ($fields) {
        if (empty($conds) || !is_array($conds)) {
            return false;
        }
        $ret = $this->insertRecords ($fields);
        return $ret;
    }

    public function updateUser($conds, $fields) {
        if (empty($conds) || !is_array($conds)) {
            return false;
        }
        if (empty($fields) || !is_array($fields)) {
            return false;
        }
        $ret = $this->updateByConds($conds, $fields);
        return $ret;
    }

    public function delUser ($conds){
        if (empty($conds) || !is_array($conds)) {
            return false;
        }
        $ret = $this->deleteByConds ($conds);
        return $ret;
    }
}