<?php

class Action_UserReset extends Zy_BaseAction {

    public function invoke () {
        $arrInput = array(
            'uid'           => isset($this->_requestParam['uid']) ? intval($this->_requestParam['uid']) : 0,
        );

        $objPs = new Service_Authmis_UserReset ();
        $arrOutput = $objPs->execute($arrInput);
        return $arrOutput;
    }
}