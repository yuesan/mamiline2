<?php
namespace block_minerva\base;

defined('MOODLE_INTERNAL') || die();

define("TIME_WEEK", 604800);

class logging {
    private $cache;
    private $context;

    function __construct($context)
    {
        global $CFG;
        require_once __DIR__ . '/../../../../report/log/lib.php';

        $this->context = $context;
        $this->cache = new \stdClass();
    }

    /**
     * 現在時刻から1週間までの間のアクセスログを参照し、ログインした曜日一覧を取得する。
     *
     * @return array $date
     */
    public function access()
    {
        global $DB, $USER;

        $standard_logs = $DB->get_records_sql(
            "SELECT id, userid, action, timecreated
               FROM {logstore_standard_log}
              WHERE userid = :userid AND action = :action AND timecreated > :timecreated",
            ["userid" => $USER->id, "action" => "loggedin", "timecreated" => time() - TIME_WEEK]
        );

        $date = [];
        foreach($standard_logs as $log){
            $date[] = date("w", $log->timecreated);
        }

        return $date;
    }
}