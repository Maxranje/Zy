<?php
/**
 * BASE Controller  重定向请求到Action层, 并初始化Action
 * 子类继承Controller ,  配置actions, 例如:
 * <code>
 * $action => array(
 *     'readlist'   => 'actions/read/v1/List.php',
 * );
 * </code>
 *
 */
class Zy_Base_Controller {

    public $actions = array();

    /**
     * 初始化 Controller , 初始化基础变量和函数, 匹配ACTIONS, 进入业务流程
     */
    public function init ($act = "") {
        if (empty($act) || !isset($this->actions[$act]) || empty($this->actions[$act]) ) {
            trigger_error ('[Error] base controller error, [Detail] action request param error');
        }

        $action_real_path = BASEPATH . $this->actions[$act];

        if (!file_exists ($action_real_path)) {
            trigger_error ('[Error] base controller error, [Detail] action file does not exist ');
        }
        require_once ($action_real_path);

        $action_pathinfo = pathinfo($action_real_path);
        $action_class = 'Action_' . $action_pathinfo['filename'];
        if (!class_exists($action_class)) {
            trigger_error ('[Error] base controller error, [Detail] class does not exist "'.$action_class.'" ');
        }

        $action_obj = new $action_class ();
        call_user_func(array($action_obj, "_init"));
    }
}
