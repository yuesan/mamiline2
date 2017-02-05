<?php

namespace block_minerva;

/* @var $USER object */
/* @var $PAGE object */
/* @var $CFG object */
global $USER, $PAGE, $CFG;

use block_minerva\base\logging;
use block_minerva\base\html_writer;
use block_minerva\base\security;
use block_minerva\quiz\js;
use block_minerva\quiz\quiz;

require_once __DIR__ . '/../../../../config.php';
require_once __DIR__ . '/../../apinfo.php';
require_once $CFG->libdir . '/gradelib.php';

//Check logins
security::check();

//Get course top context
$context = \context_course::instance(1);
$loggingObj = new logging($context);

//params
$quiz_instanceid = required_param("quiz_instance", PARAM_INT);

//quiz
$quiz = quiz::get_quiz_by_instance($quiz_instanceid);
if ($quiz == false) {
    print_error("quiz_not_found", "block_minerva");
}
//attempts
$attempts = quiz::get_attempts($quiz->id);

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
echo html_writer::tag("h2", "小テストごとの分析結果");
echo html_writer::empty_tag("hr");
echo html_writer::tag("h4", html_writer::link(new \moodle_url($CFG->wwwroot . "/mod/quiz/view.php", ["id" => $quiz->id]), $quiz->name));

echo html_writer::start_div("col-md-3");
echo html_writer::start_div("panel panel-primary");
echo html_writer::div("最高点", "panel-heading");
$grades = grade_get_grades($quiz->course, "mod", "quiz", $quiz->id, $USER->id);
if ($attempts) {
    echo html_writer::div(quiz::get_best_grade($quiz) . "/" . $grades->items[0]->grademax, "panel-body");
} else {
    echo html_writer::div("この小テストはまだ受験していません。", "panel-body");
}
echo html_writer::end_div(); //.panel panel-default
echo html_writer::end_div(); //.col-md-3

echo \html_writer::end_div(); //.col-md-10

echo \html_writer::end_div(); //.row profile
//End content

echo \html_writer::end_div(); //.container-fluid

$javascripts[] = js::total_attempted();
$javascripts[] = js::total_grades();

echo html_writer::footer($javascripts);