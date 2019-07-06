<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*-----------------------------------------------------
 | /application/config/thumbnail.php --> 파일 새로 생성
 | 썸네일 생성시의 기본 설정 정보
 |-----------------------------------------------------*/ 
// 사용되는 PHP의 내부 모듈 이름 (값 고정)
$config['thumbnail']['image_library'] = 'gd2';
// 썸네일 이미지 생성 여부 (값 고정)
$config['thumbnail']['create_thumb'] = TRUE;     
// 이미지 축소시 해상도 비율 유지 여부 (값 고정)
$config['thumbnail']['maintain_ratio'] = TRUE; 
// 썸네일이 저장될 경로
$config['thumbnail']['new_image'] = './files/thumbs/';
// 축소될 이미지의 넓이 (컨트롤러에서 변경 가능)
$config['thumbnail']['width']  = 320;
// 축소될 이미지의 높이 (컨트롤러에서 변경 가능)
$config['thumbnail']['height'] = 320;
// 원본파일의 경로 (컨트롤러에서 값을 할당해야 한다.)
$config['thumbnail']['source_image'] = '';
// 썸네일 이미지 파일명 뒤에 붙을 지시자 (컨트롤러에서 변경 가능)
// ex) 원본: ./files/upload/helloworld.png
//     썸네일: ./files/upload/helloworld_thumb.png
$config['thumbnail']['thumb_marker'] = "_thumb";


