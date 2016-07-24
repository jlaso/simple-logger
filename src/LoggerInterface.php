<?php

namespace JLaso\SimpleLogger;

interface LoggerInterface
{
    /**
     * @param string $level
     * @param mixed $data
     */
    public static function log($level, $data);

    /**
     * @param mixed $data
     */
    public static function debug($data);

    /**
     * @param mixed $data
     */
    public static function error($data);

    /**
     * @param mixed $data
     */
    public static function info($data);
    
}