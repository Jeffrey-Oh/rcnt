<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['login_access'] = [
    // 로그인 상태에서는 접근할 수 없는 페이지들
    'only_guest' => [
        'member/login',             // 로그인 페이지
        'member/login_ok',          // 로그인 처리
        'member/join',              // 회원가입
        'member/join_form',         // 회원가입 처리
        'member/join_form_ok',      // 회원가입 처리
        'member/join_success',      // 회원가입 처리
        'member/find_pw',           // 비번 찾기
        'member/find_pw_confirm',   // 비번 찾기
        'member/find_pw_success'    // 비번 찾기
    ],
    // 로그인하지 않은 상태에서는 접근할 수 없는 페이지들
    'only_member' => [
        'member/logout',            // 로그아웃
        'member/out',               // 회원탈퇴
        'member/out_ok',            // 회원탈퇴
        'member/buy',               // 회원탈퇴
        'member/edit',              // 정보수정
        'member/edit_ok',           // 정보수정
        'member/edit_success'       // 정보수정
    ]
];


