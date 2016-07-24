<?php

namespace JLaso\SimpleLogger;

use Symfony\Component\Yaml\Yaml;

class PlainFileLogger implements LoggerInterface
{
    /** @var  array */
    protected $config;
    /** @var  string */
    protected $logFile;
    /** @var string  */
    protected $projectDir;
    /** @var  array */
    protected $levels;

    public function __construct()
    {
            // vendor/jlaso/simple-logger/src
        $this->projectDir =
            preg_match("~vendor/jlaso/simple-logger/src$~i", __DIR__) ? 
            realpath(__DIR__ . '/../../../../') :
            realpath(__DIR__ . '/../')
        ;
        
        $this->readConfig();
    }

    /**
     * @return array
     */
    public function getConfig()
    {
        return $this->config;
    }

    /**
     * @return string
     */
    public function getProjectDir()
    {
        return $this->projectDir;
    }

    protected function readConfig()
    {
        $this->config = array_merge(
            array(
                'logger' => array(
                    'path' => '%project_dir%/app/cache/logger.log',
                    'level' => 'info,debug,error',
                ),
            ),
            Yaml::parse(file_get_contents($this->getConfigFile()))
        );
        $this->logFile = str_replace('%project_dir%', $this->projectDir, $this->config['logger']['path']);
        $this->levels = explode(',', trim(strtolower($this->config['logger']['level'])));
    }

    public function getConfigFile()
    {
        $configFile = $this->projectDir.'/config-simple-logger.yml';
        if(!file_exists($configFile)) {
            $configFile = dirname(__DIR__) . '/config-simple-logger.yml.dist';
            if (!file_exists($configFile)) {
                throw new \Exception("Configuration file {$configFile} not found");
            }
        }
        if(!is_readable($configFile)){
            throw new \Exception("File {$configFile} is not readable!");
        }

        return $configFile;
    }

    /**
     * @return string
     */
    public function getLogFile()
    {
        return $this->logFile;
    }
    
    /**
     * @return array
     */
    public function getLevels()
    {
        return $this->levels;
    }

    /**
     * @param string $level
     * @return bool
     */
    public function isLevel($level)
    {
        return in_array(trim(strtolower($level)), $this->levels);
    }

    public static function debug($data)
    {
        self::log('debug', $data);
    }

    public static function error($data)
    {
        self::log('error', $data);
    }

    public static function info($data)
    {
        self::log('info', $data);
    }

    /** @var  PlainFileLogger */
    protected static $instance = null;

    public static function getInstance()
    {
        if(!self::$instance){
            self::$instance = new PlainFileLogger();
        }

        return self::$instance;
    }

    /**
     * @param string $level
     * @param mixed $data
     */
    public static function log($level, $data)
    {
        $instance = self::getInstance();
        if($instance->isLevel($level)) {
            $logFile = $instance->logFile;
            $data = is_scalar($data) ? (string)$data : json_encode($data);
            $date = date("Y-m-d h:i:s");

            file_put_contents(
                $logFile,
                sprintf("*%'*-10s*[@%s]*: %s\n", "[ {$level} ]", $date, $data),
                FILE_APPEND
            );
        }
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