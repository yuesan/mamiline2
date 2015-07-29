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
    ['href' => "../../css/bootstrap.min.css", 'rel' => 'stylesheet']);
echo \html_writer::empty_tag('link',
    ['href' => '../../css/c3.css', 'rel' => 'stylesheet']);
echo \html_writer::empty_tag('link',
    ['href' => '../../css/main.css', 'rel' => 'stylesheet']);

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
echo \html_writer::start_tag("li", ["class" => ""]);
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
echo \html_writer::tag("h3", "タイムライン");

echo \html_writer::start_div("timeline");
//line component
echo html_writer::line();
//Separator
echo \html_writer::start_div("separator text-mute");
echo \html_writer::tag("time", "26. 3. 2015");
echo \html_writer::end_div();



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