<?php

namespace JLaso\SimpleLogger\Tests;

use JLaso\SimpleLogger\PlainFileLogger;
use Symfony\Component\Yaml\Yaml;

class ConfigTest extends AbstractTestCase
{
    public function testConfigRead()
    {
        $config = PlainFileLogger::getInstance()->getConfig();

        $this->assertEquals($this->testConfig, $config);
    }
}
