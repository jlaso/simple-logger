<?php

namespace JLaso\SimpleLogger;

use Symfony\Component\Yaml\Yaml;

abstract class BaseConfig
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

    /**
     * @return string
     * @throws \Exception
     */
    protected function getConfigFile()
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

}