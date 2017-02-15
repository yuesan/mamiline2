<?php

namespace block_minerva\timeline\filter;

use block_minerva\timeline\html_writer;
use block_minerva\timeline\timeline;

defined('MOODLE_INTERNAL') || die();

/**
 * filter class for forum.
 *
 * Class forum
 * @package block_minerva\timeline\filter
 */
class feedback
{
    public static function do($data)
    {
        global $CFG, $DB;

        $user_link = timeline::get_userlink($data);

        $html = "";

        if ($data->action === "submitted" && $data->target === "response") {
            $feedback_completed = $DB->get_record("feedback_completed", ["id" => $data->objectid]);
            if(!$feedback_completed){
                return "";
            }
            $feedback = $DB->get_record("feedback", ["id" => $feedback_completed->feedback]);
            $feedback_url = html_writer::link(new \moodle_url($CFG->wwwroot . "/mod/forum/view.php", ["id" => $feedback->id]), $feedback->name);

            $title = "フィードバックに回答しました";
            $content = $user_link . "がフィードバック(" . html_writer::link($feedback_url, $feedback->name) . ")に回答しました。";
            $footer = "";

            $html = html_writer::panel_success($title, $content, $footer, " glyphicon-bullhorn");
        }

        return $html;
    }
}