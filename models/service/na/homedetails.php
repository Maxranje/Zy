<?php

class Service_Na_Homedetails {

    public function __construct() {
        $this->bannerService = new Service_Banner_Index ();
        $this->articleService = new Service_Article_Index ();
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


        $arrOutput['coursetype'] = Service_Course_Index::COURSE_TYPE;
        $arrOutput['banner']     = $this->banner->getBannerList(0, 5);

        return $arrOutput;
    }
}