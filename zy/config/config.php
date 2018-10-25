<?php

// BASE URI
$configure['base_url'] = 'http://127.0.0.1:8090/';

// 语言
$configure['language'] = 'en';

// 编码格式
$configure['charset'] = 'UTF-8';

// project log path
$configure['log_path']  = BASEPATH . 'logs/';

// 正则URI
$configure['permitted_uri_chars'] = 'a-z 0-9~%.:_\-';

// xss 开启
$configure['global_xss_filtering'] = TRUE;

// 输出内容压缩
$configure['compress_output']   = TRUE;

// 用户信息库
$configure['db_ucloud']   = 'zy';

// session
$config['sess_driver'] = 'files';
$config['sess_cookie_name'] = 'zyuss';
$config['sess_expiration'] = 7200;

// $config['enable_hooks'] = FALSE;
// $config['error_views_path'] = '';
// $config['sess_driver'] = 'files';
// $config['sess_cookie_name'] = 'ci_session';
// $config['sess_expiration'] = 7200;
// $config['sess_save_path'] = NULL;
// $config['sess_match_ip'] = FALSE;
// $config['sess_time_to_update'] = 300;
// $config['sess_regenerate_destroy'] = FALSE;
// $config['cookie_prefix'] = '';
// $config['cookie_domain']    = '';
// $config['cookie_path']      = '/';
// $config['cookie_secure']    = FALSE;
// $config['cookie_httponly']  = FALSE;
