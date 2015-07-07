<?php

namespace block_minerva\base;

defined('MOODLE_INTERNAL') || die();

class graph
{
    function __construct($context)
    {

    }

    public function pie($data, $id)
    {
        $data_array = "";
        foreach ($data as $key => $value) {
            $key = preg_replace('/(?:\n|\r|\r\n)/', '', $key);
            $value = $value ? $value : 0;
            $data_array .= "['$key', $value],";
        }
        $data_array = rtrim($data_array, ",");

        $js = file_get_contents(__DIR__ . '/../../templates/pie.js.template');
        $js = str_replace("@@ID@@", $id, $js);
        $js = str_replace("@@DATA@@", $data_array, $js);

        return $js;
    }

    public function line($data, $id)
    {
        $data_array = "['data1',";
        foreach ($data as $key => $value) {
            $value = $value ? $value : 0;
            $data_array .= "[$key, $value],";
        }
        $data_array = rtrim($data_array, ",");
        $data_array .= "]";

        $js = file_get_contents(__DIR__ . '/../../templates/line.js.template');
        $js = str_replace("@@ID@@", $id, $js);
        $js = str_replace("@@DATA@@", $data_array, $js);

        return $js;
    }
}