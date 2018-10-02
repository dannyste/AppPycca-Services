<?php

if (!function_exists('log_general')) {

    function log_general($code, $message, $tipo = 'error') {
        $trace = debug_backtrace();
        $trace = $trace[1];
        $arr_err = array(
            'PHP' => array(
                'IP' => isset($_SERVER['REMOTE_ADDR']) ? $_SERVER['REMOTE_ADDR'] : '0.0.0.0',
                'co_error' => $code,
                'smg_error' => $message,
                'file' => isset($trace['file']) ? $trace['file'] : '',
                'function' => isset($trace['function']) ? $trace['function'] : '',
                'line' => isset($trace['line']) ? $trace['line'] : ''
            )
        );
        $salida = $tipo == 'error' ? print_r($arr_err, true) : $message;
        log_message($tipo, $salida);
    }

}
if (!function_exists('ArrayStructXML')) {

    function ArrayStructXML($dom, $body, $a) {
        if (!empty($a) && is_array($a)) {
            foreach ($a as $b => $c) {
                $h = $dom->createElement($b);
                if (!empty($c) && is_array($c)) {
                    $body->appendChild($h);
                    ArrayStructXML($dom, $h, $c);
                } else {
                    $body->appendChild($dom->createElement($b, $c));
                }
            }
        }
    }

}
if (!function_exists('clearCachePhp')) {

    function clearCachePhp() {
        clearstatcache();
        header("Expires: Tue, 01 Jan 2000 00:00:00 GMT");
        header("Last-Modified: " . gmdate("D, d M Y H:i:s") . " GMT");
        header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
        header("Cache-Control: post-check=0, pre-check=0", false);
        header("Pragma: no-cache");
    }

}
if (!function_exists('getRealIP')) {

    function getRealIP() {
        if (isset($_SERVER["HTTP_CLIENT_IP"])) {
            return $_SERVER["HTTP_CLIENT_IP"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_X_FORWARDED"])) {
            return $_SERVER["HTTP_X_FORWARDED"];
        } elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])) {
            return $_SERVER["HTTP_FORWARDED_FOR"];
        } elseif (isset($_SERVER["HTTP_FORWARDED"])) {
            return $_SERVER["HTTP_FORWARDED"];
        } else {
            return $_SERVER["REMOTE_ADDR"];
        }
    }

}

?>

