<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Post_controller_constructor
{
    // 컨트롤러를 참조할 객체 --> 컨트롤러에서의 $this 역할을 부여할 객체
    var $CI;

    public function __construct()
    {
        $this->CI =& get_instance();
        $this->CI->load->library('User_agent');
    }

    public function check_client()
    {
        // 사용자 정보 (IP주소,플랫폼,브라우저(버전),모바일단말명)
        log_message("DEBUG", sprintf("[client] %s / %s / %s(%s) / %s",
                                    $this->CI->input->ip_address(),
                                    $this->CI->agent->platform(),
                                    $this->CI->agent->browser(),
                                    $this->CI->agent->version(),
                                    $this->CI->agent->is_mobile() ? $this->CI->agent->mobile() : "PC"
                            )
                    );

        // 실행된 소스의 경로,클래스이름,함수이름
        // --> route 라이브러리는 URL을 관리하는 라이브러리로 자동 초기화 된다.
        log_message("DEBUG", sprintf("[source] %s/%s::%s",
                                    $this->CI->router->directory,
                                    $this->CI->router->class,
                                    $this->CI->router->method));
    }

    /** 컨트롤러 실행 직후 로그인 여부를 검사하기 위한 Hook 함수 */
    public function login_check()
    {
        // 세션에서 데이터를 추출한다.
        $user_info = $this->CI->session->userdata('user_info');

        // url helper를 통해 현재 페이지의 URI를 가져온다.
        $uri = uri_string();
        // 환경설정에서 접근 금지 목록을 가져온다.
        $login_access = $this->CI->config->item('login_access');

        if (empty($user_info)) {
            // 세션정보가 없다면 로그인 정보는 FALSE
            $this->CI->user_info = FALSE;
            log_message("DEBUG", "로그인 중이 아님");

            // 회원만 접근 가능한 페이지의 목록을 가져온다.
            $list = $login_access['only_member'];

            // 회원전용 페이지 목록과 현재 페이지의 URL을 비교하여 같은 값이 있는지 확인한다.
            foreach ($list as $i => $value) {
                if (strpos($uri, $value) !== FALSE) {
                    echo('<script>alert("로그인후에 이용 가능합니다.")</script>');
                    echo('<script>history.back()</script>');
                    exit;
                }
            }
        } else {
            // 세션정보가 존재한다면 컨트롤러 하위에 등록시켜 전역변수로 변환
            $this->CI->user_info = $user_info;
            log_message("DEBUG", sprintf("로그인 중임 (userName=%s, userId=%s)",
                                    $user_info->userName,
                                    $user_info->userId));

            // 로그인 상태에서는 접근할 수 없는 페이지의 목록을 가져온다.
            $list = $login_access['only_guest'];

            // 현재 URL이 가져온 페이지 목록에 포함되어 있다면 에러로 간주한다.
            foreach ($list as $i => $value) {
                if (strpos($uri, $value) !== FALSE) {
                    echo('<script>alert("이미 로그인 중입니다.")</script>');
                    echo('<script>history.back()</script>');
                    exit;
                }
            }
        }
    }
}



