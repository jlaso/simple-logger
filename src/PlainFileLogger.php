<?php

namespace JLaso\SimpleLogger;

use Symfony\Component\Yaml\Yaml;

class PlainFileLogger extends BaseLogger
{
    use SingletonTrait;
    
    /**
     * @param string $level
     * @param mixed $data
     */
    public static function log($level, $data)
    {
        $instance = static::getInstance();

        if($instance->isLevel($level)) {

            file_put_contents(
                $instance->logFile,
                $instance->encodeLine($level, $data),
                FILE_APPEND
            );
        }
    }

    public function encodeLine($level, $data)
    {
        $data = is_scalar($data) ? (string)$data : json_encode($data);
        $date = date("Y-m-d h:i:s");

        return sprintf("*%'*-10s*[@%s]*: %s\n", "[ {$level} ]", $date, $data);
    }

    /**
     * @param string $line
     * @return array
     */
    public function decodeLine($line)
    {
        if(preg_match("/\*\[\s(?<level>\w+)\s\]\*+\[@(?<date>\d{4}\-\d{2}-\d{2}\s\d{2}:\d{2}:\d{2})\]\*:\s(?<text>.*)$/i", $line, $matches)){
            return array(
                'level' => $matches['level'],
                'date' => $matches['date'],
                'text' => $matches['text'],
            );
        }
    }

}