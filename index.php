<?php

namespace block_minerva;

use block_minerva\base\course;
use block_minerva\base\logging;
use block_minerva\base\html_writer;
use block_minerva\quiz\quiz;

require_once __DIR__ . '/../../config.php';
require_once "apinfo.php";

require_login();

/* @var $USER object */
/* @var $PAGE object */
global $USER, $PAGE;

//Get course top context
$context = \context_course::instance(1);
$loggingObj = new logging($context);

$userid = $USER->id;

$PAGE->set_context($context);

echo html_writer::head();
echo html_writer::navbar();

//Start main contents
echo \html_writer::start_div("container-fluid");
echo \html_writer::start_div("row profile");
//Start left menu
echo \html_writer::start_div("col-md-2");
echo html_writer::leftmenu();
echo \html_writer::end_div(); //.col-md-2
//End left menu

//Start content
echo \html_writer::start_div("col-md-10 profile-content");
echo html_writer::tag("h2", "ダッシュボード");
echo \html_writer::start_div("row");
echo \html_writer::start_div("col-md-4 well");
echo \html_writer::tag("h3", "アクセスステータス");
//TODO
//echo \html_writer::div("あなたは今週、連続3日間ログインしました！", "alert alert-success");

echo \html_writer::start_tag("table", ["class" => "table table-borderd"]);
echo \html_writer::start_tag("tr");
echo \html_writer::tag("th", "月");
echo \html_writer::tag("th", "火");
echo \html_writer::tag("th", "水");
echo \html_writer::tag("th", "木");
echo \html_writer::tag("th", "金");
echo \html_writer::end_tag("tr");
echo \html_writer::start_tag("tr");

$week = [1, 2, 3, 4, 5];
$access_statuses = $loggingObj->access_status();
foreach ($week as $w) {
    if ($access_statuses[$w] != 0) {
        echo \html_writer::tag("td", html_writer::img_stamp("taihenyokudekimasita.png"));
    } else {
        echo \html_writer::tag("td", html_writer::img_stamp("mousukosiganbarimashou.png"));
    }
}
echo \html_writer::end_tag("tr");
echo \html_writer::end_tag("table");
echo \html_writer::end_div(); //.col-md-4 well

echo \html_writer::start_div("col-md-4 col-md-offset-1 well");
echo \html_writer::tag("h3", "最近受験した小テスト");
echo \html_writer::start_tag("table", ["class" => "table table-borderd"]);
echo \html_writer::start_tag("tr");
echo \html_writer::tag("th", "小テスト名");
echo \html_writer::tag("th", "受験した時間");
echo \html_writer::tag("th", "点数");
echo \html_writer::end_tag("tr");
$quizzes = quiz::recently_attempt($userid);

foreach ($quizzes as $quiz) {
    $grade = quiz::get_grade($quiz);
    echo \html_writer::start_tag("tr");
    echo \html_writer::tag("td", $quiz->name);
    echo \html_writer::tag("td", userdate($quiz->timelastattempt));
    echo \html_writer::tag("td", $grade);
    echo \html_writer::end_tag("tr");
}
echo \html_writer::end_tag("table");
echo \html_writer::end_div();//.col-md-4 col-md-offset-1 well

echo \html_writer::end_div();//.row

echo \html_writer::start_div("row");
echo \html_writer::start_div("col-md-4 well");
echo \html_writer::tag("h3", "小テスト受験率");
echo \html_writer::end_div();//.col-md-4 col-md-offset-1 well
echo \html_writer::end_div();//.row

echo \html_writer::start_div("row");
echo \html_writer::start_div("col-md-12");
echo \html_writer::tag("h3", "所属しているコース");
echo \html_writer::div("現時点であなたが所属しているコース一覧です。(10件まで表示しています)", "alert alert-info");
$courses = course::courses($userid);
echo \html_writer::start_tag("table", ["class" => "table table-bordered"]);
echo \html_writer::start_tag("tr", ["class" => ""]);
echo \html_writer::tag("th", "コース名");
echo \html_writer::tag("th", "ロール");
echo \html_writer::end_tag("tr");
if (!empty($courses)) {
    foreach ($courses as $course) {
        echo \html_writer::start_tag("tr", ["class" => ""]);
        echo \html_writer::tag("td",
            \html_writer::link(
                course::course_url($course->id),
                $course->fullname,
                ["target" => "_blank"]));
        echo \html_writer::tag("td", "");
        echo \html_writer::end_tag("tr");
    }
} else {
    echo \html_writer::tag("p", "どのコースにも所属していません。");
}
echo \html_writer::end_tag("table");
echo \html_writer::end_div(); //row
echo \html_writer::end_div(); //.col-md-12

echo \html_writer::end_div(); //.row profile
echo \html_writer::end_div(); // row

echo \html_writer::end_div(); //.row profile
echo \html_writer::end_div(); //.container-fluid

echo html_writer::footer();