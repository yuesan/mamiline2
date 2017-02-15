<?php

namespace block_minerva;

use block_minerva\base\logging;
use block_minerva\base\security;
use block_minerva\timeline\html_writer;
use block_minerva\timeline\timeline;
use block_minerva\quiz\attempts;
use block_minerva\quiz\quiz;
use block_minerva\timeline\filter;

/* @var $USER object */
/* @var $CFG object */
/* @var $PAGE object */
/* @var $OUTPUT object */
global $USER, $CFG, $PAGE, $OUTPUT;

require_once __DIR__ . '/../../../../config.php';
require_once "../../apinfo.php";
require_once __DIR__ . '/../../../../grade/querylib.php';
require_once "$CFG->libdir/gradelib.php";
require_once "$CFG->libdir/grade/grade_item.php";
require_once "$CFG->libdir/grade/constants.php";

$context = \context_course::instance(1);

security::check();

$loggingObj = new logging($context);

$jses = [];

$PAGE->set_context($context);
echo html_writer::head();
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

$timelineObj = new timeline($context);
$dataes = $timelineObj->myself();
$_line_date = null;
foreach ($dataes as $data) {
    //Time Separator
    $line_date = userdate($data->timecreated, "%Y-%m-%d");
    if ($line_date !== $_line_date) {
        echo html_writer::time($data->timecreated);
    }
    $_line_date = $line_date;

    $date = userdate($data->timecreated);
    switch ($data->component) {
        case 'core' :
            if ($data->action === "loggedin") {
                echo html_writer::panel_primary("ログイン", $date . "にMoodleへログインしました", "", "glyphicon-lock");
            }
            break;
        case 'mod_quiz' :
            echo filter\quiz::do($data);
            $jses[] = filter\quiz::script($data);
            break;
        case "mod_forum" :
            echo filter\forum::do($data);
            break;

        case "mod_feedback" :
            echo filter\feedback::do($data);
            break;
    }
}

echo \html_writer::end_div();

echo \html_writer::end_div();

echo \html_writer::end_div();

echo \html_writer::end_div();
echo \html_writer::end_div();

//Script
echo html_writer::footer($jses);

echo \html_writer::end_tag('body');
echo \html_writer::end_tag('html');