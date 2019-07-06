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
    <link rel="stylesheet" href="<?=base_url('assets/css/input.css')?>" />
</head>
<body>
    <!-- 전체를 감싸는 div -->
    <div class="container-fluid">
        <!-- header -->
        <header>
            <div class="head_img">
                <a href="https://rcnt.tech" target="_blank">
                    <img src="<?=base_url('assets/img/rcnt/logo.png')?>" width="400" height="400">
                </a>
            </div>
        </header>
        <!-- //header -->
        <!-- contents -->
        <aside class="side_contents">
            <div class="fl text-center">
                <span class="intro">RCNT  /  REO  Introduce</span><br /><br />
                <span class="text1">RCNT (REO) 암호화폐를 사용하는 소셜커머스 플랫폼을 기반으로 Token Economy를 실현합니다.</span><br /><br />
                <span class="text2">블록체인 기반  분산형 네트워크 TRON의 DApp으로 출시 합니다. RCNT는 TRON생태계에 존재하는 많은 토큰들과 파트너쉽 역시 가능합니다.
                    <br /><span class="dash"></span><br />
                    TRON DAPP<br /><br />
                </span>
                <span class="text3">RCNT Mobile Application은블록체인 소셜커머스 / 블로그 미디어 / 보상 / 광고 / 에어드랍 & 증강현실 게임 위 5가지가 결합된 멀티 플랫폼입니다.
                    <br /><span class="dash"></span><br />
                    Multi-Platform<br /><br />
                </span>
                <span class="text4">RCNT(REO) 암호화폐를 이용하여 유저와 가맹점을 연결해주며 전 세계 음식전문 콘텐츠를 다루는 블로그 미디어와 연동되어 다양한 서비스를 제공합니다.
                    <br /><span class="dash"></span><br />
                    Contents<br />
                </span>
            </div>
            <div class="fr text-center">
                <div class="input_header text-center">RCNT REO LINK LIST</div>
                <span class="link_img_1">
                    <img src="<?=base_url('assets/img/rcnt/rcnt.png')?>" width="50" height="50">
                </span>
                <a href="https://rcnt.tech/" class="homepage" target="_blank">RCNT REO OFFICIAL HOMEPAGE</a><br /><br />
                <span class="link_img_2">
                    <img src="<?=base_url('assets/img/rcnt/telegram.jpg')?>" width="50" height="50">
                </span>
                <a href="https://t.me/rcntreokorea" class="telegram" target="_blank">RCNT REO KOREA TELEGRAM</a><br /><br />
                <span class="link_img_3">
                    <img src="<?=base_url('assets/img/rcnt/kakao.jpg')?>" width="50" height="50">
                </span>
                <a href="https://open.kakao.com/o/gis7Wzrb" class="kakao" target="_blank">RCNT REO KAKAO TALK</a><br /><br />
                <span class="link_img_4">
                    <img src="<?=base_url('assets/img/rcnt/instagram.jpg')?>" width="50" height="50">
                </span>
                <a href="https://www.instagram.com/rcntreohouse/" class="instagram" target="_blank">RCNT REO HOUSE INSTAGRAM</a><br /><br />
                <span class="link_img_5">
                    <img src="<?=base_url('assets/img/rcnt/medium.png')?>" width="50" height="50">
                </span>
                <a href="https://medium.com/@rcntblockchain" class="medium" target="_blank">RCNT REO MEDIUM</a><br /><br />
                <span class="link_img_6">
                    <img src="<?=base_url('assets/img/rcnt/twitter.png')?>" width="50" height="50">
                </span>
                <a href="https://twitter.com/TonyRcnt" class="twitter" target="_blank">RCNT REO TWITTER</a><br /><br />
            </div>
        </aside>
        <section id="contents">
            <div class="container">
                <!-- 메인 컨텐츠 삽입 -->

                <!-- 여기에 contents 삽입 시작 -->

                <div class="form-box">
                    <div class="input_header text-center color4">RCNT 선착순 추첨 이벤트</div>
                </div>
                <br />

                <div class="form-box input_box">
                    <form action="<?=base_url(['rcnt', 'event_ok'])?>" method="POST" id="rcnt_event" class="form-horizontal" role="form">
                        <div class="input_header text-center color1">아래 폼을 작성해주세요.</div>
                        <!-- 닉네임 -->
                        <div class="form-group">
                            <label for="nickName" class="col-md-2 col-md-offset-2 control-label color1">닉네임</label>
                            <div class="col-md-8 form-inline">
                                <input type="text" name="nickName" id="nickName" class="form-control" placeholder="@JeffreyOh" value="@" />
                            </div>
                        </div>
                        <!-- 이메일 -->
                        <div class="form-group">
                            <label for="email" class="col-md-2 col-md-offset-2 control-label color1">이메일</label>
                            <div class="col-md-8 form-inline">
                                <input type="email" name="email" id="email" class="form-control" placeholder="이메일을 입력해 주세요." />
                            </div>
                        </div>
                        <!-- 트론주소 -->
                        <div class="form-group">
                            <label for="trx" class="col-md-2 col-md-offset-2 control-label color1">트론주소</label>
                            <div class="col-md-8 form-inline">
                                <input type="text" name="trx" id="trx" class="form-control" placeholder="토큰추가 가능한 지갑(거래소X)" />
                            </div>
                        </div>
                        <button type="submit" class="btn btn_color3 btn_submit" title="제출">제출</button>
                    </form>
                </div>

                <!-- ===== 개인 작업 영역 ===== -->
                <!-- 여기에 contents 삽입 끝 -->

            </div>
        </section>
        <!-- //contents -->
    </div>
    <!-- // 전체를 감싸는 div -->
    <?php $this->load->view("_common/script"); ?>
    <!-- 개인 js 참조 영역 -->
    <script src="<?=base_url('assets/js/rcnt_input.js')?>"></script>
</body>
</html>
