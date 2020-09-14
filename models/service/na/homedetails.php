<?php

class Service_Na_Homedetails {

    public $bannerPs;

    public $articlePs;

    public $coursePs;

    public $teacherPs;

    public $commentPs;

    public function __construct() {
        $this->bannerPs   = new Service_Banner_Lists ();
        $this->articlePs  = new Service_Article_Lists ();
        $this->coursePs   = new Service_Course_Lists ();
        $this->teacherPs  = new Service_Teacher_Lists ();
        $this->commentPs  = new Service_Comment_Lists ();
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
            'comment'       => array(), 

            // uri
            'vedio'      => array(
                "media"   => 'https://www.w3school.com.cn/i/movie.ogg',
                'cover'   => 'https://ss1.bdstatic.com/70cFvXSh_Q1YnxGkpoWK1HF6hhy/it/u=2067279115,186115572&fm=15&gp=0.jpg', 
            ),
        ];

        // banner 列表
        $arrOutput['banner']     = $this->bannerPs->getBannerList();
        $arrOutput['article']    = $this->articlePs->getRecommendArticleList ();
        $arrOutput['courses']    = $this->coursePs->getCourseList ("", 0, 1, 0, 5);
        $arrOutput['teacher']    = $this->teacherPs->getTeacherList ();
        $arrOutput['comment']    = $this->commentPs->getCommentList ();

        return $arrOutput;
    }
}