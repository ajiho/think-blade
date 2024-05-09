<?php


if (!function_exists('blade')) {
    function blade($view, $data = [])
    {
        return app('blade')->make($view, $data);
    }
}

