<?php
class Action_OrderList extends Zy_BaseAction {

    public function invoke () {
        $arrInput = array(
            'uid'  	=> isset($this->_requestParam['uid']) ? intval($this->_requestParam['uid']) : 0,
            'uname' => isset($this->_requestParam['uname']) ? trim($this->_requestParam['uname']) : '',
            'startTime' => isset($this->_requestParam['startTime']) ? trim($this->_requestParam['startTime']) : '',
            'endTime' => isset($this->_requestParam['endTime']) ? trim($this->_requestParam['endTime']) : '',
            'name'  => isset($this->_requestParam['name']) ? trim($this->_requestParam['name']) : '',
            'pn'  => isset($this->_requestParam['page']) ? intval($this->_requestParam['page']) : 0,
            'rn'  => isset($this->_requestParam['rows']) ? intval($this->_requestParam['rows']) : 20,
        );

        $objPs = new Service_Correct_OrderList ();
        $arrOutput = $objPs->execute($arrInput);
        return $arrOutput;
    }
}