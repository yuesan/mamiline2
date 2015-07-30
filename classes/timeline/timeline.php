<?php
namespace block_minerva\timeline;

use block_minerva\timeline\dao\log;

class timeline
{
    private $logObject;

    function __construct($context){
        $this->logObject = new log($context);
    }

    public function myself()
    {
        return $this->logObject->myself();
    }
}