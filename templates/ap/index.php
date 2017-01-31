<?php

namespace block_minerva;

use block_minerva\base\logging;
use block_minerva\base\html_writer;
use block_minerva\base\security;

require_once __DIR__ . '/../../../../config.php';
require_once "../../apinfo.php";

security::check();

/* @var $USER object */
/* @var $PAGE object */
/* @var $APS object */
global $USER, $PAGE, $APS;

//Get course top context
$context = \context_course::instance(1);
$loggingObj = new logging($context);

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
echo \html_writer::end_div(); //.col-md-10
echo \html_writer::end_div(); //.row profile
//End content

echo \html_writer::end_div(); //.container-fluid


echo html_writer::footer();