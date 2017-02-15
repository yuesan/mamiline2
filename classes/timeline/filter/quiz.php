<?php

namespace block_minerva\timeline\filter;

use block_minerva\quiz\attempts;
use block_minerva\timeline\html_writer;
use block_minerva\timeline\timeline;

defined('MOODLE_INTERNAL') || die();

/**
 * filter class for forum.
 *
 * Class forum
 * @package block_minerva\timeline\filter
 */
class quiz
{
    public static function do($data)
    {
        global $CFG, $USER;

        $html = "";

        if ($data->target === "attempt" && $data->eventname === '\mod_quiz\event\attempt_submitted') {
            $attempt = attempts::get_attempt($data->objectid);
            //When quiz or quiz attempt was deleted, skip
            if (!$attempt) {
                return "";
            }
            $quiz = \block_minerva\quiz\quiz::get_quiz_by_instance($attempt->quiz);
            $cm = get_coursemodule_from_instance("quiz", $quiz->id);
            $grade_obj = \block_minerva\quiz\quiz::get_grade($quiz);

            $grade_str = is_null($grade_obj) ? null : quiz_format_grade($quiz, $grade_obj->rawgrade);
            $g = grade_get_grades($quiz->course, "mod", "quiz", $quiz->id, $USER->id);
            $max_grade = quiz_format_grade($quiz, $quiz->grade);
            $quiz_url = new \moodle_url($CFG->wwwroot . "/mod/quiz/view.php", ["id" => $cm->id]);

            if ((int)$g->items[0]->gradepass != 0 && ((int)$g->items[0]->gradepass <= $grade_obj->rawgrade)) {
                $image = html_writer::img_stamp("taihenyokudekimasita.png", "たいへんよくできました");

                $title = $quiz->name . "を合格しました！";
                $content = html_writer::link($quiz_url, $quiz->name) . "に合格しました！";
                $footer = $image;

                $html .= html_writer::panel_success($title, $content, $footer, "glyphicon-ok");

            } else {
                $image = html_writer::img_stamp("mousukosiganbarimashou.png", "もうすこしがんばりましょう");

                $title = $quiz->name . "は不合格でした・・・";

                if ($quiz->timeclose != 0 && $quiz->timeclose < time()) {
                    $content = "ふりかえりしてみませんか？<hr>" . html_writer::link($quiz_url, "ふりかえりをする", ["class" => "btn btn-primary"]);
                } else {
                    $content = "もう一度、挑戦してみませんか？<hr>" . html_writer::link($quiz_url, "もう一度挑戦する", ["class" => "btn btn-primary"]);
                }

                $footer = $image;

                $html .= html_writer::panel_primary($title, $content, $footer, "glyphicon-ban-circle");
            }

            $attempts = attempts::get_attempts($quiz->id);
            if (count($attempts) != 1) {
                $graph = html_writer::graph($data->id . "_quiz_total_graph", 500, 250);
                $graph = html_writer::div($graph);
            } else {
                $graph = "";
            }

            $html .= html_writer::panel_primary(
                "小テストを受験",
                "小テスト「" . html_writer::link($quiz_url, $quiz->name) . "」を受験しました。点数は" . $grade_str . "(" . $max_grade . "点中)でした。" . $graph,
                "",
                "glyphicon-pencil");
        }

        return $html;
    }

    public static function script($data)
    {
        if ($data->target === "attempt" && $data->eventname === '\mod_quiz\event\attempt_submitted') {
            $attempt = attempts::get_attempt($data->objectid);
            //When quiz or quiz attempt was deleted, skip
            if (!$attempt) {
                return "";
            }
            $quiz = \block_minerva\quiz\quiz::get_quiz_by_instance($attempt->quiz);
            return self::js_total_attempted($quiz->id, $data->id);
        }
    }

    private static function js_total_attempted($quiz_id, $unique_id)
    {
        $attempts = attempts::get_attempts($quiz_id);
        if (count($attempts) == 1) {
            return "";
        }

        $data = [];
        $count = [];
        $c = 1;
        foreach ($attempts as $attempt) {
            $data[] = (int)$attempt->sumgrades;
            $count[] = (string)$c . "回";
            $c++;
        }

        $data = [
            "datasets" => [[
                "label"                 => "点数の遷移(複数回受験した時の点数遷移)",
                "data"                  => $data,
                "fill"                  => false,
                "lineTension"           => 0.1,
                "backgroundColor"       => "rgba(75,192,192,0.4)",
                "borderColor"           => "rgba(75,192,192,1)",
                "borderCapStyle"        => "butt",
                "borderDash"            => [],
                "borderDashOffset"      => 0.0,
                "borderJoinStyle"       => "miter",
                "pointBorderColor"      => "rgba(75,192,192,1)",
                "pointBackgroundColor"  => "#fff",
                "pointBorderWidth"      => 1,
                "pointHoverRadius"      => "rgba(75,192,192,1)",
                "pointHoverBorderColor" => "rgba(220,220,220,1)",
                "pointHoverBorderWidth" => 2,
                "pointRadius"           => 1,
                "pointHitRadius"        => 10,
                "spanGaps"              => false,
            ]],
            "labels"   => $count
        ];
        $js = file_get_contents(__DIR__ . "/../../../templates/line.js.template");
        $js = str_replace("@@ID@@", $unique_id . "_quiz_total_graph", $js);
        $js = str_replace("@@DATA@@", json_encode($data), $js);

        return $js;
    }
}