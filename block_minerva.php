<?php
// This file is part of Moodle - http://moodle.org/
//
// Moodle is free software: you can redistribute it and/or modify
// it under the terms of the GNU General Public License as published by
// the Free Software Foundation, either version 3 of the License, or
// (at your option) any later version.
//
// Moodle is distributed in the hope that it will be useful,
// but WITHOUT ANY WARRANTY; without even the implied warranty of
// MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
// GNU General Public License for more details.
//
// You should have received a copy of the GNU General Public License
// along with Moodle.  If not, see <http://www.gnu.org/licenses/>.

/**
 * minerva block caps.
 *
 * @package    block_minerva
 * @copyright  Takayuki FUWA <yue@eldoom.me>
 * @license    http://www.gnu.org/copyleft/gpl.html GNU GPL v3 or later
 */

defined('MOODLE_INTERNAL') || die();

class block_minerva extends block_base
{
    function init()
    {
        $this->title = get_string('pluginname', 'block_minerva');
    }

    function get_content()
    {
        global $CFG;

        $this->content = new stdClass();
        $this->content->items = [];
        $this->content->icons = [];
        $this->content->footer = '';

        $html = html_writer::link(
            new moodle_url($CFG->wwwroot . '/blocks/minerva/index.php'),
            get_string('launch', 'block_minerva'),
            ['class' => 'btn', 'target' => '_blank']
        );
        return $this->content = (object)['text' => $html];
    }

    // my moodle can only have SITEID and it's redundant here, so take it away
    public function applicable_formats()
    {
        return ['all' => false,
            'site' => true,
            'site-index' => true,
            'course-view' => true,
            'course-view-social' => false,
            'mod' => true,
            'mod-quiz' => false];
    }

    public function instance_allow_multiple()
    {
        return true;
    }

    function has_config()
    {
        return true;
    }

    public function cron()
    {
        return true;
    }
}