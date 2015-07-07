<?php
namespace block_minerva\base;

defined('MOODLE_INTERNAL') || die();

class quiz
{
    private $cache;
    private $context;
    private $user;

    function __construct($context)
    {
        global $USER, $CFG;

        $this->context = $context;
        $this->user = $USER;
        $this->cache = new \stdClass();
    }

    /**
     * 小テストを取得する。
     *
     * @param $quizid
     *
     * @return mixed
     */
    public function quiz($quizid)
    {
        global $DB;
        return $DB->get_record('quiz', ['id' => $quizid]);
    }

    /**
     * コース内の小テストをすべて取得する。
     *
     * @return array
     * @throws \coding_exception
     */
    public function quizzes()
    {
        return get_coursemodules_in_course('quiz', $this->course->id);
    }
}