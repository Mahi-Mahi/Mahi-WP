<?php

function logr(...$stuffs)
{
    $bt = debug_backtrace();
    $call = current($bt);
    $function = null;
    if (isset($call['function'])) {
        if (isset($call['file'])) {
            $function .= str_replace($_SERVER['DOCUMENT_ROOT'], '', $call['file']);
        }
        if (isset($call['line'])) {
            $function .= ' : '.$call['line'];
        }
    }
    error_log($function.(isset($_SERVER['REQUEST_URI']) ? (' - '.$_SERVER['REQUEST_URI']) : ''));

    if (!is_array($stuffs)) {
        $stuffs = [$stuffs];
    }

    foreach ($stuffs as $s) {
        if (is_object($s)) {
            $s = json_encode($s, JSON_PRETTY_PRINT);
        }
        error_log($s);
    }
}

if (!function_exists('xmpr')) {
    function xmpr($s)
    {
        if (is_dev() || is_admin() || defined('WP_CLI')) {
            if (is_array($s) or is_object($s)) {
                xmpr(print_r($s, true));
            } else {
                if (defined('WP_CLI')) {
                    echo $s."\n";
                } else {
                    echo "<xmp style='word-wrap:break-word;'>".$s.'</xmp>';
                }
                flush();
                ob_flush();
            }
        } else { // prod
            logr($s);
        }
    }
}

if (!function_exists('is_dev')) {
    function is_dev()
    {
        if (defined('IS_LOCAL') || defined('IS_DEV')) {
            return true;
        }
        $suffix = ['.localhost', '.web-staging.com'];
        foreach ($suffix as $s) {
            if (preg_match('#'.$s.'#', $_SERVER['SERVER_NAME'])) {
                return $s;
            }
        }
    }
}

if (!function_exists('is_staging')) {
    function is_staging()
    {
        $suffix = ['.web-staging.com'];
        foreach ($suffix as $s) {
            if (preg_match('#'.$s.'#', $_SERVER['SERVER_NAME'])) {
                return $s;
            }
        }
    }
}

if (!function_exists('is_local')) {
    function is_local()
    {
        if (defined('IS_LOCAL')) {
            return true;
        }
        $suffix = ['.localhost'];
        if (preg_match('#^(192|127|10)\\.#', $_SERVER['SERVER_NAME'])) {
            return true;
        }
        foreach ($suffix as $s) {
            if (strstr($_SERVER['SERVER_NAME'], $s)) {
                return $s;
            }
        }
    }
}
