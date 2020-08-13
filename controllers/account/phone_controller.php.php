<?php
class Controller_Index extends Zy_Core_Controller{

    public function getDetailsInfo () {
        $serivce = new Service_Na_Homedetails ();
        $output = $serivce->execute();        

        if (empty($output)) {
            $this->error(500, '服务异常');
        }

        return $output;
    }


    // public function getAbroadDetails () {
    //     $serivce = new Service_Actical_Lists ();
    //     $serivce->get
    // }

    public function getCampusDetails () {

    }

}
