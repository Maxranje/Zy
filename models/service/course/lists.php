<?php

class Service_Course_Lists {

    const COURSE_TYPE = [
        [ 'id'    => 'toefl', 'name'  => 'TOEFL', ],
    ];

    public function __construct() {

    }

    public function execute ($arrInput){

        $arrOutput = [

            'coursetype' => array(),        

            'banner' => array(),

            'article' => array(),
            
            'courses' => array(),

            'teacher' => array(),

            'thought' => array(), 

            'vediourl' => '',
        ];

        return $arrOutput;
    }
}