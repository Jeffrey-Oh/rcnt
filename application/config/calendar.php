<?php defined('BASEPATH') OR exit('No direct script access allowed');

$config['calendar'] = array(
    // 이전,다음달 링크 표시 여부
    'show_next_prev'  => TRUE,
    // 이전,다음달 링크의 URL
    'next_prev_url'   => '/ex27_calendar/month/',
    // 이번달 이외의 날짜 표시 여부
    'show_other_days' => TRUE,

    /** 달력 출력을 위한 HTML 템플릿 */
    'template' => [

        // 전체 테이블 태그 열기,닫기
        'table_open'    => '<table class="calendar" border="0" cellpadding="0" cellspacing="0">',
        'table_close'   => '</table>',

        // 이전/다음달 링크, 현재 월 이름 표시 행
        'heading_row_start'     => '<tr class="heading-row">',
        'heading_previous_cell' => '<th><a href="{previous_url}">&lt;&lt;</a></th>',
        'heading_title_cell'    => '<th colspan="{colspan}">{heading}</th>',
        'heading_next_cell'     => '<th><a href="{next_url}">&gt;&gt;</a></th>',
        'heading_row_end'       => '</tr>',

        // 요일이름 표시 행
        'week_row_start' => '<tr class="week-row">',
        'week_day_cell'  => '<td>{week_day}</td>',
        'week_row_end'   => '</tr>',

        // 달력의 각 날짜 행
        'cal_row_start' => '<tr>',
        'cal_row_end'   => '</tr>',

        // 일반 날짜를 출력하기 위한 칸의 <td>
        'cal_cell_start'        => '<td>',
        'cal_cell_end'          => '</td>',
        // 링크가 적용되는 일반 날짜
        'cal_cell_content'      => '<a href="{content}">{day}</a>',
        // 링크가 적용되지 않는 일반 날짜
        'cal_cell_no_content'   => '{day}',
        // 빈칸 (show_other_days가 FALSE인 경우 사용됨)
        'cal_cell_blank'        => '&nbsp;',
        
        // 오늘 날짜를 출력하기 위한 칸의 <td>
        'cal_cell_start_today'      => '<td class="today-cell">',
        'cal_cell_end_today'        => '</td>',
        // 링크를 포함하는 오늘 날짜
        'cal_cell_content_today'    => '<div class="today-content"><a href="{content}">{day}</a></div>',
        // 링크를 포함하지 않는 오늘 날짜
        'cal_cell_no_content_today' => '<div class="today-content">{day}</div>',
        
        // 이번달이 아닌 날짜를 위한 <td>
        'cal_cell_start_other'  => '<td class="other-month">',
        'cal_cell_end_other'    => '</td>',
        // 이번달이 아닌 날짜
        'cal_cell_other'        => '<div class="other-month-day">{day}</day>'
        
    ]
);

