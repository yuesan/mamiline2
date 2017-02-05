<?php

namespace block_minerva;

use block_minerva\base\course;
use block_minerva\base\graph;
use block_minerva\base\logging;
use block_minerva\base\quiz;
use block_minerva\timeline\html_writer;
use block_minerva\timeline\timeline;

require_once __DIR__ . '/../../../../config.php';
require_once "../../apinfo.php";

require_login();

/* @var $USER object */
/* @var $CFG object */
/* @var $PAGE object */
/* @var $OUTPUT object */
global $USER, $CFG, $PAGE, $OUTPUT;
global $APS;

$context = \context_course::instance(1);

$loggingObj = new logging($context);
$graphObj = new graph($context);

$userid = $USER->id;

$jses = [];

$PAGE->set_context($context);
echo \html_writer::start_tag('html');
echo html_writer::head();
echo \html_writer::start_tag('body');
echo html_writer::navbar();

echo \html_writer::start_div("container-fluid");
echo \html_writer::start_div("row profile");
echo \html_writer::start_div("col-md-2");
echo html_writer::leftmenu();
echo \html_writer::end_div();//.col-md-2

echo \html_writer::start_div("col-md-9 profile-content");
echo \html_writer::tag("h3", "タイムライン");

echo \html_writer::start_div("timeline");
//line component
echo html_writer::line();
//Separator
echo \html_writer::start_div("separator text-mute");
echo \html_writer::tag("time", "26. 3. 2015");
echo \html_writer::end_div();

$timelineObj = new timeline($context);
$dataes = $timelineObj->myself();
foreach ($dataes as $data) {
    $date = userdate($data->timecreated);
    switch ($data->component) {
        case 'core' :
            if ($data->action == "loggedin") {
                echo html_writer::panel_success("", "ログイン", $date . "にMoodleへログインしました");
            }
            break;
        case 'mod_quiz' :
            if ($data->target == "attempt") {
                $context = \context_module::instance($data->contextinstanceid);
                $quizObj = new quiz($context);
                $attempt = $quizObj->attempt($data->objectid);
                $quiz = $quizObj->quiz($attempt->quiz);
                $grade = $quizObj->grade($quiz, $userid);
                $max_grade = quiz_format_grade($quiz, $quiz->grade);
                echo html_writer::panel_success("",
                    "小テストを受験",
                    $date . "に小テスト「" . $quiz->name . "」を受験しました。点数は" . $grade . "(" . $max_grade . "点中)でした。");
            }
            break;
    }
}

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
foreach ($jses as $js) {
    echo \html_writer::script($js);
}

echo \html_writer::end_tag('body');
echo \html_writer::end_tag('html');