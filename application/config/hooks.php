<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| Hooks
| -------------------------------------------------------------------------
| This file lets you define "hooks" to extend CI without hacking the core
| files.  Please see the user guide for info:
|
|	https://codeigniter.com/user_guide/general/hooks.html
|
*/

// 컨트롤러가 호출되기 직전
$hook['pre_controller'][] = array(
        'class'    => 'Pre_controller',
        'function' => 'init_request',
        'filename' => 'Pre_controller.php',
        'filepath' => 'hooks',
        'params'   => FALSE
);

// 브라우저로 컨텐츠가 전송된 후
$hook['post_system'][] = array(
        'class'    => 'Post_system',
        'function' => 'release_response',
        'filename' => 'Post_system.php',
        'filepath' => 'hooks',
        'params'   => FALSE
);

// 컨트롤러의 생성자가 실행된 직후 --> 즉 웹 페이지 실행 직전
$hook['post_controller_constructor'][] = array(
        'class'    => 'Post_controller_constructor',
        'function' => 'check_client',
        'filename' => 'Post_controller_constructor.php',
        'filepath' => 'hooks',
        'params'   => FALSE
);

// 컨트롤러의 생성자가 실행된 직후 
// --> 모든 페이지에서 로그인여부를 검사한다.
$hook['post_controller_constructor'][] = array(
    'class'    => 'Post_controller_constructor',
    'function' => 'login_check',
    'filename' => 'Post_controller_constructor.php',
    'filepath' => 'hooks',
    'params'   => ''
);
