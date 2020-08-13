<?php

class Service_Thought_Lists {

    private $daoThought ;

    const THOUGHT_TYPE = [
        1 => '',
        2 => '',
        3 => '',
        4 => '',
    ];

    public function __construct() {
        $this->daoThought = new Dao_Tought_Mysql_Tought () ;
    }

    public function getThoughtList (){
        $arrConds = array(
            'status' => 1,
        );

        $arrFields = $this->daoThought->simpleFields;

        $lists = $this->daoThought->getListByConds($arrConds, $arrFields);

        if (empty($lists)) {
            return [];
        }

        $result = [];
        foreach ($lists as $index => $item) {
            if (!isset(self::THOUGHT_TYPE[$item['type']])) {
                continue;
            }

            if (!isset($result[$item['type']])) {
                $result[$item['type']] = ['type' => self::THOUGHT_TYPE[$item['type']], 'lists' => []];
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