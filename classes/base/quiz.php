<?php
namespace block_minerva\base;

require_once("../../../../mod/quiz/lib.php");

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

    public static function attempt($id)
    {
        global $DB;
        return $DB->get_record('quiz_attempts', ['id' => $id]);
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

    /**
     * 小テストの評点を取得する。
     *
     * @param $quiz
     * @param $userid
     * @return array
     */
    public static function grade($quiz, $userid)
    {
        $grade = quiz_get_user_grades($quiz, $userid);
        return array_shift($grade);
    }

    /**
     * 指定したユーザーの小テスト最高評点を取得する。
     *
     * @param $quiz
     * @param $userid
     * @return float
     */
    public static function best_grade($quiz, $userid)
    {
        return quiz_get_best_grade($quiz, $userid);
    }
}