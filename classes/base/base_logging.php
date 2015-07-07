<?php
namespace block_minerva;

class base_logging {
    private $cache;
    private $context;
    private $user;

    function __construct($context)
    {
        global $CFG;
        require_once __DIR__ . '/../../../../report/log/lib.php';

        $this->context = $context;
        $this->cache = new \stdClass();
    }

    public function access($course)
    {
        global $DB, $USER;
        $sql = $DB->get_records("logstore_standard_log",
            ["action" => "loggedin", "userid" => $USER->id]);


    }
}