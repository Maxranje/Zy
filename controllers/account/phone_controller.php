<?php
class Controller_Phone extends Zy_Core_Controller{

    public function send () {

        $phone = empty($this->_request['phone']) ? "" : strval($this->_request['phone']);
        $country = empty($this->_request['country']) ? "+86" : strval($this->_request['country']);

        if ($country != "+86" || empty($phone) || !Zy_Helper_Common::checkPhoneAvalilable($phone)) {
            $this->error(405, "请填写有效的手机号");
        }

        if ($this->isLogin()) {
            $this->error(405, '您已经登陆完成');
        }

        $serivce = new Service_Account_Captcha ();
        if ($serivce->isOfenSend ($country, $phone)) {
            $this->error(405, '短信已发送, 5 分钟内有效');
        }

        $ret = $serivce->sendCode($country, $phone) ;
        if ($ret == false) {
            $this->error(500, '服务异常, 请重试');
        }

        return $this->_data;
    }

    public function verify () {
        $phone = empty($this->_request['phone']) ? "" : strval($this->_request['phone']);
        $country = empty($this->_request['country']) ? "+86" : strval($this->_request['country']);
        $code = empty($this->_request['code']) ? "" : strval($this->_request['code']);

        if ($country != "+86" || empty($phone) || !Zy_Helper_Common::checkPhoneAvalilable($phone)) {
            $this->error(405, "请填写有效的手机号");
        }
        if (empty($code) || !is_numeric($code) || strlen($code) != 4) {
            $this->error(405, "无效验证码, 请重新输入");
        }

        if ($this->isLogin()) {
            $this->error(405, '您已经登陆完成');
        }

        $serivce = new Service_Account_Captcha ();

        $data = $serivce->verify($country, $phone, $code) ;
        if ($data == false) {
            $this->error(500, '服务异常, 请重试');
        }

        return ['userinfo' => $data];
    }


}
