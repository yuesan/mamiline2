<?php

namespace block_minerva\quiz;

defined('MOODLE_INTERNAL') || die();

global $CFG;
require_once $CFG->dirroot . "/mod/quiz/lib.php";

class quiz
{
    /**
     * Return quiz instance by instanceid
     *
     * @param int $instance
     * @return array
     */
    public static function get_quiz_by_instance($instance)
    {
        global $DB;

        return $DB->get_record("quiz", ["id" => $instance]);
    }

    /**
     * Return quizzes in course
     *
     * @param int $courseid
     * @return array
     */
    public static function get_quizzes_in_course($courseid)
    {
        return get_coursemodules_in_course("quiz", $courseid);
    }

    /**
     * Retugn quizzes with userid
     *
     * @return array
     */
    public static function get_quizzes_by_userid()
    {
        global $USER;

        $courses = enrol_get_users_courses($USER->id);
        $cms = [];
        foreach ($courses as $course) {
            $cms = array_merge($cms, self::get_quizzes_in_course($course->id));
        }

        return $cms;
    }

    /**
     * Return grade
     *
     * @param $quiz
     * @return array
     */
    public static function get_grade($quiz)
    {
        global $USER;

        $grades = quiz_get_user_grades($quiz, $USER->id);
        $grade = array_shift($grades);

        return $grade;

        return is_null($grade) ? null : quiz_format_grade($quiz, $grade->rawgrade);
    }

    public static function get_all_grades()
    {
        global $USER, $DB;
        return $DB->get_records("quiz_grades", ["userid" => $USER->id]);
    }

    /**
     * 指定したユーザーの小テスト最高評点を取得する。
     *
     * @param \quiz $quiz
     * @return float
     */
    public static function get_best_grade($quiz)
    {
        global $USER;
        return quiz_get_best_grade($quiz, $USER->id);
    }

    public static function get_attempted_quizzes()
    {
        global $USER, $DB;
        $sql = "SELECT * FROM {quiz_attempts} a WHERE userid = :userid AND state = 'finished' GROUP BY quiz";
        $param = ["userid" => $USER->id];
        return $DB->get_records_sql($sql, $param);
    }

    public static function count_attempted_quizzes()
    {
        return count(self::get_attempted_quizzes());
    }

    public static function count_quizzes()
    {
        global $DB;
        return $DB->count_records("quiz");
    }


}