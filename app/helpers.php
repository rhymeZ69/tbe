<?php

if (! function_exists('setting')) {
    function setting(string $key, $default = null)
    {
        return $GLOBALS['__tbe_settings'][$key] ?? $default;
    }
}