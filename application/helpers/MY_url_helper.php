<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*---------------------------------------------------------
 | 페이지 강제 이동 처리
 | @param  $url 이동할 URL. FALSE인 경우 이전 페이지로 이동한다.
 | @param  $msg 페이지 이동전 표시할 alert 메시지. (생략가능)
 ----------------------------------------------------------*/
if ( ! function_exists('page_redirect')) {
    function page_redirect($url=FALSE, $msg=FALSE)
    {
        /** (1) 페이지 강제 이동을 위한 HTML 태그 구성 */
        $html = '<!doctype html>';
        $html .= '<html>';
        $html .= '<head>';
        $html .= "<meta charset='utf-8'>";

        // 메시지가 전달된 경우
        if ($msg != false) {
            $html .= "<script type='text/javascript'>alert('".$msg."');</script>";
        }

        // 이동할 URL이 전달된 경우
        if ($url != false) {
            $html .= "<meta http-equiv='refresh' content='0; url=".$url."'>";
        } else {
            $html .= "<script type='text/javascript'>history.back();</script>";
        }

        $html .= '</head>';
        $html .= '<body></body>';
        $html .= '</html>';
        
        /** (2) CI의 출력문을 사용하여 HTML태그 출력 */
        // 현재 구동중인 컨트롤러에 대한 참조를 가져온다.
        // $CI객체는 컨트롤러에서의 $this에 해당한다.
        $CI = &get_instance();
        $CI->output->set_output($html);
    }
}