<?php
namespace block_minerva\base;

class security
{
    public static function check()
    {
        if (!isloggedin()) {
            redirect("/", "このページはログインが必要です。", \core\output\notification::NOTIFY_ERROR);
        }
    }
}