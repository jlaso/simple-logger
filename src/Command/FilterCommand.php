<?php

namespace JLaso\SimpleLogger\Command;

use JLaso\SimpleLogger\PlainFileLogger as Logger;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FilterCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('log:filter')
            ->setDescription('Filter log entries')
            ->addOption('date-from',null, InputOption::VALUE_REQUIRED,'date from (format Y-m-d h:i:s)',null)
            ->addOption('levels', null, InputOption::VALUE_REQUIRED, 'levels', null)
            ->addOption('with', null, InputOption::VALUE_REQUIRED, 'words contained', null)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $start = microtime(true);

        $logger = new Logger();
        $logFile = $logger->getLogFile();
        if(!file_exists($logFile) || !is_readable($logFile)){
            $output->writeln("Log file {$logFile} not found or not readable!");
        }else {
            $words = $input->getOption('with');
            /** @var string[] $words */
            $words = $words ? explode(',', $words) : false;
            /** @var string[] $levels */
            $levels = $input->getOption('levels');
            $levels = $levels ? explode(',', $levels) : false;
            $date = $input->getOption('date-from');

            $fHandler = fopen($logFile, 'r');
            if(!$fHandler){
                $output->writeln("Some error happened reading file {$logFile} !");
            }else {
                while (!feof($fHandler)) {
                    $buffer = fgets($fHandler, 4096);
                    if ($data = $logger->decodeLine($buffer)) {
                        $show = (!$date || ($data['date'] >= $date))
                            && (!$words || $this->areAnyOfThisWords($words, $buffer))
                            && (!$levels || in_array($data['level'], $levels));
                        if ($show) {
                            $output->write($buffer);
                        }
                    }
                }
                fclose($fHandler);
            }
        }
       
        $output->writeln('Done in '.intval((microtime(true)-$start)*1000).' msec !');
    }

    /**
     * @param string[] $words
     * @param string $buffer
     * @return bool
     */
    protected function areAnyOfThisWords($words, $buffer)
    {
        foreach($words as $word) {
            if (preg_match('#\b' . preg_quote($word, '#') . '\b#i', $buffer)){
                return true;
            }
        }

        return false;
    }
}
