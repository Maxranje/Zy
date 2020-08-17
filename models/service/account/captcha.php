<?php

class Service_Account_Captcha {

    private $daoCaptcha ;

    private $daoUser ;

    private $captchaRecord;

    public function __construct() {
        $this->daoUser = new Dao_User_Mysql_User();
        $this->daoCaptcha = new Dao_User_Mysql_Captcha () ;
    }

    public function isOfenSend ($country, $phone){
        $arrConds = array(
            'country' => $country,
            'phone' => $phone,
        );

        $arrFields = $this->daoCaptcha->simpleFields;

        $this->captchaRecord = $this->daoCaptcha->getRecordByConds($arrConds, $arrFields);
        if (empty($this->captchaRecord)) {
            return false;
        }

        if ($this->captchaRecord['isverify'] == 1 && $this->captchaRecord['verifytime'] - time() >= 0) {
            return false;
        }

        return true;
    }


    public function sendCode ($country, $phone) {

        $arrConds = array(
            'country' => $country,
            'phone' => $phone,
        );

        $arrFields = $this->daoUser->simpleFields;

        $userRecord = $this->daoUser->getRecordByConds($arrConds, $arrFields);

        if (!empty($userRecord) && $userRecord['type'] == Service_Account_User::USER_TYPE_INNER) {
            return true;
        }

        $code = Zy_Helper_Common::rand_string(4, 1);
        $verifytime = time() + 300;

        $data = [
            "code"  => $code , 
            "verifytime"  => $verifytime , 
            "isverify"  => 1 , 
        ];

        if (!empty($this->captchaRecord)) {
            $data = [
                "country"  => $country , 
                "phone"  => $phone , 
                "createtime"  => time() , 
                "updatetime"  => time() , 
            ];
            $ret = $this->daoCaptcha->insertRecords($data);
        } else {
            $data = [
                "updatetime"  => time() , 
            ];
            $ret = $this->daoCaptcha->updateByConds($arrConds, $data);
        }

        if ($ret == false) {
            return false;
        }

        
        Zy_Core_Session::getInstance()->setSessionVerify($data);
        Zy_Helper_Common::sendCaptchaMsg ($country.$phone, $code); 
        return true;
    }


    public function verify ($country, $phone, $code) {
        $arrConds = array(
            'country' => $country,
            'phone' => $phone,
        );

        $arrFields = $this->daoUser->simpleFields;

        $userRecord = $this->daoUser->getRecordByConds($arrConds, $arrFields);

        if (!empty($userRecord) && $userRecord['type'] == Service_Account_User::USER_TYPE_INNER && $code == '2879') {
            Zy_Core_Session::getInstance()->setSessionUserInfo($userRecord['userid'], $userRecord['uname'], $userRecord['phone'], $userRecord['type']);
            return [0, Zy_Core_Session::getInstance()->getSessionUserInfo()];
        }

        $this->captchaRecord = $this->daoCaptcha->getRecordByConds($arrConds, $this->daoCaptcha->simpleFields);
        if (empty($this->captchaRecord['code']) || $this->captchaRecord['code'] != $code) {
            throw new Zy_Core_Exception(405, '验证码错误');
        }

        if ($this->captchaRecord['verifytime'] - 300 < time()) {
            throw new Zy_Core_Exception(405, '验证码已失效');
        }

        if ($this->captchaRecord['isverify'] != 1) {
            throw new Zy_Core_Exception(405, '验证码无效状态');
        }

        if (empty($userRecord)) {
            $service = new Service_Account_User();
            $userRecord = $service->createUserInfo($country, $phone);
            if (empty($userRecord)) {
                throw new Zy_Core_Exception(405, '系统错误, 请重试');
            }
        }

        $data = [
            "code"  => $code , 
            "verifytime"  => $this->captchaRecord['verifytime'] , 
            "isverify"  => 0 , 
        ];
        $this->daoCaptcha->updateByConds($arrConds, $data);
        Zy_Core_Session::getInstance()->setSessionVerify($data);
        Zy_Core_Session::getInstance()->setSessionUserInfo($userRecord['userid'], $userRecord['uname'], $userRecord['phone'], $userRecord['type']);
        $userRecord =  Zy_Core_Session::getInstance()->getSessionUserInfo();
        return $userRecord;
    }
}