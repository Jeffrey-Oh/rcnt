<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Rcnt extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model("Users_dao", "users_dao");
    }

    public function input() {
	/* 추첨 on/off */
	// 추첨할 땐 주석처리하고 안할 땐 주석 달기
	return page_redirect("event_result", "지금은 추첨 이벤트를 하지 않습니다.");

        /* ip 검사 */
        $ipAddress = $this->input->ip_address();
        $cnt = $this->users_dao->where("ipAddress", $ipAddress)->count_all_results();
        if ($cnt > 0) {
            return page_redirect("event_result", "중복 참여는 불가능 합니다.");
        }

        /* 선착순 마감 검사 */
	// 선착순 조절 하는 곳
        $count = $this->users_dao->count_all();
        if ($count >= 20) {
            return page_redirect("event_result", "선착순 마감 되었습니다.");
        }

        $this->load->view("rcnt/input");
    }

    public function event_ok() {
        // json 설정
        header('Content-Type: application/json; charset=utf-8');

        /* 폼 검증 */
        if ($this->form_validation->run("rcnt_input") === false) {
            echo json_encode(array("rt" => "ERROR", "msg" => print_r($this->form_validation->error_string(), true), "url" => base_url("input")));
            exit;
        }

        /* 파라미터 받기 */
        $nickName = $this->input->post("nickName");
        if (substr($nickName, 0, 1) != "@") {
            echo json_encode(array("rt" => "nickName", "msg" => "닉네임에 @를 포함하여 입력해주세요."));
            exit;
        }
        $email = $this->input->post("email");
        $trx = $this->input->post("trx");
        if (substr($trx, 0, 1) != "T") {
            echo json_encode(array("rt" => "trx", "msg" => "올바르지 않는 트론 주소 입니다.\n트론 주소는 첫 시작 알파벳이 T 입니다."));
            exit;
        }
        $ipAddress = $this->input->ip_address();

        /* ip 검사 */
        $cnt = $this->users_dao->where("ipAddress", $ipAddress)->count_all_results();
        if ($cnt > 0) {
            echo json_encode(array("rt" => "duplicate", "msg" => "중복 참여는 불가능 합니다."));
            exit;
        }

        /* 모델 묶기 */
	$id = 0;
    	if ($this->users_dao->count_all() < 20) {
            $this->users_dao->nickName = $nickName;
            $this->users_dao->email = $email;
            $this->users_dao->trx = $trx;
            $this->users_dao->ipAddress = $ipAddress;
            $this->users_dao->regDate = "{now()}";
            $id = $this->users_dao->insert();
        } else {
	    echo json_encode(array("rt" => "ERROR", "msg" => "선착순 마감되었습니다."));
	    exit;
	}
        
        echo json_encode(array("rt" => "OK", "number" => $id));
        exit;
    }

    public function event_result() {
	/* 관리자 유무 ip 확인 */
        $master = false;
        $ip = $this->input->ip_address();
        if ($ip == "124.59.189.203") {
            $master = true;
        }
        $this->load->vars("master", $master);
        /* 목록 조회 */
        $item = $this->users_dao->select();
        $this->load->vars("item", $item);
        $resCnt = $this->users_dao->where("winner", null)->count_all_results();
        $this->load->vars("resCnt", $resCnt);
        $this->load->view("rcnt/event_result");
    }

    public function event_ajax() {
        // json 설정
        header('Content-Type: application/json; charset=utf-8');

        /* 당첨자 1명 씩 선정 */
	// 당첨자 조절 하는 곳
        $resCnt = $this->users_dao->where("winner", "당첨")->count_all_results();
        if ($resCnt >= 5) {
            echo json_encode(array("rt" => "FAIL", "msg" => "이미 추첨이 완료되었습니다."));
            exit;
        }
        $temp = $this->users_dao->where("winner", null)->count_all_results();
        $tempList = $this->users_dao->where("winner", null)->select();
        $rand = rand(0, $temp-1);
        $id = $tempList[$rand]->usersId;
        $this->users_dao->winner = "당첨";
        $this->users_dao->where("usersId", $id)->update();

        $resCnt = $this->users_dao->where("winner", "당첨")->count_all_results();

        /* 목록 조회 */
        $item = $this->users_dao->select();
        echo json_encode(array("rt" => "OK", "item" => $item, "resCnt" => $resCnt));
        exit;
    }

    public function btn_text() {
        /** 1) 엑셀로 사용할 데이터 형식 준비 (2차 연관배열) */
        $item = $this->users_dao->where("winner", "당첨")->select();
        for ($i = 0; $i < count($item); $i++) {
            $data[$i] = ['nickName' => $item[$i]->nickName, 'email' => $item[$i]->email, 'trx' => $item[$i]->trx, 'ipAddress' => $item[$i]->ipAddress];
        }

        /** 2) 문서 기본속성 지정 */
        // 불필요한 항목은 생략 가능함
        $this->phpexcel->getProperties()->setCreator("JeffreyOh")
                                        ->setLastModifiedBy("JeffreyOh")
                                        ->setTitle("RCNT 추첨 당첨자 정보")
                                        ->setSubject("RCNT 추첨 이벤트")
                                        ->setDescription("RCNT 추첨 이벤트")
                                        ->setKeywords("RCNT")
                                        ->setCategory("RCNT");
                                        
        /** 3) 문서의 기본 글꼴, 글자크기 */
        $this->phpexcel->getDefaultStyle()->getFont()->setName('맑은고딕')->setSize(14);

        /** 4) 시트를 지정한다. */
        $this->phpexcel->getActiveSheet()->setTitle("RCNT 당첨자 정보");
        $this->phpexcel->setActiveSheetIndex(0);

        /** 5) 엑셀에 데이터 수동으로 데이터 채워 넣기 */
        // --> 제목행 구성을 위한 수동처리
        // --> 엑셀은 열의 경우 0부터 카운트하고, 행의 경우 1부터 카운트한다.

        // 설정된 시트를 가져온다.
        $sheet = $this->phpexcel->getActiveSheet();
        // 0열의 1행에 내용을 STRING 형식으로 넣음.
        $sheet->setCellValueExplicitByColumnAndRow(
                            0, 1, "닉네임", PHPExcel_Cell_DataType::TYPE_STRING);
        // 0열의 1행에 내용을 STRING 형식으로 넣음.
        $sheet->setCellValueExplicitByColumnAndRow(
                            1, 1, "이메일", PHPExcel_Cell_DataType::TYPE_STRING);
        // 0열의 1행에 내용을 STRING 형식으로 넣음.
        $sheet->setCellValueExplicitByColumnAndRow(
                            2, 1, "지갑주소", PHPExcel_Cell_DataType::TYPE_STRING);
        $sheet->setCellValueExplicitByColumnAndRow(
                            3, 1, "아이피", PHPExcel_Cell_DataType::TYPE_STRING);

        /** 6) 반복문으로 데이터 채워 넣기  */
        $row = 2;   // 행(줄) 앞에서 첫 번째 행에 데이터를 넣었으므로 두 번째 행부터 시작.
        $col = 0;   // 열(칸)
        foreach($data as $index => $item) {
            $col = 0;   // 배열의 매 행마다 엑셀의 0번째 칸부터 다시 시작
            foreach ($item as $key => $value) {
                $sheet->setCellValueExplicitByColumnAndRow(
                    $col, $row, $value, PHPExcel_Cell_DataType::TYPE_STRING);
                $col++; // 엑셀의 다음 칸(열)로 이동
            }
            $row++;     // 엑셀의 다음 줄(행)로 이동
        }

        /** 7) 파일로 내보낸다. */
        // 엑셀 형식으로 인식시키기 위한 HTTP Header설정
        $filename = "RCNT_".date('Y-m-d', time()).".xlsx";
        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment;filename="'.$filename.'"');
        // 캐시로 저장되지 않도록 하기 위한 처리
        header('Cache-Control: max-age=0');
        header ('Expires: Mon, 26 Jul 1997 05:00:00 GMT');
        header ('Last-Modified: '.gmdate('D, d M Y H:i:s').' GMT');
        header ('Cache-Control: cache, must-revalidate');
        header ('Pragma: public'); // HTTP/1.0

        // 엑셀 버전 지정 Excel2007 or Excel2003.
        $objWriter = PHPExcel_IOFactory::createWriter($this->phpexcel, 'Excel2007');

        // 다운로드 처리
        $objWriter->save('php://output');
    }

}
