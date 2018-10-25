<?php
/**
 * mis平台-用户权限
 *
 * @author maxranje <maxranje@qq.com>
 */
class Controller_Authmis extends Zy_Controller{

    public $actions = array(
        'authdetail'        => 'actions/authmis/AuthDetail.php',  	# 权限详情页
        'userlist'			=> 'actions/authmis/UserList.php',  	# 用户列表
        'useradd' 			=> 'actions/authmis/UserAdd.php',  		# 添加用户
        'useredit' 			=> 'actions/authmis/UserEdit.php',  	# 编辑用户
        'userdel' 			=> 'actions/authmis/UserDel.php',  		# 删除用户
        'userreset' 		=> 'actions/authmis/UserReset.php',  	# 用户重置密码
    );
}
