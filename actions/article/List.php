<?php


class Action_List extends Zy_Base_WebAction {
    public function invoke () {
        $arrInput = array(
            'name'  => isset($this->_requestParam['name']) ? trim($this->_requestParam['name']) : "",
            'password'   => isset($this->_requestParam['password']) ? trim($this->_requestParam['password']) : '',
        );

        // 调用PS层代码
        $objPs = new Service_Activity_List();
        $arrOutput = $objPs->execute($arrInput);
        $template = Zy_Template::getInstance();
        $template->assgin(array('result' => $arrOutput)) ;
        $template->display('index.twig');
    }
}