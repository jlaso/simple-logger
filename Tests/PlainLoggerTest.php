<?php

namespace JLaso\SimpleLogger\Tests;

use JLaso\SimpleLogger\PlainFileLogger;

class PlainLoggerTest extends AbstractTestCase
{
    public function testLogWrite()
    {
        $readConfig = PlainFileLogger::getInstance()->getConfig();

        $this->assertEquals(
            $this->testConfig['logger']['path'],
            $readConfig['logger']['path']
        );

        $data = 'This is a logged text';
        PlainFileLogger::log('error', $data);

        $loggedText = file_get_contents($this->loggerFile);

        $this->assertRegExp("/$data$/", $loggedText);

        unlink($this->loggerFile);

        // info level is not logged, so this text wont be in the logger file

        $data = 'This is a logged text';
        PlainFileLogger::log('info', $data);

        $this->assertFalse(file_exists($this->loggerFile));
    }
}
