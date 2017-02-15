<?php

namespace block_minerva\timeline\filter;

use block_minerva\timeline\html_writer;

defined('MOODLE_INTERNAL') || die();

/**
 * filter class for forum.
 *
 * Class forum
 * @package block_minerva\timeline\filter
 */
class forum
{
    public static function do($data)
    {
        global $CFG, $DB, $USER, $USER;

        $html = "";

        $user = \core_user::get_user($data->userid);
        if ($user->id == $USER->id) {
            $user_link = html_writer::link(new \moodle_url($CFG->wwwroot . "/user/profile.php", ["id" => $user->id]), "あなた");
        } else {
            $user_link = html_writer::link(new \moodle_url($CFG->wwwroot . "/user/profile.php", ["id" => $user->id]), fullname($user));
        }

        // response post
        if ($data->userid !== $data->relateduserid && $data->target === "post" && $data->userid === $USER->id) {
            $post = $DB->get_record("forum_posts", ["id" => $data->objectid]);
            $discussion = $DB->get_record("forum_discussions", ["id" => $post->discussion]);
            $forum = $DB->get_record("forum", ["id" => $discussion->forum]);
            $post_url = new \moodle_url($CFG->wwwroot . "/mod/forum/discuss.php", ["d" => $discussion->id]);

            if ($post->parent != 0) {
                if ($user->id == $USER->id) {
                    $title = "フォーラムに返信しました";
                } else {
                    $title = "フォーラムに返信が来ました";
                }
                $content = $user_link . "がフォーラム(" . html_writer::link($post_url, $forum->name) . ")に返信しました。";
                $footer = $post->message;
            } else {
                $title = "フォーラムに投稿がありました";
                $content = $user_link . "がフォーラム(" . html_writer::link($post_url, $forum->name) . ")に投稿しました。";
                $footer = $post->message;
            }
            if ($post->attachment == 1) {
                $footer .= self::get_attachments_html($data);
            }
            $footer .= html_writer::link($post_url, "この投稿を見る", ["class" => "btn btn-success"]);
            $html = html_writer::panel_success($title, $content, $footer, "glyphicon-comment");

            // new post
        } elseif ($data->target === "discussion" && $data->action === "created" && $data->userid === $USER->id) {
            $discussion = $DB->get_record("forum_discussions", ["id" => $data->objectid]);
            $forum = $DB->get_record("forum", ["id" => $discussion->forum]);
            $post = $DB->get_record("forum_posts", ["id" => $discussion->firstpost]);
            $post_url = new \moodle_url($CFG->wwwroot . "/mod/forum/discuss.php", ["d" => $post->id]);

            if ($data->userid == $USER->id) {
                $title = "あなたがフォーラムに投稿しました";
                $content = $user_link . "がフォーラム(" . html_writer::link($post_url, $forum->name) . ")に返信しました。";
                $footer = $post->message;
            } else {
                $title = "コースメンバーがフォーラムに投稿しました";
                $content = $user_link . "がフォーラム(" . html_writer::link($post_url, $forum->name) . ")に返信しました。";
                $footer = $post->message;
            }
            $footer .= html_writer::link($post_url, "この投稿を見る", ["class" => "btn btn-success"]);
            $html = html_writer::panel_success($title, $content, $footer, "glyphicon-comment");

        }

        return $html;
    }


    /**
     * Get attachment files in discussion/post
     *
     * @param $data
     * @return \stored_file[]
     */
    private static function get_attachments($data)
    {
        $fs = get_file_storage();
        return $fs->get_area_files($data->contextid, "mod_forum", "attachment", $data->objectid, "itemid, filepath, filename", false);
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
        $table_head = ["", "ファイル名", "-", "ファイル登録日", "ファイル更新日"];
        $table_data = [];

        $html = "";

        $attachments = self::get_attachments($data);
        foreach ($attachments as $attachment) {
            $mimetype = $attachment->get_mimetype();
            if ($mimetype === 'image/gif' || $mimetype === 'image/jpeg' || $mimetype === 'image/png') {
                $attachment_url = \moodle_url::make_pluginfile_url($data->contextid, "mod_forum", "attachment", $data->objectid, "/", $attachment->get_filename());
                $attachment_imagetag = html_writer::empty_tag("img", ["src" => $attachment_url, "class" => "img-responsive"]);
                $html .= $attachment_imagetag . "<hr>";
            } else {
                $attachment_url = \moodle_url::make_pluginfile_url($data->contextid, "mod_forum", "attachment", $data->objectid, "/", $attachment->get_filename(), true);
                $table_data[] = [
                    $OUTPUT->pix_icon(file_file_icon($attachment), get_mimetype_description($attachment), 'moodle', ['class' => 'icon']),
                    $attachment->get_filename(),
                    html_writer::link($attachment_url, "ダウンロード", ["class" => "btn btn-primary"]),
                    userdate($attachment->get_timecreated()),
                    userdate($attachment->get_timemodified())];
            }
        }
        if (!empty($table_data)) {
            $html .= html_writer::table_files($table_head, $table_data);
        }

        return $html;
    }
}