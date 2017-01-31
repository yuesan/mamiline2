<?php

namespace block_minerva\base;

class html_writer extends \html_writer
{
    public static function img_stamp($filename)
    {
        global $CFG;

        return \html_writer::img(new \moodle_url($CFG->wwwroot . "/blocks/minerva/images/stamp/" . $filename),
            "たいへんよくできました", ["class" => "img-circle img-responsive"]);
    }

    public static function head()
    {
        global $CFG;

        echo \html_writer::start_tag('html');
        $html = \html_writer::start_tag('head');
        $html .= \html_writer::empty_tag('meta',
            ['charset' => 'UTF-8']);
        $html .= \html_writer::empty_tag('meta',
            ['http-equiv' => 'content-language']);
        $html .= \html_writer::empty_tag('meta',
            ['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0']);
        $html .= \html_writer::tag('title',
            get_string('pluginname', 'block_minerva'),
            ['name' => 'viewport', 'content' => 'width=device-width, initial-scale=1.0']);
        $html .= \html_writer::empty_tag('link',
            ['href' => $CFG->wwwroot . "/blocks/minerva/css/bootstrap.min.css", 'rel' => 'stylesheet']);
        $html .= \html_writer::empty_tag('link',
            ['href' => $CFG->wwwroot . '/blocks/minerva/css/main.css', 'rel' => 'stylesheet']);
        $html .= html_writer::end_tag("head");
        $html .= \html_writer::start_tag('body');

        return $html;
    }

    public static function navbar()
    {
        $html = \html_writer::start_div("navbar navbar-default", ["role" => "navigation"]);
        $html .= \html_writer::start_div("navbar-header");
        $html .= \html_writer::link(new \moodle_url('index.php'),
            get_string('pluginname', 'block_minerva'),
            ["class" => "navbar-brand"]);
        $html .= \html_writer::end_div();
        $html .= \html_writer::start_tag('div', ['class' => 'collapse navbar-collapse', 'id' => 'bs-example-navbar-collapse-1']);
        $html .= \html_writer::start_tag('ul', ['class' => 'nav navbar-nav navbar-right']);
//@TODO menu
        $html .= \html_writer::end_tag('ul');
        $html .= \html_writer::end_div();
        $html .= \html_writer::end_div();

        return $html;
    }

    public static function leftmenu()
    {
        global $USER, $OUTPUT, $CFG, $APS;

        $html = \html_writer::start_div("profile-sidebar");
        $html .= \html_writer::div($OUTPUT->user_picture($USER, ['size' => 140, 'class' => 'img-responsive', "link" => false, "alttext" => false]),
            "profile-userpic");

        $html .= \html_writer::start_div("profile-usertitle");
        $html .= \html_writer::div(fullname($USER), "profile-usertitle-name");
        $html .= \html_writer::end_div(); //.profile-usertitle
        $html .= \html_writer::start_div("profile-usermenu");
        $html .= \html_writer::start_tag("ul", ["class" => "nav"]);
        $html .= \html_writer::start_tag("li", ["class" => ""]);
        $html .= \html_writer::link(new \moodle_url($CFG->wwwroot . "/blocks/minerva/"),
            \html_writer::tag("i", "", ["class" => "glyphicon glyphicon - home"]) . "ダッシュボード");
        $html .= \html_writer::end_tag("li");

        foreach ($APS as $ap) {
            $html .= html_writer::start_tag("li", ["class" => ""]);
            $html .= html_writer::link(new \moodle_url($CFG->wwwroot . "/blocks/minerva/ap/" . $ap["name"] . "/"),
                html_writer::tag("i", "", ["class" => $ap["icon"]]) . $ap["naturalname"]);
            $html .= html_writer::end_tag("li");
        }
        $html .= html_writer::end_tag("ul");
        $html .= html_writer::end_div();//.profile-usermenu
        $html .= html_writer::end_div();//.profile-sidebar

        return $html;
    }

    public static function footer($jses = [])
    {
        global $CFG;

        $html = \html_writer::script(null, new \moodle_url($CFG->wwwroot . '/blocks/minerva/js/jquery.min.js'));
        $html .= \html_writer::script(null, new \moodle_url($CFG->wwwroot . '/blocks/minerva/js/bootstrap.min.js'));
        $html .= \html_writer::script(null, new \moodle_url($CFG->wwwroot . '/blocks/minerva/js/Chart.min.js'));

        foreach ($jses as $js) {
            $html .= \html_writer::script($js);
        }

        $html .= \html_writer::end_tag('body');
        $html .= \html_writer::end_tag('html');

        return $html;
    }

    public static function graph($id, $width = 200, $height = 200)
    {
        return html_writer::tag("canvas", " ", ["id" => $id, "width" => $width, "height" => $height]);
    }
}