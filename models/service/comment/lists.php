<?php

class Service_Comment_Lists {

    private $daoComment ;

    const COMMENT_TYPE = [
        'toefl' => '托福牛人',
        'ielts' => '雅思牛人',
        'sat'   => 'SAT牛人',
        'other' => '其他牛人',
    ];

    public function __construct() {
        $this->daoComment = new Dao_Comment_Mysql_Comment () ;
    }

    public function getCommentList (){
        $arrConds = array(
            'status' => 1,
        );

        $arrFields = $this->daoComment->simpleFields;

        $lists = $this->daoComment->getListByConds($arrConds, $arrFields);

        if (empty($lists)) {
            return [];
        }

        $result = [];
        foreach ($lists as $index => $item) {
            if (!isset(self::COMMENT_TYPE[$item['type']])) {
                continue;
            }

            if (!isset($result[$item['type']])) {
                $result[$item['type']] = ['type' => self::COMMENT_TYPE[$item['type']], 'lists' => []];
            }

            if (count($result[$item['type']]['lists']) > 3) {
                continue;
            }

            $result[$item['type']]['lists'][] = [
                "name"      =>  $item["name"],
                "avatar"    =>  $item["avatar"],
                "content"   =>  $item["content"],
                "score"     =>  $item["score"],
                "createtime" => $item['createtime'],
            ];
        }

        return array_values($result);
    }
}