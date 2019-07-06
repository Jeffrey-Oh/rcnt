<?php defined('BASEPATH') OR exit('No direct script access allowed');

/*---------------------------------------------------------
 | 파일 업로드 처리
 | @param  <input type="file">요소에 부여한 name속성값
 ----------------------------------------------------------*/
if ( ! function_exists('file_upload')) {
    function file_upload($key)
    {
        /** 1) 업로드 설정 */
        // 전체 환경설정 정보에서 필요한 정보만 추출
        // --> "/config/upload.php"의 내용만 추출된다.
        $CI =& get_instance();
        $config = $CI->config->item('upload');

        // 파일이 업로드 될 폴더가 존재하지 않는다면 생성한다.
        // --> './files/upload/' 경로에 대한 처리가 수행된다.
        if (!is_dir($config['upload_path'])) {
            // 경로, 퍼미션, 하위폴더 생성 여부 설정
            mkdir($config['upload_path'], 0766, TRUE);
        }

        // 업로드 라이브러리에 환경설정 정보 등록
        $CI->upload->initialize($config);

        /** 2) 업로드 수행 및 결과 처리 */
        $result = $CI->upload->do_upload($key); // 업로드 수행 --> 실패시 FALSE 리턴됨.
        if (!$result) { return FALSE; }         // 실패시 FALSE 리턴
        $data = $CI->upload->data();            // 업로드 결과 데이터 조회

        // 파일 전체 경로에서 CI의 ROOT경로를 제외한 값으로 대체
        // --> 윈도우,리눅스 호환을 위해서 CI루트경로에서 역슬래시를 슬래시로 변경
        $root_path = str_replace("\\", "/", FCPATH);
        $data['file_path'] = str_replace($root_path, '', $data['full_path']);
        
        // 첫 글자가 "/"라면 강제로 삭제 (window/linux 호환처리)
        if (substr($data['file_path'], 0, 1) == "/") {
            $data['file_path'] = substr($data['file_path'], 1);
        }

        // 처리결과 리턴
        return $data;
    }
}

// /*---------------------------------------------------------
//  | 파일 멀티 업로드 처리
//  | @param  <input type="file[]">요소에 부여한 name속성값
//  ----------------------------------------------------------*/

// if ( ! function_exists('file_multi_upload')) {
//     function file_multi_upload($boardId, $key)
//     {
//         /** 1) 업로드 설정 */
//         // 전체 환경설정 정보에서 필요한 정보만 추출
//         // --> "/config/upload.php"의 내용만 추출된다.
//         $CI =& get_instance();
//         $files = $_FILES[$key];
//         $cpt = count($files['name']);
//         $config = $CI->config->item('upload');
//         // 파일 전체 경로에서 CI의 ROOT경로를 제외한 값으로 대체
//         // --> 윈도우,리눅스 호환을 위해서 CI루트경로에서 역슬래시를 슬래시로 변경
//         // $root_path = str_replace("\\", "/", FCPATH);

//         for($i=0; $i<$cpt; $i++) {
//             $files['name']= $files['name'][$i];
//             $files['type']= $files['type'][$i];
//             $files['tmp_name']= $files['tmp_name'][$i];
//             $files['error']= $files['error'][$i];
//             $files['size']= $files['size'][$i];

//         // 파일이 업로드 될 폴더가 존재하지 않는다면 생성한다.
//         // --> './files/upload/' 경로에 대한 처리가 수행된다.
//         if (!is_dir($config['upload_path'])) {
//             // 경로, 퍼미션, 하위폴더 생성 여부 설정
//             mkdir($config['upload_path'], 0766, TRUE);
//         }

//         // 업로드 라이브러리에 환경설정 정보 등록
//         $CI->upload->initialize($config);

//         /** 2) 업로드 수행 및 결과 처리 */
//         $upload = $CI->upload->do_upload($key); // 업로드 수행 --> 실패시 FALSE 리턴됨.
//         if (!$upload) { return FALSE; }         // 실패시 FALSE 리턴
//         $data = $CI->upload->data();            // 업로드 결과 데이터 조회

