<?php
namespace block_minerva\base;

defined('MOODLE_INTERNAL') || die();

define("TIME_WEEK", 604800);

class logging {
    private $context;
    private $cache;

    function __construct($context)
    {
        global $CFG;
        require_once __DIR__ . '/../../../../report/log/lib.php';

        $this->context = $context;
        $this->cache = [];
    }

    /**
     * 現在時刻から1週間までの間のログイン記録を取得する。
     *
     * @return array $logins
     */
    private function login()
    {
        global $DB, $USER;

        if(array_key_exists("login", $this->cache)){
            return $this->cache["login"];
        }else{
            return $this->cache["login"] = $DB->get_records_sql(
                "SELECT id, userid, action, timecreated
               FROM {logstore_standard_log}
              WHERE userid = :userid AND action = :action AND timecreated > :timecreated",
                ["userid" => $USER->id, "action" => "loggedin", "timecreated" => time() - TIME_WEEK]
            );
        }
    }

    /**
     * 現在時刻から1週間までの間のアクセスログを参照し、ログインした曜日一覧を取得する。
     * @return array $date_count
     */
    public function access_status()
    {
        $logins = self::login();

        $date = [];
        foreach($logins as $login){
            $date[] = date("w", $login->timecreated);
        }

        $date = array_count_values($date);
        $date_count = [0, 1, 2, 3, 4, 5, 6];
        foreach($date_count as $key => $value){
            if(array_key_exists($key, $date)){
                $date_count[$key] = $date[$key];
            }else{
                $date_count[$key] = 0;
            }
        }
        return $date_count;
    }
}