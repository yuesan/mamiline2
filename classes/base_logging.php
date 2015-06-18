<?php
namespace block_minerva;

class base_logging {
    private $cache;
    private $context;
    private $user;

    function __construct($context)
    {
        global $USER, $CFG;
        require_once __DIR__ . '/../../../report/log/lib.php';

        $this->context = $context;
        $this->user = $USER;
        $this->cache = new \stdClass();
    }

    public function access($course){
        global $USER;
        return \report_log_can_access_user_report($USER, $course);
    }
}