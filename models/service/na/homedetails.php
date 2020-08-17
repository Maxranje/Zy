<?php

class Service_Na_Homedetails {

    public $bannerPs;

    public $articlePs;

    public $coursePs;

    public $teacherPs;

    public $thoughtPs;

    public function __construct() {
        $this->bannerPs   = new Service_Banner_Lists ();
        $this->articlePs  = new Service_Artical_Lists ();
        $this->coursePs   = new Service_Course_Lists ();
        $this->teacherPs  = new Service_Teacher_Lists ();
        $this->thoughtPs  = new Service_Thought_Lists ();
    }

    public function execute (){

        $arrOutput = [
            // 课程类型
            'coursetype'    => Service_Course_Lists::COURSE_TYPE_LISTS,
            
            // banner 列表
            'banner'        => array(),

            // 热门文章列表
            'article'       => array(),

            // 热门课程列表
            'courses'       => array(),

            // 老师列表
            'teacher'       => array(),

            // 附加
            'thought'       => array(), 

            // uri
            'vediourl'      => '',

            // platform
            "platformurl"   => "",
        ];

        // banner 列表
        $arrOutput['banner']     = $this->bannerPs->getBannerList(0, 5);
        $arrOutput['article']    = $this->articlePs->getRecommendArticleList ();
        $arrOutput['courses']    = $this->coursePs->getCourseList ("", 0, 1, 0, 5);
        $arrOutput['teacher']    = $this->teacherPs->getTeacherList ();
        $arrOutput['thought']    = $this->thoughtPs->getThoughtList ();

        if (Zy_Core_Session::getInstance()->getSessionUserType() == Service_Account_User::USER_TYPE_INNER) {
            $arrOutput['platformurl'] = "";
        }

        return $arrOutput;
    }
}