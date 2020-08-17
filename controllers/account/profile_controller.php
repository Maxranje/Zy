<?php
class Controller_Profile extends Zy_Core_Controller{

    public function getUserInfo () {
        if (!$this->isLogin()) {
            $this->error(401, '请先登录');
        }

        $serivce = new Service_Account_User ();
        $userinfo = $serivce->getUserInfo($this->_userid);        

        if (empty($userinfo)) {
            $this->error(500, '无相关用户信息, 请重新登陆');
        }

        return ['userinfo' => $userinfo];
    }


    public function editUserInfo () {

        $uname = empty($this->_request['uname']) ? "" : trim($this->_request['uname']);
        $school = empty($this->_request['school']) ? "" : trim($this->_request['school']);
        $graduate = empty($this->_request['graduate']) ? "" : trim($this->_request['graduate']);
        $class = empty($this->_request['class']) ? "" : trim($this->_request['class']);
        $birthday = empty($this->_request['birthday']) ? "" : trim($this->_request['birthday']);
        $sex = empty($this->_request['sex']) ? "" : trim($this->_request['sex']);
        $email = empty($this->_request['email']) ? "" : trim($this->_request['email']);

        if (!$this->isLogin()) {
            $this->error(401, '请先登录');
        }

        if (empty($uname) || empty($school) || empty($class) || empty($birthday) || empty($sex) || empty($email)) {
            $this->error(401, "上传信息不全, 请重试");
        }

        $len = mb_strlen($uname, 'utf-8');
        if ($len > 9 || $len < 3) {
            $this->error(405, '姓名长度限制 3-8 位');
        }

        $len = mb_strlen($school, 'utf-8');
        if ($len > 20) {
            $this->error(405, '学校名称长度限制 20 字符以内');
        }

        $len = mb_strlen($graduate, 'utf-8');
        if ($len > 10) {
            $this->error(405, '专业名称长度限制 10 字符以内');
        }


        $len = mb_strlen($class, 'utf-8');
        if ($len > 10) {
            $this->error(405, '班级名称限制 10 字符以内');
        }

        $birthday = strtotime($birthday);
        if (empty($birthday) || $birthday <= 315504000 || $birthday >= 1577808000) {
            $this->error(405, '年龄不是开玩笑, 真实一点吧');
        }

        if (!in_array($sex , ['M', 'F'])) {
            $this->error(405, '性别格式不正确, 请重试');
        }

        $len = mb_strlen($email, 'utf-8');
        if ($len > 30 || $len < 3 || !preg_grep('/^[a-zA-Z0-9_-]+@[a-zA-Z0-9_-]+(\.[a-zA-Z0-9_-]+)+$/', [$email])) {
            $this->error(405, '邮箱格式不正确');
        }

        $profile = [
            'userid' => $this->_userid,
            'uname' => $uname,
            'school' => $school,
            'graduate' => $graduate,
            'class' => $class,
            'birthday' => $birthday,
            'sex' => $sex,
            'email' => $email,
            'updatetime' => time(),
        ];

        $serivce = new Service_Account_User ();
        $ret = $serivce->editUserInfo($profile);        

        if ($ret == false) {
            $this->error(500, '编辑失败请重试');
        }

        return $this->_data;
    }
}
