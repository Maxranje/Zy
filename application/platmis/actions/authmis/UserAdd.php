<?php

class Action_UserAdd extends Zy_BaseAction {

    public function invoke () {
        $arrInput = array(
            'uname'  	    => isset($this->_requestParam['uname']) ? trim($this->_requestParam['uname']) : '',
            'phone'         => isset($this->_requestParam['phone']) ? trim($this->_requestParam['phone']) : '',
            'email'         => isset($this->_requestParam['email']) ? trim($this->_requestParam['email']) : '',
            'avatar'        => isset($this->_requestParam['avatar']) ? trim($this->_requestParam['avatar']) : '',
            'role'          => isset($this->_requestParam['role']) ? intval($this->_requestParam['role']) : 0,
            'act'           => "add",
        );

        $objPs = new Service_Authmis_UserModify ();
        $arrOutput = $objPs->execute($arrInput);
        return $arrOutput;
    }
}