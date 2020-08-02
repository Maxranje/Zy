<?php

class Action_Eidt extends Zy_Base_Actions {
    public function invoke () {
        $arrInput = array(
            'userid'  => isset($this->_userInfo['userid']) ? trim($this->_requestParam['userid']) : '',
        );

        // 调用PS层代码
        $objPs = new Service_Activity_List();
        $arrOutput = $objPs->execute($arrInput);
        return $arrOutput;
    }
}