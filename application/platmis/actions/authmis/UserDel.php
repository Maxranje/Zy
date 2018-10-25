<?php

class Action_UserDel extends Zy_BaseAction {

    public function invoke () {
        $arrInput = array(
            'uid'           => isset($this->_requestParam['uid']) ? intval($this->_requestParam['uid']) : 0,
        );

        $objPs = new Service_Authmis_UserDel ();
        $arrOutput = $objPs->execute($arrInput);
        return $arrOutput;
    }
}