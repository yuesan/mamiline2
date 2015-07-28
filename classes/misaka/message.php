<?php

namespace block_misaka;

use block_misaka\rules\greeting;
use block_misaka\rules\quizmod;
use block_misaka\rules\weather;

defined('MOODLE_INTERNAL') || die();

class message
{
    public $message_text;

    function __construct($context){
        $this->context = $context;
    }

    function generate(){
        global $USER;

        if($USER->id == 0){
            $message = new \stdClass();
            $message->score = 0;
            $message_text = null;

            return $message;
        }

        $message_text = "";
        $message_score = 0;
        $messages = [];

        $greeting_obj = new greeting();
        $messages[] = $greeting_obj->get();

        $quiz_obj = new quizmod();
        $messages[] = $quiz_obj->get();

        $weather_obj = new weather();
        $messages[] = $weather_obj->get();

        foreach($messages as $message){
            $message_text .= $message->text . '<hr>';
            $message_score += $message->score;
        }

        $message = new \stdClass();
        $message->text = $message_text;
        $message->score = $message_score;

        return $message;
    }

    function count_action($component, $target)
    {
        global $DB, $USER;

        $count_login = $DB->count_records_sql(
            "SELECT COUNT('id') FROM {logstore_standard_log}
              WHERE userid = :userid
                AND timecreated > (UNIX_TIMESTAMP(NOW()) - 259200)
                AND component = :component
                AND target = :target;
             "
            , ['userid' => $USER->id, 'component' => $component, 'target' => $target]
        );

        return $count_login;
    }

    function set_message()
    {

    }
}