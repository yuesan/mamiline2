<?php
namespace block_minerva\timeline;

defined('MOODLE_INTERNAL') || die();

class html_writer extends \block_minerva\base\html_writer
{
    public static function line()
    {
        return self::div("", "line text-muted");
    }

    public static function separator($content)
    {
        return self::div(
            self::tag("time", $content),
            "separator text-muted"
        );
    }

    public static function panel_simple($icon, $title, $content)
    {
        $html = self::start_tag("article", ["class" => "panel panel-danger panel-outline"]);
        $html .= self::start_div("panel-heading icon");
        $html .= self::tag("i", "", ["class" => $icon]);
        $html .= self::end_div();
        $html .= self::start_div("panel-body");
        $html .= self::tag("strong", $title) . $content;
        $html .= self::end_div();
        $html .= self::end_tag("article");

        return $html;
    }

    public static function panel_picture(\moodle_url $url)
    {
        $html = self::start_tag("article", ["class" => "panel panel-default panel-outline"]);
        $html .= self::start_div("panel-heading icon");
        $html .= self::tag("i", "", ["class" => "glyphicon glyphicon-picture"]);
        $html .= self::end_div();
        $html .= self::start_div("panel-body");
        $html .= self::empty_tag("img", ["class" => "img-responsive img-rounded", "src" => $url->out()]);
        $html .= self::end_div();
        $html .= self::end_tag("article");

        return $html;
    }

    public static function panel_box($title, $content, $footer)
    {
        $html = self::start_tag("article");
        $html .= self::start_div("panel-heading icon");
        $html .= self::tag("i", "", ["class" => "glyphicon glyphicon-plus"]);
        $html .= self::end_div();
        $html .= self::start_div("panel-heading icon");
        $html .= self::tag("h2", $title, ["class" => "panel-title"]);
        $html .= self::end_div();
        $html .= self::div($content, "panel-body");
        $html .= self::start_div("panel-footer icon");
        $html .= self::tag("small", $footer);
        $html .= self::end_div();
        $html .= self::end_tag("article");

        return $html;
    }

    public static function panel_success($title, $content, $footer, $icon)
    {
        $html = self::start_tag("article", ["class" => "panel panel-success"]);

        $html .= self::start_div("panel-heading icon");
        $html .= self::tag("i", "", ["class" => "glyphicon " . $icon]);
        $html .= self::end_div();

        $html .= self::start_div("panel-heading");
        $html .= self::tag("h2", $title, ["class" => "panel-title"]);
        $html .= self::end_div();

        $html .= self::div($content, "panel-body");

        $html .= self::start_div("panel-footer");
        if (!is_null($footer)) {
            $html .= self::tag("small", $footer);
        }
        $html .= self::end_div();

        $html .= self::end_tag("article");

        return $html;
    }

    public static function panel_primary($title, $content, $footer, $icon)
    {
        global $OUTPUT;

        $html = self::start_tag("article", ["class" => "panel panel-primary"]);

        $html .= self::start_div("panel-heading icon");
        $html .= self::tag("i", "", ["class" => "glyphicon " . $icon]);
        $html .= self::end_div();

        $html .= self::start_div("panel-heading");
        $html .= self::tag("h2", $title, ["class" => "panel-title"]);
        $html .= self::end_div();

        $html .= self::div($content, "panel-body");

        $html .= self::start_div("panel-footer");
        if (!is_null($footer)) {
            $html .= self::tag("small", $footer);
        }
        $html .= self::end_div();

        $html .= self::end_tag("article");

        return $html;
    }

    public static function panel_footer($icon, $content)
    {
        $html = self::start_tag("article", ["class" => "panel panel-info panel-outline"]);
        $html .= self::start_div("glyphicon glyphicon-info-sign");
        $html .= self::tag("i", "", ["class" => $icon]);
        $html .= self::end_div();
        $html .= self::div("panel-body", $content);
        $html .= self::end_tag("article");

        return $html;
    }

    public static function time($date)
    {
        $date = userdate($date, "%Y年%m月%d日");

        $html = \html_writer::start_div("separator text-mute", ["#" => "time_" . $date]);
        $html .= \html_writer::tag("time", $date);
        $html .= \html_writer::end_div();

        return $html;
    }

    public static function table_files($table_head, $table_data)
    {
        $table = new \html_table();
        $table->attributes["class"] = "table table-bordered table-hover";
        $table->head = $table_head;
        $table->data = $table_data;

        return html_writer::table($table);
    }
}