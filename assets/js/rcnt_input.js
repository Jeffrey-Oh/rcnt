$(function() {
	// ajaxForm plugin으로 submit
    $("#rcnt_event").form_helper({
    	rules: {
            "nickName": {
                "required": true
            },
            "email": {
                "required": true,
                "maxlength": 150,
                "email": true
            },
            "trx": {
                "required": true,
                "minlength": 20
            }
        },

        messages: {
            "nickName": {
                "required": "닉네임을 입력하세요."
            },
            "email": {
                "required": "이메일을 입력하세요.",
                "maxlength": "이메일은 최대 150자 까지 입력가능합니다.",
                "email": "이메일 형식에 맞지 않습니다."
            },
            "trx": {
                "required": "지갑주소를 입력하세요.",
                "minlength": "지갑주소는 최소 20자 이상입니다."
            }
        },
        success: function(json) {
        	if (json.rt == "OK") {
	        	alert("<font color='#05a'>Success</font>", "<strong>제출이 완료 되었습니다. 참여 숫자 : " + json.number + "</strong>", function() {
					location.href = ROOT_URL + "rcnt/event_result";
	        	}, "success");
        	} else if (json.rt == "duplicate") {
                alert("<font color='#05a'>Error</font>", "<strong>" + json.msg + "</strong>", function() {
                    location.href = ROOT_URL + "rcnt/input";
                }, "error");
            } else if (json.rt == "nickName") {
                alert("<font color='#05a'>Error</font>", "<strong>" + json.msg + "</strong>", function() {
                    location.href = ROOT_URL + "rcnt/input";
                }, "error");
            } else if (json.rt == "trx") {
                alert("<font color='#05a'>Error</font>", "<strong>" + json.msg + "</strong>", function() {
                    location.href = ROOT_URL + "rcnt/input";
                }, "error");
            } else if (json.rt == "ERROR") {
                alert("<font color='#05a'>Error</font>", "<strong>" + json.msg + "</strong>", function() {
                    location.href = ROOT_URL + "rcnt/input";
                }, "error");
            } else {
        		alert("<font color='#05a'>Error</font>", "<strong>제출에 실패했습니다. <br />관리자에게 문의하세요. 에러코드 : " + json.rt + "</strong>", function() {
					location.href = ROOT_URL + "rcnt/input";
	        	}, "error");
        	}
        }
    });
});