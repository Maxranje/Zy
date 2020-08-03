<?php

class Action_Index extends Zy_Base_Actions {
    public function invoke () {
        $arrInput = array(
            'userid'  => isset($this->_userInfo['userid']) ? trim($this->_userInfo['userid']) : '',
        );

        // 调用PS层代码
        $objPs = new Service_Na_Homedetails ();
        $arrOutput = $objPs->execute($arrInput);
        return $arrOutput;
    }
}