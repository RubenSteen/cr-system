<?php

if (! function_exists('readable_date_time_string')) {
    function readable_date_time_string(\Carbon\Carbon $time)
    {
        return $time->format('j-M-Y H:i:s');
    }
}

if (! function_exists('readable_date_string')) {
    function readable_date_string(\Carbon\Carbon $time)
    {
        return $time->format('j-M-Y');
    }
}

if (! function_exists('readable_time_string')) {
    function readable_time_string(\Carbon\Carbon $time)
    {
        return $time->format('H:i:s');
    }
}
