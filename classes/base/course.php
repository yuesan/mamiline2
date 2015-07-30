<?php
namespace block_minerva\base;

defined('MOODLE_INTERNAL') || die();

require_once __DIR__ . '/../../../../config.php';

class course
{
    private $cache;
    private $context;
    private $user;

    function __construct($context)
    {
        global $USER, $CFG;
        require_once __DIR__ . '/../../../../course/lib.php';

        $this->context = $context;
        $this->user = $USER;
        $this->cache = new \stdClass();
    }

    /**
     * ユーザが所属しているコース一覧を取得する。
     *
     * @return array
     * @throws \coding_exception
     */
    public function courses()
    {
        if(property_exists($this->cache, "courses")){
            return $this->cache->courses;
        }else{
            return $this->cache->courses = enrol_get_all_users_courses($this->user->id);
        }
    }

    /**
     * コースを取得する。
     *
     * @param $courseid
     *
     * @return \stdClass
     */
    public function course($courseid)
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
    public function course_url($courseid)
    {
        return \course_get_url($courseid);
    }

    public function roles($courseid)
    {

    }
}