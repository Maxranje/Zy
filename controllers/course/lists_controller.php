<?php
class Controller_Lists extends Zy_Core_Controller{

    public function getCourseList () {
        $pn = empty($this->_request['pn']) ? 0 : $this->_request['pn'];
        $rn = empty($this->_request['rn']) ? 20 : $this->_request['rn'];
        $coursetype = empty($this->_request['coursetype']) ? '' : strval($this->_request['coursetype']);
        $teacherid = empty($this->_request['teacherid']) ? 0 : intval($this->_request['teacherid']);
        $isrecommend = empty($this->_request['isrecommend']) ? 0 : intval($this->_request['isrecommend']);

        $pn = $pn * 20;
        
        $coursetypelist = array_column(Service_Course_Lists::COURSE_TYPE_LISTS, 'id');
        if (!empty($coursetype) && !in_array($coursetype, $coursetypelist)) {
            $this->error(405, "课程类型错误, 请重试");
        }

        $serivce = new Service_Course_Lists ();
        $total = $serivce->getCourseTotal($coursetype, $teacherid, $isrecommend);
        $lists = $serivce->getCourseList ($coursetype, $teacherid, $isrecommend, $pn, $rn);
        $coursetype = $serivce->getCourseTypes ($coursetype);

        return ['coursetype' => $coursetype, 'lists' => $lists, 'total' => $total];
    }


}
