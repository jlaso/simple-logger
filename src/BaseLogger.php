<?php

namespace JLaso\SimpleLogger;


abstract class BaseLogger extends BaseConfig implements LoggerInterface
{
    public static function debug($data)
    {
        static::log('debug', $data);
    }

    public static function error($data)
    {
        static::log('error', $data);
    }

    public static function info($data)
    {
        static::log('info', $data);
    }
}