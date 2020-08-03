<?php

class Service_Activity_List {
    public function __construct() {
    }

    public function execute ($arrInput){
        $arrOutput = array(
            'title' => 'test',
            'header'   => 'hahahah',
            'name'  => $arrInput['name'],
            'password'  => $arrInput['password'],
        );
        return $arrOutput;
    }
}