<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*-----------------------------------------------------
 | /config/email.php
 | 이메일 발송시 SMTP서버의 정보를 관리하는 설정파일
 |-----------------------------------------------------*/ 

// 구글과 연동할 경우
$config['email']['protocol'] = "smtp";
$config['email']['smtp_host'] = "ssl://smtp.gmail.com";
$config['email']['smtp_port'] = "465";
$config['email']['smtp_user'] = "taehyun920121@gmail.com";
$config['email']['smtp_pass'] = "pdscmoguapdhgemz";
$config['email']['charset'] = "utf-8";
$config['email']['mailtype'] = "html";
$config['email']['smtp_timeout'] = "10";
/*
// 네이버와 연동할 경우
$config['email']['protocol'] = "smtp";
$config['email']['smtp_host'] = "ssl://smtp.naver.com";
$config['email']['smtp_port'] = "465";
$config['email']['smtp_user'] = "itpaper3217";
$config['email']['smtp_pass'] = "itvpdlvj3217!@#";
$config['email']['charset'] = "utf-8";
$config['email']['mailtype'] = "html";
$config['email']['smtp_timeout'] = "10";
*/
