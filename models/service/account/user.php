<?php

class Service_Account_User {

    private $daoUser ;

    const USER_TYPE_INNER = 2;
    const USER_TYPE_NORMAL = 1;
    
    const USER_STATUS_NORMAL = 1;
    const USER_STATUS_BLOCK  = 2;

    public function __construct() {
        $this->daoUser = new Dao_User_Mysql_User () ;
    }

    public function getUserInfo ($userid){
        if (empty($userid)) {
            return false;
        }

        $arrConds = array(
            'status' => 1,
            'userid' => $userid,
        );

        $arrFields = $this->daoUser->simpleFields;

        $userinfo = $this->daoUser->getRecordByConds($arrConds, $arrFields);
        if (empty($userinfo)) {
            return [];
        }

        $userinfo['regtime'] = date('Y-m-d H:i:s');
        $userinfo['birthday']   = date('Y-m-d H:i:s');
        return $userinfo;
    }


    public function editUserInfo ($profile) {
        $arrConds = array(
            'status' => 1,
            'userid' => $profile['userid'],
        );

        $ret = $this->daoUser->updateByConds($arrConds, $profile);
        return $ret;
    }

    public function createUserInfo ($country, $phone) {
        $profile = [
            'uname' => $phone,
            'country' => $country,
            'phone' => $phone,
            'school' => '',
            'graduate' => '',
            'class' => '',
            'birthday' => strtotime('2005-01-01'),
            'sex' => 'M',
            'type' => 3,
            'email' => '',
            'updatetime' => time(),
            'regtime' => time(),
        ];

        $ret = $this->daoUser->insertRecords($profile);
        if ($ret == false) {
            return false;
        }

        $userid = $this->daoUser->getInsertId();

        return [
            'userid' => $userid,
            'uname'  => $profile['uname'],
            'phone'  => $profile['phone'],
            'type'  => 3,
        ];
    }

    public function checkUserStatus ($userid) {
        $user = $this->daoUser->getRecordByConds(['userid' => $userid], $this->daoUser->simpleFields);
        if (empty($user)) {
            return false;
        }
        if ($user['status'] == Service_Account_User::USER_STATUS_BLOCK) {
            return false;
        }
        return true;
    }
}