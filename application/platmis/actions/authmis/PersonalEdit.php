<?php

class Action_PersonalEdit extends Zy_BaseAction {

    public function invoke () {
        $arrInput = array(
            'uid'           => isset($this->_userInfo['uid']) ? intval($this->_userInfo['uid']) : 0,
            // 'uname'         => isset($this->_requestParam['uname']) ? trim($this->_requestParam['uname']) : '',
            // 'phone'         => isset($this->_requestParam['phone']) ? trim($this->_requestParam['phone']) : '',
            'email'         => isset($this->_requestParam['email']) ? trim($this->_requestParam['email']) : '',
            'avatar'        => isset($this->_requestParam['avatar']) ? trim($this->_requestParam['avatar']) : '',
            'password'      => isset($this->_requestParam['password']) ? trim($this->_requestParam['password']) : '',
            'act'           => "editinfo",
        );

        $objPs = new Service_Authmis_Personal ();
        $arrOutput = $objPs->execute($arrInput);
        return $arrOutput;
    }
}