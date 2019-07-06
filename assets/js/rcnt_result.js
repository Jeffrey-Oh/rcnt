$(function() {
    // 당첨 여부
    $(".btn_res").click(function() {
        $.get(ROOT_URL + "rcnt/event_ajax", undefined, function(json) {
            if (json.rt != "OK") {
                alert(json.msg);
                return false;
            }

            $(".user_data").remove();

            if (json.resCnt >= 5) {
                $(".btn_res").removeClass("show");
                $(".btn_res").addClass("hide");
            }

            // 템플릿 HTML을 로드
            var template = Handlebars.compile($("#list-tmpl").html());
            // JSON에 포함된 '&lt;br/&gt;'을 검색해서 <br/>로 변경
            for (var i=0; i<json.item.length; i++) {
                
                // 댓글 아이템 항목 하나를 템플릿과 결합한다.
                var html = template(json.item[i]);
                
                // 결합된 결과를 댓글 목록에 추가한다.
                $("#event_list").append(html);
            }
        });
    });
    // setInterval(function() {
    //     $.get(ROOT_URL + "rcnt/event_ajax", undefined, function(json) {
    //         if (json.rt != "OK") {
    //             alert(json.rt);
    //             return false;
    //         }

    //         // 템플릿 HTML을 로드
    //         var template = Handlebars.compile($("#list-tmpl").html());
    //         // JSON에 포함된 '&lt;br/&gt;'을 검색해서 <br/>로 변경
    //         for (var i=0; i<json.item.length; i++) {
                
    //             // 댓글 아이템 항목 하나를 템플릿과 결합한다.
    //             var html = template(json.item[i]);
                
    //             // 결합된 결과를 댓글 목록에 추가한다.
    //             $("#event_list").append(html);
    //         }
    //     });
    // }, 10000);
});
