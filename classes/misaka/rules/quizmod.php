<?php

namespace block_minerva\misaka\rules;

use block_misaka\message;
use block_misaka\misakamessages;
use block_misaka\quiz;

define('COMPONENT', 'mod_quiz');

class quizmod extends misakamessages{
    public function get()
    {
        global $USER, $CFG;

        $message = new \stdClass();

        $c = $this->count_action(COMPONENT, 'attempt');

        if($c != 0){
            $message->text = "<p>あなたは最近、小テストに" . $c . "回挑戦しています。</p>";
            $c = quiz::count_finished($USER->id);
            if($c == 0){
                $message->text .= "<p>けど最近は小テストを受験していないようですね・・・</p>";

                $quiz = $this->get_finished();

                if($quiz){
                    echo $this->review($quiz);
                    $message->score = 0;
                }else{
                    $message->text .= "";
                    $message->score = 0;
                }
            }else{
                $message->text .= "そしてあなたは今まで" . $c . "回小テストを終わらせました！<br>";
                $message->text .= "その調子です！<br>";
                $message->text .= \html_writer::start_div('', ['style' => 'text-align:center;']);
                $message->text .= \html_writer::empty_tag('image', ['src' => new \moodle_url($CFG->wwwroot . '/blocks/misaka/images/quiz/stump01-002.gif')]);
                $message->text .= \html_writer::end_div();

                $quiz = $this->get_finished();

                if($quiz){
                    $message->text .= $this->review($quiz);

                    $message->score = 0;
                }else{
                    $message->text .= "";
                    $message->score = 0;
                }

                $message->score = 1;
            }
        }else{
            $courses = enrol_get_my_courses();
            $quiz_obj = new quiz();

            $show_quizzes = [];
            foreach($courses as $course){
                $quizzes = $quiz_obj->quizzes($course->id);
                foreach($quizzes as $quiz){
                    if($quiz->timeclose > time()){
                        $show_quizzes[] = $quiz->id;
                    }
                }
            }

            $show_quiz = array_rand($show_quizzes);

            if(is_null($show_quiz)){
                $message->text = "";
                $message->score = 0;

                return $message;
            }

            $quiz = quiz::quiz($show_quiz);

            $cm = get_coursemodule_from_instance('quiz', $quiz->id, $quiz->course);

            $url = new \moodle_url($CFG->wwwroot . '/mod/quiz/view.php', ['id' => $cm->id]);

            $message->text = $url;
            $message->score = 0;
        }

        return $message;
    }

    private function get_finished()
    {
        global $USER;

        $quiz_obj = new quiz();

        $quiz = $quiz_obj->recent_finished($USER->id);
        if(count($quiz) > 1){
            $key = array_rand($quiz);
            return $quiz[$key];
        }else{
            return $quiz;
        }
    }

    private function review($quiz)
    {
        $cm = get_coursemodule_from_instance('quiz', $quiz->qid, $quiz->course);

        $message = "";

        $message .= "過去に受験した小テストをふりかえってみましょう。<br>";
        $message .= \html_writer::start_div('', ['style' => 'text-align:center;']);
        $message .= \html_writer::link(new \moodle_url('mod/quiz/view.php', ['id' => $cm->id]), '小テストをふりかえり', ['class' => 'btn btn-success']);
        $message .= \html_writer::end_div();

        return $message;
    }
}