<?php defined('BASEPATH') OR exit('No direct script access allowed');?>
<!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge, chrome=1">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>RCNT 이벤트 참여 페이지</title>
    <link rel="apple-touch-icon" href="<?=base_url('assets/img/rcnt/rcnt.png')?>" /><!-- 아이폰용 아이콘 적용 -->
    <link rel="shortcut icon" href="<?=base_url('assets/img/rcnt/rcnt.png')?>" /><!-- 안드로이드용 아이콘 적용 -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Noto+Sans+KR" />
    <link rel="stylesheet" href="<?=base_url('assets/css/bootstrap.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('assets/css/bootstrap-theme.min.css')?>">
    <link rel="stylesheet" href="<?=base_url('plugins/ajax/ajax_helper.css')?>" /><!-- ajax helper -->
    <link rel="stylesheet" href="<?=base_url('plugins/sweetalert/sweetalert2.min.css')?>" />
    <link rel="stylesheet" href="<?=base_url('assets/css/common.css')?>" />
    <link rel="stylesheet" href="<?=base_url('assets/css/result.css')?>" />
</head>
<body>
    <!-- 전체를 감싸는 div -->
    <div class="container-fluid">
        <!-- header -->
        <header id="head_top">
	<?php if ($master == true) { ?>
            <form action="<?=base_url(['rcnt', 'btn_text'])?>">
                <button class="btn btn-lg bg_color2 color8 btn_text" type="submit">당첨자 Excel</button>
            </form>
        <?php } ?>
            <div class="form-box">
                <div class="input_header text-center color4">RCNT 선착순 추첨 이벤트<br />(텔레그램 미참여자 당첨제외)</div>
            </div>
        <?php if ($resCnt >= 20) { ?>
            <button class="btn btn-lg bg_color2 color8 btn_res show">추첨하기 !!!</button>
        <?php } ?>
        </header>
        <!-- //header -->
        <!-- contents -->
        <section id="contents" class="container">
            <div class="result_list">
                <table class="table table-hover">
                    <tbody id="event_list">
                        <tr>
                            <th class="text-center wid25 pd20 color1">참여 번호</th>
                            <th class="text-center wid25 pd20 color1">닉네임</th>
                            <th class="text-center wid25 pd20 color1">참여 시각</th>
                            <th class="text-center wid25 pd20 color1">당첨 여부</th>
                        </tr>
		    <?php if (!empty($item)) { ?>
                    <?php for ($i = 0; $i < count($item); $i++) { ?>
                        <tr class="user_data">
                            <td class="text-center pd20"><?=$item[$i]->usersId?></td>
                            <td class="text-center pd20"><?=$item[$i]->nickName?></td>
                            <td class="text-center pd20"><?=$item[$i]->regDate?></td>
                            <?php if ($item[$i]->winner != null) { ?>
                                <td class="text-center pd20"><?=$item[$i]->winner?></td>
                            <?php } else { ?>
                                <td class="text-center pd20"></td>
                            <?php } ?>
                        </tr>
                    <?php } ?>
		    <?php } else { ?>
		        <tr class="user_data">
			    <td colspan="4" class="text-center pd20">참여자 없음</td>
			</tr>
		    <?php } ?>
                    </tbody>
                </table>
            </div>
        </section>
        <!-- //contents -->
    </div>
    <!-- // 전체를 감싸는 div -->
    <?php $this->load->view("_common/script"); ?>
    <!-- 개인 js 참조 영역 -->
    <script src="<?=base_url('plugins/handlebars/handlebars-v4.0.11.js')?>"></script>
    <script src="<?=base_url('assets/js/handlebar_register.js')?>"></script>
    <script src="<?=base_url('assets/js/rcnt_result.js')?>"></script>
    <script type="text/x-handlebars-template" id="list-tmpl">
        <tr class="user_data">
            <td class="text-center pd20">{{usersId}}</td>
            <td class="text-center pd20">{{nickName}}</td>
            <td class="text-center pd20">{{regDate}}</td>
            {{#x-winner winner}}
            <td class="text-center pd20">{{winner}}</td>
            {{else}}
            <td class="text-center pd20"></td>
            {{/x-winner}}
        </tr>
    </script>
</body>
</html>
