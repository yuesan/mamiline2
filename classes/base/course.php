<?php
namespace block_minerva\base;

defined('MOODLE_INTERNAL') || die();

require_once __DIR__ . '/../../../../config.php';
require_once __DIR__ . '/../../../../course/lib.php';

class course
{

    /**
     * ユーザが所属しているコース一覧を取得する。
     *
     * @param int $userid
     * @return array
     * @throws \coding_exception
     */
    public static function courses($userid)
    {
        return enrol_get_all_users_courses($userid);
    }

    /**
     * コースを取得する。
     *
     * @param $courseid
     *
     * @return \stdClass
     */
    public static function course($courseid)
    {
        return get_course($courseid);
    }

    /**
     * コースのURLを取得する。
     *
     * @param $courseid
     *
     * @return \moodle_url
     */
    public static function course_url($courseid)
    {
        return \course_get_url($courseid);
    }

    public function roles($courseid)
    {

    }
}