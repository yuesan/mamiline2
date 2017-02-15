<?php

namespace block_minerva\timeline\dao;

defined('MOODLE_INTERNAL') || die();

define("MAX_LOGS", 1000);

class standard_log
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

        $sql = "SELECT * FROM {logstore_standard_log} WHERE userid = :userid OR relateduserid = :relateduserid ORDER BY timecreated DESC";

        return $DB->get_records_sql($sql, ["userid" => $USER->id, "relateduserid" => $USER->id], $from_num, $max_num);
    }

}