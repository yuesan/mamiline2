<?php

namespace block_minerva\timeline\filter;


use block_minerva\timeline\html_writer;
use block_minerva\timeline\timeline;

defined('MOODLE_INTERNAL') || die();

/**
 * filter class for forum.
 *
 * Class forum
 * @package block_minerva\timeline\filter
 */
class assign
{
    public static function do($data)
    {
        global $CFG, $DB, $USER, $OUTPUT;

        require_once $CFG->dirroot . "/mod/assign/locallib.php";

        $user_link = timeline::get_userlink($data);

        $html = "";

        if ($data->action === "submitted" && $data->target === "assessable" && $data->userid === $USER->id) {
            $assign_submission = $DB->get_record("assign_submission", ["id" => $data->objectid]);
            $assign = $DB->get_record("assign", ["id" => $assign_submission->assignment]);
            $cm = get_coursemodule_from_instance("assign", $assign->id, $data->courseid);
            $assign_url = new \moodle_url($CFG->wwwroot . "/mod/assign/view.php", ["id" => $cm->id]);

            $title = "課題を提出";
            $content = $user_link . "が" . html_writer::link($assign_url, $assign->name) . "に課題を提出しました！";
            $footer = self::get_attachments_html($data);

            $onlinetext = self::get_onlinetext_attachment($data);
            $context = \context_module::instance($cm->id);
            $assignObj = new \assign($context, $cm, get_course($data->courseid));

            $plugin = $assignObj->get_submission_plugin_by_type("onlinetext");
            $url = $assignObj->download_rewrite_pluginfile_urls($onlinetext, \core_user::get_user($data->userid), $plugin);

            $footer .= html_writer::div($url, "well");

            $html .= html_writer::panel_primary($title, $content, $footer, "glyphicon-file");
        }

        if ($data->action === "graded" && $data->target === "submission" && $data->relateduserid === $USER->id) {
            $graded_user = \core_user::get_user($data->userid);
            $graded_userlink = html_writer::link(new \moodle_url($CFG->wwwroot . "/user/profile.php", ["id" => $graded_user->id]), fullname($graded_user));

            $assign_grade = $DB->get_record("assign_grades", ["id" => $data->objectid]);
            $assign = $DB->get_record("assign", ["id" => $assign_grade->assignment]);
            $assign_submission = $DB->get_record("assign_submission", ["assignment" => $assign_grade->assignment, "userid" => $USER->id, "status" => "submitted"]);

            $cm = get_coursemodule_from_instance("assign", $assign_grade->assignment, $data->courseid);

            $assign_feedback = $DB->get_record("assignfeedback_comments", ["grade" => $assign_grade->id]);
            $assign_url = new \moodle_url($CFG->wwwroot . "/mod/assign/view.php", ["id" => $cm->id]);

            $title = "課題が採点されました";
            $content = $graded_userlink . "が" . html_writer::link($assign_url, $cm->name) . "を採点しました。" . $assign_grade->grade . "点でした。";

            $footer = "<h4>" . fullname($graded_user) . "さんからのコメント</h4>" . $assign_feedback->commenttext . "<hr>";

            $fs = get_file_storage();
            $attachments = $fs->get_area_files($data->contextid, "assignsubmission_file", "submission_files", $assign_submission->id, "itemid, filepath, filename", false);

            $table = new \html_table();
            $table->attributes["class"] = "table table-bordered table-hover";
            $table_head = ["", "ファイル名", "-"];
            $table_data = [];

            foreach ($attachments as $attachment) {
                $mimetype = $attachment->get_mimetype();
                if ($mimetype === 'image/gif' || $mimetype === 'image/jpeg' || $mimetype === 'image/png') {
                    $attachment_url = \moodle_url::make_pluginfile_url($data->contextid, "assignsubmission_file", "submission_files", $assign_submission->id, "/", $attachment->get_filename());
                    $attachment_img_tag = html_writer::empty_tag("img", ["src" => $attachment_url, "class" => "img-responsive", "width" => "400"]);
                    $table_data[] = [
                        $OUTPUT->pix_icon(file_file_icon($attachment), get_mimetype_description($attachment), 'moodle', ['class' => 'icon']),
                        $attachment->get_filename(),
                        $attachment_img_tag
                    ];
                } else {
                    $attachment_url = \moodle_url::make_pluginfile_url($data->contextid, "assignsubmission_file", "submission_files", $assign_submission->id, "/", $attachment->get_filename(), true);
                    $table_data[] = [
                        $OUTPUT->pix_icon(file_file_icon($attachment), get_mimetype_description($attachment), 'moodle', ['class' => 'icon']),
                        $attachment->get_filename(),
                        html_writer::link($attachment_url, "ダウンロード", ["class" => "btn btn-primary"])
                    ];
                }
            }

            if (!empty($table_data)) {
                $footer .= html_writer::tag("h4", "あなたの提出物");
                $footer .= html_writer::table_files($table_head, $table_data);
            }

            $html .= html_writer::panel_success($title, $content, $footer, "glyphicon-file");
        }

        return $html;
    }

