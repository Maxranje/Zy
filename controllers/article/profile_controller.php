<?php
class Controller_Profile extends Zy_Core_Controller{

    public function getArticlDetails () {
        $articleid = empty($this->_request['articleid']) ? 0 : intval($this->_request['articleid']);
        if (empty($articleid)) {
            $this->error(405, '无法检索到相关文章');
        }

        $serivce = new Service_Articel_Lists ();
        $details = $serivce->getArticleDetails($articleid);
        
        if (empty($details)) {
            $this->error(405, '无法检索到相关文章');
        }

        return $details;
    }
}
