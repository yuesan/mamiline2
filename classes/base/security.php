<?php
namespace block_minerva\base;

defined('MOODLE_INTERNAL') || die();

class security
{
    public static function check()
    {
        if (!isloggedin()) {
            redirect("/", "このページはログインが必要です。", \core\output\notification::NOTIFY_ERROR);
        }
    }
}