//         if (!empty($upload)) {
//                 // 원본 파일명
//                 $CI->file_dao->orginName = $data['client_name'];
//                 // 파일이 저장된 경로
//                 $filename = $data["file_name"];
//                 $CI->file_dao->fileDir = "./files/upload/".$filename;
//                 $CI->file_dao->fileName = $filename;
//                 // 파일 형식
//                 $CI->file_dao->contentType = $data['file_type'];
//                 $CI->file_dao->fileSize = $data["file_size"];
//                 // 등록일시
//                 $CI->file_dao->regDate = '{now()}';
//                 // 변경일시
//                 $CI->file_dao->editDate = '{now()}';
//                 // 파일이 속한 게시글의 일련번호
//                 $CI->file_dao->boardId = $boardId;
//                 // 작성자의 일련번호
//                 $CI->file_dao->memberId = $CI->session->userdata("user_info")->memberId;

//                 // 데이터 저장하기
//                 $result = $CI->file_dao->insert();
//                 if (!$result) {
//                     // 업로드 된거 다시 삭제
//                     if (is_file($CI->file_dao->fileDir)) {
//                         unlink($CI->file_dao->fileDir);
//                     }
//                     return false;
//                 }
//             }

//         // $data['file_path'] = str_replace($root_path, '', $data['full_path']);
        
//         // // 첫 글자가 "/"라면 강제로 삭제 (window/linux 호환처리)
//         // if (substr($data['file_path'], 0, 1) == "/") {
//         //     $data['file_path'] = substr($data['file_path'], 1);
//         // }
//         }

//         return true;
//     }
// }

/*---------------------------------------------------------
 | 썸네일 이미지 생성
 | @param  <input type="file">요소에 부여한 name속성값
 ----------------------------------------------------------*/
if (!function_exists('thumbnail')) {
    function thumbnail($source_image)
    {
        /** 1) 썸네일 생성 초기화 */
        // 대용량 이미지 처리시 메모리 부족을 방지하기 위해
        // 메모리 사용량을 무제한으로 해제한다.
        // --> 일부 호스팅에서는 ini_set()함수에 대한 권한이 없을 수 있다.
        ini_set('memory_limit','-1');

        // 현재 컨트롤러에 대한 참조 얻기
        $CI =& get_instance();

        // 전체 환경 설정 정보에서 썸네일 관련 정보만 추출
        $config = $CI->config->item('thumbnail');

        // 설정정보에 원본파일 경로 추가
        $config['source_image'] = $source_image;

        // 썸네일이 생성될 폴더가 없다면 생성한다.
        if (!is_dir($config['new_image'])) {
            mkdir($config['new_image'], 0777, true);
            chmod($config['new_image'], 0777);
        }

        /** 2) 생성될 경로를 조합하여 해당 위치에 파일이 이미 존재하는지 검사 */
        $p1 = strrpos($source_image, "/");
        $p2 = strrpos($source_image, ".");
        $filename = substr($source_image, $p1+1, $p2-$p1-1);
        $extname = substr($source_image, $p2);
        // 경로조합 --> 저장될폴더+파일이름+파일명 뒤에 붙을 지시자+확장자
        $thumb = $config['new_image'].$filename.$config['thumb_marker'].$extname;

        /** 3) 파일의 존재여부를 확인하여 썸네일 생성 */
        // 같은 이름의 썸네일 파일이 존재하지 않을 경우만 생성
        if (!file_exists($thumb)) {
            // 설정 정보를 라이브러리에 로드시킴
            $CI->image_lib->initialize($config);
            // 이미지 리사이즈(성공시 true, 실패시 false)
            $result = $CI->image_lib->resize();
            // 이미지 생성 실패시
            if (!$result) { return FALSE; }
        }

        // 생성된 썸네일의 경로를 리턴한다.
        return $thumb;
    }
}








