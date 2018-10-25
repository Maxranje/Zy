<?php

class Service_App_Personal  {

    public function __construct() {
        $this->daoUcloud = new Dao_Na_Ucloud ();
    }

    public function execute ($arrInput) {
        $conds = [
            'uid' => $arrInput['uid'],
        ];
        $fields = Dao_Na_Ucloud::$arrFields;
        $ret = $this->daoUcloud->getListByConds ($conds, $fields);
        if (empty($ret) || !isset($ret[0])) {
            throw new Zy_Exception(Zy_ExceptionCode::SYSTEM_CRAZY);   
        }

        $userInfo = [
            'uname'     =>  $ret[0]['uname'],
            'avatar'    =>  $ret[0]['avatar'],
        ];

        $objSession = Zy_Session::getInstance();
        $ret = $objSession->makeSession($userInfo);
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