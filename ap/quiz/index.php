<?php

namespace block_minerva;

use block_minerva\base\logging;
use block_minerva\base\html_writer;
use block_minerva\base\security;
use block_minerva\quiz\js;
use block_minerva\quiz\quiz;

require_once __DIR__ . '/../../../../config.php';
require_once __DIR__ . '/../../apinfo.php';

//Check logins
security::check();
/* @var $PAGE object */
global $PAGE;

//Get course top context
$context = \context_course::instance(1);

$javascripts = [];

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
echo html_writer::tag("h2", "小テスト");
echo html_writer::empty_tag("hr");
echo html_writer::tag("h4", "サマリー");
echo html_writer::start_div("col-md-3");
echo html_writer::start_div("panel panel-primary");
echo html_writer::div("今まで受験した小テストの数", "panel-heading");
echo html_writer::div(html_writer::graph("graph_total_attempted", 300, 300), "panel-body");
echo html_writer::end_div(); //.panel panel-default
echo html_writer::end_div(); //.col-md-3

echo html_writer::start_div("col-md-3");
echo html_writer::start_div("panel panel-primary");
echo html_writer::div("小テストの点数分布", "panel-heading");
echo html_writer::div(html_writer::graph("graph_total_grades", 300, 300), "panel-body");
echo html_writer::end_div(); //.panel panel-default
echo html_writer::end_div(); //.col-md-3

echo html_writer::start_div("col-md-12");
echo html_writer::tag("h4", "小テストごとに詳細分析");
echo html_writer::div("小テストを選択すると、過去の成績を分析することができます。ふりかえりに役立ててください。", "alert alert-success");
echo html_writer::start_div("panel panel-primary");

echo html_writer::div("小テスト一覧", "panel-heading");
echo html_writer::start_tag("table", ["class" => "table table-bordered"]);
echo html_writer::start_tag("tr");
echo html_writer::tag("th", "コース", ["class" => "col-md-2"]);
echo html_writer::tag("th", "小テスト", ["class" => "col-md-6"]);
echo html_writer::tag("th", "-", ["class" => "col-md-2"]);
echo html_writer::end_tag("tr");
$attempted_quizzes = quiz::get_attempted_quizzes();
foreach ($attempted_quizzes as $attempted) {
    $quiz = quiz::get_quiz_by_instance($attempted->quiz);
    echo html_writer::start_tag("tr");
    echo html_writer::tag("td", get_course($quiz->course)->fullname);
    echo html_writer::tag("td", $quiz->name);
    echo html_writer::tag("td", html_writer::link(new \moodle_url("detail.php", ["quiz_instance" => $quiz->id]), "分析する", ["class" => "btn btn-success"]));
    echo html_writer::end_tag("tr");
}
echo html_writer::end_tag("table");

echo html_writer::end_div(); //.panel panel-default
echo html_writer::end_div(); //.col-md-12

echo \html_writer::end_div(); //.col-md-10

echo \html_writer::end_div(); //.row profile
//End content

echo \html_writer::end_div(); //.container-fluid

$javascripts[] = js::total_attempted();
$javascripts[] = js::total_grades();

echo html_writer::footer($javascripts);