    /**
     * Get attachment files in assign
     *
     * @param $data
     * @return \stored_file[]
     */
    private static function get_upload_attachments($data)
    {
        $fs = get_file_storage();
        return $fs->get_area_files($data->contextid, "assignsubmission_file", "submission_files", $data->objectid, "itemid, filepath, filename", false);
    }

    /**
     * Get onlinetext
     *
     * @param $data
     * @return \string $onlinetext->onlinetext
     */
    private static function get_onlinetext_attachment($data)
    {
        global $DB, $CFG;

        $assign_submission = $DB->get_record("assign_submission", ["id" => $data->objectid]);
        $assign = $DB->get_record("assign", ["id" => $assign_submission->assignment]);

        $onlinetext = $DB->get_record("assignsubmission_onlinetext", ["assignment" => $assign->id, "submission" => $assign_submission->id]);
        $baseurl = "$CFG->wwwroot" . "/pluginfile.php/" . $data->contextid . "/assignsubmission_onlinetext/submissions_onlinetext/" . $assign_submission->id;
        $onlinetext->onlinetext = str_replace("@@PLUGINFILE@@", $baseurl, $onlinetext->onlinetext);

        return $onlinetext->onlinetext;
    }

    /**
     * Get html for attachment info
     *
     * @param $data
     * @return string
     */
    private static function get_attachments_html($data)
    {
        global $OUTPUT;

        $table = new \html_table();
        $table->attributes["class"] = "table table-bordered table-hover";
        $table_head = ["", "ファイル名", "-"];
        $table_data = [];

        $html = "";

        $upload_attachments = self::get_upload_attachments($data);
        foreach ($upload_attachments as $attachment) {
            $mimetype = $attachment->get_mimetype();
            if ($mimetype === 'image/gif' || $mimetype === 'image/jpeg' || $mimetype === 'image/png') {
                $attachment_url = \moodle_url::make_pluginfile_url($data->contextid, "assignsubmission_file", "submission_files", $data->objectid, "/", $attachment->get_filename());
                $attachment_img_tag = html_writer::empty_tag("img", ["src" => $attachment_url, "class" => "img-responsive", "width" => "400"]);
                $table_data[] = [
                    $OUTPUT->pix_icon(file_file_icon($attachment), get_mimetype_description($attachment), 'moodle', ['class' => 'icon']),
                    $attachment->get_filename(),
                    $attachment_img_tag
                ];
            } else {
                $attachment_url = \moodle_url::make_pluginfile_url($data->contextid, "assignsubmission_file", "submission_files", $data->objectid, "/", $attachment->get_filename(), true);
                $table_data[] = [
                    $OUTPUT->pix_icon(file_file_icon($attachment), get_mimetype_description($attachment), 'moodle', ['class' => 'icon']),
                    $attachment->get_filename(),
                    html_writer::link($attachment_url, "ダウンロード", ["class" => "btn btn-primary"])
                ];
            }
        }
        if (!empty($table_data)) {
            $html .= html_writer::table_files($table_head, $table_data);
        }

        return $html;
    }
}