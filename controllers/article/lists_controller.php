<?php
class Controller_Lists extends Zy_Core_Controller{

    public function getArticleList () {
        $pn = empty($this->_request['pn']) ? 0 : $this->_request['pn'];
        $rn = empty($this->_request['rn']) ? 20 : $this->_request['rn'];

        $pn = $pn * 20;

        $serivce = new Service_Artical_Lists ();
        $total = $serivce->getArticleTotal(Service_Artical_Lists::ARTICLE_TYPE_NORMAL);
        $lists = $serivce->getArticleList(Service_Artical_Lists::ARTICLE_TYPE_NORMAL, 0, $pn, $rn);

        return ['lists' => $lists, 'total' => $total];
    }

    public function getAbroadArticleList () {
        $serivce = new Service_Artical_Lists ();
        $output = $serivce->getAbroadArticleList();
        return $output;
    }

    public function getCampusArticleDetails () {
        $campusid = empty($this->_request['campusid']) ? 0 : intval($this->_request['campusid']);

        $serivce = new Service_Campus_Lists();
        list($articleid, $lists) = $serivce->getCampusList($campusid);
        if (empty($lists) || empty($articleid)) {
            $this->error(405, '系统错误, 请重试');
        }

        $serivce = new Service_Artical_Lists ();
        $details = $serivce->getArticleDetails($articleid);
        if (empty($details)) {
            $this->error(405, '相关校区尚未开启');
        }

        return ['lists' => $lists, 'details' => $details];
    }
}
