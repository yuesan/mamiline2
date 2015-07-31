<?php

namespace block_minerva\misaka;

class util
{
    public static function count_action($component, $target)
    {
        global $DB, $USER;

        $count_login = $DB->count_records_sql(
            "SELECT COUNT('id') FROM {logstore_standard_log}
              WHERE userid = :userid
                AND timecreated > (UNIX_TIMESTAMP(NOW()) - 259200)
                AND component = :component
                AND target = :target;
             "
            , ['userid' => $USER->id, 'component' => $component, 'target' => $target]
        );

        return $count_login;
    }

    public static function set_message()
    {

    }
}