<?php
class Action_UserList extends Zy_BaseAction {

    public function invoke () {
        $arrInput = array(
            'uid'  	=> isset($this->_requestParam['uid']) ? intval($this->_requestParam['uid']) : 0,
            'uname' => isset($this->_requestParam['uname']) ? trim($this->_requestParam['uname']) : '',
            'role'  => isset($this->_requestParam['role']) ? intval($this->_requestParam['role']) : 0,
            'pn'  => isset($this->_requestParam['page']) ? intval($this->_requestParam['page']) : 0,
            'rn'  => isset($this->_requestParam['rows']) ? intval($this->_requestParam['rows']) : 20,
        );

        $objPs = new Service_Authmis_UserList ();
        $arrOutput = $objPs->execute($arrInput);
        return $arrOutput;
    }
}