<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * captcha 이미지 생성 옵션.
 * img_url, word 속성은 각각 url헬퍼,string헬퍼를 사용해야 하므로 실행시에 결정한다.
 */
$config['captcha'] = [
    'img_url'    => '',                             // 이미지 URL
    'word'       => '',                             // 표시할 문자열
    'img_path'   => "files/captcha/",               // 이미지 저장 폴더
    'font_path'  => FCPATH.'application/config/MICKEY.TTF',// 사용될 폰트 파일
    'img_width'  => '240',                          // 이미지의 가로 넓이(px)
    'img_height' => 64,                             // 이미지의 세로 높이(px)
    'font_size'  => 32,                             // 글꼴 크기
    'img_id'     => 'my_captcha',                   // <img>태그에 사용될 id값
    'expiration' => 7200,   // 이미지 보전 시간(초) (지정된 시간 후 자동 삭제됨)
    'colors'     => array(                          // 이미지에 사용되는 색상
            'background' => array(255, 255, 255),   // 배경 색상
            'border' => array(255, 255, 255),       // 이미지 테두리 색상
            'text' => array(0, 0, 0),               // 글자 색상
            'grid' => array(255, 40, 40)            // 글자뒤에 그려질 배경 색상
    )
];


