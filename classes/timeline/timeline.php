<?php

namespace block_minerva\timeline;

defined('MOODLE_INTERNAL') || die();

use block_minerva\timeline\dao\log;

class timeline
{
    private $logObject;

    function __construct($context)
    {
        $this->logObject = new log($context);
    }

    public function myself()
    {
        return $this->logObject->myself();
    }

    public function get_message()
    {
    }
}