<?php

namespace block_minerva\quiz;

global $CFG;

require_once $CFG->dirroot . "/mod/quiz/lib.php";

defined('MOODLE_INTERNAL') || die();

class js
{
    public static function total_attempted()
    {
        $quiz_count_attempted_quizzes = quiz::count_attempted_quizzes();
        $count_quizzes = quiz::count_quizzes();
        $data = [
            "datasets" => [[
                "data" => [$quiz_count_attempted_quizzes, $count_quizzes - $quiz_count_attempted_quizzes],
                "backgroundColor" => ["#FF6384", "#36A2EB"],
                "hoverBackgroundColor" => ["#FF6384", "#36A2EB", "#FFCE56"]
            ]],
            "labels" => ["受験済み", "未受験"]
        ];
        $js = file_get_contents(__DIR__ . "/../../templates/pie.js.template");
        $js = str_replace("@@ID@@", "graph_total_attempted", $js);
        $js = str_replace("@@DATA@@", json_encode($data), $js);

        return $js;
    }

    public static function total_grades()
    {
        $all_grades = quiz::get_all_grades();
        $grades = [];
        foreach ($all_grades as $all_grade) {
            $quiz = quiz::get_quiz_by_instance($all_grade->quiz);
            $grades[] = [
                "x" => $quiz->name,
                "y" => $all_grade->grade,
                "r" => 10
            ];
        }

        $data = [
            "datasets" => [[
                "label" => "過去の小テスト点数",
                "data" => $grades,
                "backgroundColor" => "#FF6384",
                "hoverBackgroundColor" => "#FF6384"
            ]],

        ];

        $js = file_get_contents(__DIR__ . "/../../templates/bubble.js.template");
        $js = str_replace("@@ID@@", "graph_total_grades", $js);
        $js = str_replace("@@DATA@@", json_encode($data), $js);

        return $js;
    }
}