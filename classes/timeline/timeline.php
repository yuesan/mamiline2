<?php

namespace block_minerva\timeline;

defined('MOODLE_INTERNAL') || die();

use block_minerva\timeline\dao\log;
use block_minerva\timeline\dao\standard_log;

class timeline
{
    private $logObject;

    function __construct($context)
    {
        $this->logObject = new standard_log($context);
    }

    public function myself()
    {
        return $this->logObject->myself();
    }

    public static function get_userlink($data)
    {
        global $USER, $CFG;

        $user = \core_user::get_user($data->userid);
        if ($user->id == $USER->id) {
            return html_writer::link(new \moodle_url($CFG->wwwroot . "/user/profile.php", ["id" => $user->id]), "あなた");
        } else {
            return html_writer::link(new \moodle_url($CFG->wwwroot . "/user/profile.php", ["id" => $user->id]), fullname($user));
        }
    }
}