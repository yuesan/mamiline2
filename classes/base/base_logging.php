<?php
namespace block_minerva;

define("TIME_WEEK", 604800);

class base_logging {
    private $cache;
    private $context;

    function __construct($context)
    {
        global $CFG;
        require_once __DIR__ . '/../../../../report/log/lib.php';

        $this->context = $context;
        $this->cache = new \stdClass();
    }

    public function access()
    {
        global $DB, $USER;
        $sql = $DB->get_records("logstore_standard_log",
            ["action" => "loggedin", "userid" => $USER->id]);
    }
}