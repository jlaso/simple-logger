<?php

namespace JLaso\SimpleLogger\Tests;

use Symfony\Component\Yaml\Yaml;

abstract class AbstractTestCase extends \PHPUnit_Framework_TestCase
{
    /** @var string */
    protected $tmpFile;
    /** @var string */
    protected $loggerFile;
    /** @var array */
    protected $testConfig;

    protected function setUp()
    {
        $this->tmpFile = dirname(__DIR__) . '/config-simple-logger.yml';
        $this->loggerFile = __DIR__ . '/logger.log';

        $this->testConfig = array(
            'logger' => array(
                'path' => $this->loggerFile,
                'level' => 'error',
                'date_format' => '',
            ),
        );

        file_put_contents($this->tmpFile, Yaml::dump($this->testConfig));
    }

    protected function tearDown()
    {
        $this->cleanTmp();
    }

    protected function cleanTmp()
    {
        if (file_exists($this->tmpFile)) {
            unlink($this->tmpFile);
        }
        if (file_exists($this->loggerFile)) {
            unlink($this->loggerFile);
        }
    }
}
