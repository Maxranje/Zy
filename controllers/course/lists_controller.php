<?php
class Controller_Lists extends Zy_Core_Controller{

    public function getCourseList () {
        $pn = empty($this->_request['pn']) ? 0 : $this->_request['pn'];
        $rn = empty($this->_request['rn']) ? 20 : $this->_request['rn'];
        $coursetype = empty($this->_request['coursetype']) ? '' : strval($this->_request['coursetype']);
        $teacherid = empty($this->_request['teacherid']) ? 0 : intval($this->_request['teacherid']);

        $pn = $pn * 20;

        if (empty($coursetype) && empty($teacherid)) {
            $coursetype = Service_Course_Lists::COURSE_TYPE_LISTS[0]['id'];
        }

        $serivce = new Service_Course_Lists ();
        $total = $serivce->getCourseList($coursetype, $teacherid);
        $lists = $serivce->getCourseList($coursetype, $teacherid, 0, $pn, $rn);
        $coursetype = $serivce->getCourseTypes ($coursetype);

        return ['coursetype' => $coursetype, 'lists' => $lists, 'total' => $total];
    }


}
