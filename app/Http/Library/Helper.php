<?php

namespace App\Http\Library;

class Helper
{
    static $responseCode = array(
        "OK" => 200,
        "BAD_REQUEST" => 400,
        "UNAUTHORIZED" => 401,
        "FORBIDDEN" => 403,
        "NOT_FOUND" => 404,
        "METHOD_NOT_ALLOWED" => 405
    );

    static public function getPath($env)
    {
        return env(\strtoupper("PROXY_" . $env));
    }

    static public function getIp()
    {
        if (isset($_SERVER['HTTP_CF_CONNECTING_IP']))
            $ip = strip_tags(addslashes($_SERVER['HTTP_CF_CONNECTING_IP']));
        if (\getenv("HTTP_CLIENT_IP") && \strcasecmp(\getenv("HTTP_CLIENT_IP"), "unknown"))
            $ip = getenv("HTTP_CLIENT_IP");
        else if (\getenv("HTTP_X_FORWARDED_FOR") && \strcasecmp(\getenv("HTTP_X_FORWARDED_FOR"), "unknown"))
            $ip = \getenv("HTTP_X_FORWARDED_FOR");
        else if (\getenv("REMOTE_ADDR") && \strcasecmp(\getenv("REMOTE_ADDR"), "unknown"))
            $ip = \getenv("REMOTE_ADDR");
        else if (isset($_SERVER['REMOTE_ADDR']) && $_SERVER['REMOTE_ADDR'] && \strcasecmp($_SERVER['REMOTE_ADDR'], "unknown"))
            $ip = $_SERVER['REMOTE_ADDR'];
        else
            $ip = "unknown";

        return $ip;
    }

    static public function getErrorInvalidToken()
    {
        return array(
            "message" => "Invalid token.",
            "status" => "error"
        );
    }

    static public function getErrorUnauthorized()
    {
        return array(
            "message" => "Unauthorized.",
            "status" => "error"
        );
    }

    static public function gerErrorCustomMessage($message)
    {
        return array(
            "message" => $message,
            "status" => "error"
        );
    }

    static public function getErrorBadRequest()
    {
        return array(
            "message" => "Bad request.",
            "status" => "error"
        );
    }
}
