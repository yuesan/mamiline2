<?php

namespace block_minerva;

use block_minerva\base\course;
use block_minerva\base\graph;
use block_minerva\base\logging;
use block_minerva\timeline\html_writer;

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
echo \html_writer::end_div();// .col-md-2

echo \html_writer::start_div("col-md-9 profile-content");
echo \html_writer::tag("h3", "小テスト");
echo \html_writer::end_div();//.col-md-9 profile-content
echo \html_writer::end_div();//.row profile

echo \html_writer::end_div();//.container-fluid

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