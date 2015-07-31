<?php

namespace block_minerva\misaka;

use block_minerva\misaka\rules\greeting;
use block_minerva\misaka\rules\quizmod;
use block_minerva\misaka\rules\weather;

defined('MOODLE_INTERNAL') || die();

class misakamessages
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
}