<?php

class Service_App_Login  {

    public function __construct() {
        $this->daoUcloud = new Dao_Na_Ucloud ();
    }

    public function execute ($arrInput) {

        $this->arrOutput = array(
            'uname'     => '',
            'upass'     => '',
            'token'     => '',
            'state'     => 0,
            'reson'     => '',
        );

        if (empty($arrInput['token'])) {
            return $this->getOutput();
        }

        if (empty($arrInput['phone']) || empty($arrInput['upass'])) {
            return $this->getOutput (Zy_ExceptionCode::LOGIN_INFO_EMPTY);
        }

        $arrInput['token'] = Zy_Library_StrCrypt::decodeStr($arrInput['token']);
        if (time() - $arrInput['token'] > 3600) {
            return $this->getOutput (Zy_ExceptionCode::LOGIN_TOKEN_ERR);
        }

        $conds = [
            'phone' => $arrInput['phone'],
            'password' => md5($arrInput['upass']),
        ];
        $ret = $this->daoUcloud->getUserInfoByParam ($conds);
        if (empty($ret) || !isset($ret[0])) {
            $this->arrOutput['phone'] = $arrInput['phone'];
            return $this->getOutput (Zy_ExceptionCode::LOGIN_USERINFO_ERR);
        }

        $userInfo = [
            'uname'     =>  $ret[0]['uname'],
            'avatar'    =>  $ret[0]['avatar'],
            'uid'       =>  $ret[0]['uid'],
            'role'      =>  $ret[0]['role'],
        ];

        $ret = Zy_Library_Session::makeSession($userInfo);
        if ($ret === false) {
            return $this->getOutput (Zy_ExceptionCode::SYSTEM_CRAZY);
        }

        Zy_Common::http_redirect(Zy_Config::getConfig('base_url') . 'platmis/app/index');
    }


    private function getOutput ($errno = 0) {
        $this->arrOutput['token'] = Zy_Library_StrCrypt::encodeStr(strval(time()));
        if ($errno > 0) {
            $this->arrOutput['state'] = $errno;
            $this->arrOutput['reson'] = Zy_ExceptionCode::getErrMsg($errno);
        }
        return $this->arrOutput;
    }
}