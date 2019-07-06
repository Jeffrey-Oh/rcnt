<?php defined('BASEPATH') OR exit('No direct script access allowed');
/*-----------------------------------------------------
 | /config/form_validation.php --> 파일 새로 생성
 | 유효성 검사 규칙을 설정할 파일
 |-----------------------------------------------------*/

// form_error() 함수로 에러메시지를 표시할 때 사용될 시작태그와 끝 태그
$config['error_prefix'] = "<div class='error-item'>";
$config['error_suffix'] = "</div>";

/* 이벤트 폼 */
$config['rcnt_input'] = [
    [
        'field' => 'nickName',                                // <input>의 name값
        'label' => '닉네임',                                  // 제목
        'rules' => 'required',                               // 검사 규칙
        'errors' => [                                        // 표시될 에러 메시지
            'required' => '%s은 필수 입력 입니다.',
        ]
    ],
    [
        'field' => 'email',
        'label' => '이메일',
        'rules' => 'required|max_length[150]|valid_email|is_unique[users.email]',
        'errors' => [
            'required' => '%s은 필수 입력 입니다.',
            'max_length' => '%s은 최대 %d글자 까지 입력 가능합니다.',
            'valid_email' => '%s이 유효한 형식이 아닙니다.',
            'is_unique' => '이미 사용중인 %s 입니다.'
        ]
    ],
    [
        'field' => 'trx',                                            // <input>의 name값
        'label' => '개인트론주소',                                    // 제목
        'rules' => 'required|alpha_numeric|min_length[20]|is_unique[users.trx]',  // 검사 규칙
        'errors' => [                                                // 표시될 에러 메시지
            'required' => '지갑주소는 필수 입력 입니다.',
	    'alpha_numeric' => '지갑주소는 영문과 숫자의 조합입니다.',
            'min_length' => '지갑주소는 최소 %d글자 이상 입력하셔야 합니다.',
            'is_unique' => '이미 사용중인 지갑주소 입니다.'
        ]
    ]
];
