<?php

namespace block_minerva;

use block_minerva\base\course;
use block_minerva\base\graph;
use block_minerva\base\logging;

require_once __DIR__ . '/../../config.php';
require_once "apinfo.php";

require_login();

/* @var $USER object */
/* @var $CFG object */
/* @var $PAGE object */
/* @var $OUTPUT object */
global $USER, $CFG, $PAGE, $OUTPUT;
global $APS;

$context = \context_course::instance(1);

$courseObj  = new course($context);
$loggingObj = new logging($context);
$graphObj = new graph($context);

$jses = [];

$PAGE->set_context($context);
echo \html_writer::start_tag('html');
echo \html_writer::start_tag('head');
echo \html_writer::empty_tag('meta',
    ['charset' => 'UTF-8']);
echo \html_writer::empty_tag('meta',
    ['http-equiv' => 'content-language']);
echo \html_writer::empty_tag('meta',
    ['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0']);
echo \html_writer::tag('title',
    get_string('pluginname', 'block_minerva'),
    ['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0']);
echo \html_writer::empty_tag('link',
    ['href' => new \moodle_url('css/bootstrap.min.css'), 'rel' => 'stylesheet']);
echo \html_writer::empty_tag('link',
    ['href' => new \moodle_url('css/c3.css'), 'rel' => 'stylesheet']);
echo \html_writer::empty_tag('link',
    ['href' => new \moodle_url('css/main.css'), 'rel' => 'stylesheet']);

echo \html_writer::start_tag('body');

echo \html_writer::start_div("navbar navbar-default", ["role" => "navigation"]);
echo \html_writer::start_div("navbar-header");
echo \html_writer::link(new \moodle_url('index.php'),
    get_string('pluginname', 'block_minerva'),
    ["class" => "navbar-brand"]);
echo \html_writer::end_div();
echo \html_writer::start_tag('div', ['class' => 'collapse navbar-collapse', 'id' => 'bs-example-navbar-collapse-1']);
echo \html_writer::start_tag('ul', ['class' => 'nav navbar-nav navbar-right']);

echo \html_writer::end_tag('ul');
echo \html_writer::end_div();
echo \html_writer::end_tag('nav');

echo \html_writer::start_div("container");
echo \html_writer::start_div("row profile");
echo \html_writer::start_div("col-md-3");
echo \html_writer::start_div("profile-sidebar");
echo \html_writer::div($OUTPUT->user_picture($USER, ['size'=>140, 'class' => 'img-responsive', "link" => false, "alttext" => false]),
    "profile-userpic");

echo \html_writer::start_div('popover bottom show', ['style' => 'position:relative; max-width:100%;']);
echo \html_writer::start_div('arrow');
echo \html_writer::end_div();

echo \html_writer::start_div('popover-content');
echo \html_writer::tag('p', "");
echo \html_writer::end_div();
echo \html_writer::end_div();

echo \html_writer::start_div("profile-usertitle");
echo \html_writer::div(fullname($USER), "profile-usertitle-name");
echo \html_writer::end_div();
echo \html_writer::start_div("profile-usermenu");
echo \html_writer::start_tag("ul", ["class" => "nav"]);
echo \html_writer::start_tag("li", ["class" => "active"]);
echo \html_writer::link("#",
    \html_writer::tag("i", "", ["class" => "glyphicon glyphicon-home"]) . "ダッシュボード");
echo \html_writer::end_tag("li");

foreach($APS as $ap){
    echo \html_writer::start_tag("li", ["class" => ""]);
    echo \html_writer::link(new \moodle_url("ap/" . $ap["name"] . "/"),
        \html_writer::tag("i", "", ["class" => $ap["icon"]]) . $ap["naturalname"]);
    echo \html_writer::end_tag("li");
}
echo \html_writer::end_tag("ul");
echo \html_writer::end_div();
echo \html_writer::end_div();
echo \html_writer::end_div();

echo \html_writer::start_div("col-md-9 profile-content");

echo \html_writer::start_div("col-md-6");
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
foreach($week as $w){
    if($access_statuses[$w] != 0){
        echo \html_writer::tag("td", "○");
    }else{
        echo \html_writer::tag("td", "×");
    }
}
echo \html_writer::end_tag("tr");
echo \html_writer::end_tag("table");
echo \html_writer::end_div();

echo \html_writer::start_div("col-md-6");
echo \html_writer::tag("h3", "アクセスグラフ");
echo \html_writer::div("直近7日間のアクセス回数の推移を表示しています。", "alert alert-info");
echo \html_writer::div("", "", ["id" => "access_graph"]);
//TODO
$label = ["'月'", "'火'", "'水'", "'木'", "'金'", "'土'", "'日'"];
//$label = [0,1,2,3,4,5,6];
$jses[] = $graphObj->line($label, $access_statuses, "access_graph");
echo \html_writer::end_div();

echo \html_writer::start_div("col-md-12");
echo \html_writer::tag("h3", "所属しているコース");
echo \html_writer::div("現時点であなたが所属しているコース一覧です。(10件まで表示しています)", "alert alert-info");
$courses = $courseObj->courses();
echo \html_writer::start_tag("table", ["class" => "table table-bordered"]);
echo \html_writer::start_tag("tr", ["class" => ""]);
echo \html_writer::tag("th", "コース名");
echo \html_writer::tag("th", "ロール");
echo \html_writer::end_tag("tr");
if(!empty($courses)){
    foreach($courses as $course){
        echo \html_writer::start_tag("tr", ["class" => ""]);
        echo \html_writer::tag("td",
            \html_writer::link(
                $courseObj->course_url($course->id),
                $course->fullname,
                ["target" => "_blank"]));
        echo \html_writer::tag("td", "");
        echo \html_writer::end_tag("tr");
    }
}else{
    echo \html_writer::tag("p", "どのコースにも所属していません。");
}
echo \html_writer::end_tag("table");
echo \html_writer::end_div();

echo \html_writer::end_div();
echo \html_writer::end_div();

echo \html_writer::end_div();
echo \html_writer::end_div();

//Script
echo \html_writer::script(null, new \moodle_url($CFG->wwwroot . '/blocks/minerva/js/jquery.min.js'));
echo \html_writer::script(null, new \moodle_url($CFG->wwwroot . '/blocks/minerva/js/bootstrap.min.js'));
echo \html_writer::script(null, new \moodle_url($CFG->wwwroot . '/blocks/minerva/js/d3.min.js'));
echo \html_writer::script(null, new \moodle_url($CFG->wwwroot . '/blocks/minerva/js/c3.min.js'));
foreach($jses as $js){
    echo \html_writer::script($js);
}

echo \html_writer::end_tag('body');
echo \html_writer::end_tag('html');