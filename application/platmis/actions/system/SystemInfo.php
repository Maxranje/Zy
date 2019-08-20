<?php
class Action_SystemInfo extends Zy_BaseWebAction {
    public function invoke () {
        $arrInput = array(
            'uid'   => isset($this->_userInfo['uid']) ? intval($this->_userInfo['uid']) : 0,
        );

        $objPs = new Service_Authmis_System ();
        $arrOutput = $objPs->execute($arrInput);

        $template = Zy_Template::getInstance();
        $template->assgin(array('result' => $arrOutput)) ;
        $template->display('systeminfo.twig');
    }
}