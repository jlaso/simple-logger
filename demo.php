<?php

error_reporting(E_ALL & !E_WARNING);

require_once __DIR__.'/vendor/autoload.php';

use JLaso\SimpleLogger\PlainFileLogger as Logger;

$start = microtime(true);
Logger::info("This is only the start of the program");

Logger::debug(array(
    'title' => 'This is the title',
    'data' => 'more data',
));

$a = 1234/ $b;

set_error_handler('error_handler');

function error_handler($e)
{
    Logger::error($e);
}

Logger::info('Just finishing the execution of the program in '.intval((microtime(true)-$start)*1000).' msec !');