<?php
namespace block_minerva\timeline\dao;

define("MAX_LOGS", 30);

class log
{
    private $context;
    private $cache;

    function __construct($context)
    {
        $this->context = $context;
        $this->cache = null;
    }

    /**
     * ログインユーザーのアクセス記録を取得する。
     * スクロールダウン時に$pageを1,2,3としていく。
     *
     * @param int $page
     * @return array
     */
    public function myself($page = 0)
    {
        global $DB, $USER;
        $from_num = $page * MAX_LOGS;
        $max_num = $from_num + MAX_LOGS;
        return $DB->get_records("logstore_standard_log",
            ["userid" => $USER->id, "", $from_num, $max_num]);
    }

    public function in_course($courseid)
    {

    }
}