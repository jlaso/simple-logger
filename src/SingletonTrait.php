<?php

namespace JLaso\SimpleLogger;

trait SingletonTrait
{
    protected static $instance;

    final public static function getInstance()
    {
        return static::$instance ? : static::$instance = new static();
    }
    
}