<?php defined('BASEPATH') OR exit('No direct script access allowed');

if ( !function_exists('debug')) {
    function debug($msg, $title = 'debug')
    {
        $content = print_r($msg, true);         // 전달된 내용을 출력형식으로 변환
        $CI =& get_instance();                  // 컨트롤러에 대한 참조

         // CSS로 꾸민 내용을 출력한다.
        $CI->output->append_output("<div class='debug' style='position: absolute; z-index: 10000;'>
            <fieldset style='padding: 15px; margin: 10px; border: 1px solid #bce8f1; border-radius: 4px; color: #31708f; background-color: #d9edf7; word-break: break-all; font-size: 12px; font-family: D2Coding,NanumGothicCoding,나눔고딕코딩,Helvetica,굴림'>
                <legend style='padding: 2px 15px; border: 1px solid #bce8f1; background-color: #fff; font-weight: bold'>".$title."</legend>
                <pre style='margin: 0px; padding: 0; border:0; background: none; white-space: pre-wrap;'>".htmlspecialchars($content)."</pre>
            </fieldset></div>");
    }
}

if ( !function_exists('captcha')) {
    /**
     * captch 이미지를 생성한다.
     * @param  $max_len - 생성할 문자열의 길이 
     * @param  $mode - 문자열 생성 옵션 (string헬퍼에서 사용)
     *                  alpha, alnum, numeric, nozero 등
     * @return  array 
     */
    function captcha($max_len=6, $mode='alnum')
    {
        $CI =& get_instance();                   // 컨트롤러의 참조
        $config = $CI->config->item('captcha');  // captcha 설정을 가져온다.

        // 설정에 결정되어 있지 않은 값들을 helper를 사용하여 생성한다.
        $config['img_url'] = base_url($config['img_path']);
        // 영어 대문자만 사용하도록 생성된 렌덤 문자열을 대문자로 변환
        $config['word'] = strtoupper(random_string('alnum', 6));

        // 해당 경로가 존재하지 않는다면 생성한다.
        if (!is_dir($config['img_path'])) {
            mkdir($config['img_path'], 0777, true);
        }

        // captcha를 생성하고 생성 결과를 리턴한다.
        return create_captcha($config);
    }
}

if ( !function_exists('send_mail')) {
    /**
     * 이메일 라이브러리를 사용하여 메일을 발송한다.
     * @param  $receiver_addr : 수신자 주소
     * @param  $receiver_name : 수신자 이름
     * @param  $subject       : 메일제목
     * @param  $content       : 메일내용
     * @return boolean (성공=true, 실패=false)
     */
    function send_mail($receiver_addr, $receiver_name, $subject, $content)
    {
        /** (1) 환경 설정정보에서 이메일관련 값만 추출 */
        $CI =& get_instance();  // 현재 컨트롤러의 참조
        $mail_config = $CI->config->item('email');

        /** (2) 발송정보 구성 */
        $sender_addr    = $mail_config['smtp_user'];
        $sender_name    = "관리자";

        /** (3) 받는 사람 주소 재구성 */
        if ($receiver_name) {
            // 이름과 메일주소가 함께 있는 경우 형식 재구성 --> 야옹이 <itpaper3217@gmail.com>
            $receiver_addr = sprintf("%s <%s>", $receiver_name, $receiver_addr);
        }

        /** (4) 메일 발송 설정 */
        // 메일 발송 라이브러리에 환경설정 정보 전달
        $CI->email->initialize($mail_config);
        // 메일 발송 초기화 (줄바꿈 규칙 설정, 이전 발송 내용 삭제)
        $CI->email->set_newline("\r\n");
        $CI->email->clear();

        /** (5) 메일 보내기 */
        $CI->email->from($sender_addr, $sender_name);     // 발신자 주소+이름(이름생략가능)
        $CI->email->to($receiver_addr);                   // 수신자주소 (배열로 복수지정 가능)
        $CI->email->reply_to($sender_addr, $sender_name); // 답장 받을 사람(생략가능)
        $CI->email->subject($subject);                    // 제목
        $CI->email->message($content);                    // 내용

        // 발송 후 결과값 리턴
        return $CI->email->send();
    }
}

if (!function_exists('get_page_info')) {
    /*
     * 페이지 구현에 필요한 변수값들을 계산한다.
     * @param  $total_count     - 페이지 계산의 대상이 되는 전체 데이터 수
     * @param  $now_page        - 현재 페이지
     * @param  $list_count      - 한 페이지에 보여질 목록의 수
     * @param  $group_count     - 페이지 그룹 수
     * @return Array - now_page     : 현재 페이지
     *               - total_count  : 전체 데이터 수
     *               - list_count   : 한 페이지에 보여질 목록의 수
     *               - total_page   : 전체 페이지 수
     *               - group_count  : 한 페이지에 보여질 그룹의 수
     *               - total_group  : 전체 그룹 수
     *               - now_group    : 현재 페이지가 속해 있는 그룹 번호
     *               - group_start  : 현재 그룹의 시작 페이지
     *               - group_end    : 현재 그룹의 마지막 페이지
     *               - prev_group_last_page  : 이전 그룹의 마지막 페이지
     *               - next_group_first_page : 다음 그룹의 시작 페이지
     *               - offset       : SQL의 LIMIT절에서 사용할 데이터 시작 위치
     */
    function get_page_info($total_count, $now_page=1, $list_count=15, $group_count=5)
    {
        // LIMIT 절에서 사용할 데이터 시작 위치
        $offset = ($now_page - 1) * $list_count;
        
        // 전체 페이지 수
        $total_page = intval((($total_count - 1) / $list_count) + 1);

        // 전체 그룹 수
        $total_group = intval((($total_page) - 1) / ($group_count)) + 1;

        // 현재 페이지가 속한 그룹
        $now_group = intval((($now_page - 1) / $group_count)) + 1;

        // 현재 그룹의 시작 페이지 번호
        $group_start = intval((($now_group - 1) * $group_count)) + 1;

        // 현재 그룹의 마지막 페이지 번호
        $group_end = min($total_page, $now_group * $group_count);

        // 이전 그룹의 마지막 페이지 번호
        $prev_group_last_page = 0;
        if ($group_start > $group_count) { $prev_group_last_page = $group_start - 1; }

        // 다음 그룹의 시작 페이지 번호
        $next_group_first_page = 0;
        if ($group_end < $total_page) { $next_group_first_page = $group_end + 1; }

        // 리턴할 데이터들을 객체로 묶기
        $data = new stdClass();
        $data->now_page = $now_page;
        $data->total_count = $total_count;
        $data->list_count = $list_count;
        $data->total_page = $total_page;
        $data->group_count = $group_count;
        $data->total_group = $total_group;
        $data->now_group = $now_group;
        $data->group_start = $group_start;
        $data->group_end = $group_end;
        $data->prev_group_last_page = $prev_group_last_page;
        $data->next_group_first_page = $next_group_first_page;
        $data->offset = $offset;

        return $data;
    }
}