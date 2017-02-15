<?php

namespace block_minerva\quiz;

defined('MOODLE_INTERNAL') || die();

class attempts
{
    /**
     * Return attempt object by id
     *
     * @param int $quiz_attempt_id
     * @return mixed
     */
    public static function get_attempt($quiz_attempt_id)
    {
        global $DB;
        return $DB->get_record('quiz_attempts', ['id' => $quiz_attempt_id]);
    }

    /**
     * Return attempts by quiz_id
     *
     * @param int $quiz_id
     * @param string $status
     * @return \an
     */
    public static function get_attempts($quiz_id, $status = 'finished')
    {
        global $USER;
        return quiz_get_user_attempts($quiz_id, $USER->id, $status);
    }

    /**
     * Count attempts
     *
     * @param int $limit
     * @return array
     */
    public static function recently_attempt($limit = 10)
    {
        global $DB, $USER;
        $logs = $DB->get_records(
            "quiz_attempts",
            ["userid" => $USER->id, "state" => "finished", "preview" => 0],
            "timefinish DESC", "*", 0, $limit
        );
        $quiz = [];
        foreach ($logs as $log) {
            $quiz[$log->quiz] = quiz::get_quiz_by_instance($log->quiz);
            $quiz[$log->quiz]->timelastattempt = $log->timefinish;
        }

        return $quiz;
    }
}