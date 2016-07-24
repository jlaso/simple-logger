# simple-logger
A simple logger system

# Installation

You need only to require this package in your project ```composer require jlaso/simple-logger```

# Configuration (optional)

In order to let know the library where it can put it's database file and other things related with setup you can copy the distribution file config-stats-base.yml.dist in the root
of your project with the config-stats-base.yml

This file contains:

```
logger:
    path: "%project_dir%/cache/logger.log"
    levels: error,info,debug    

```


# Commands

In order to check log file you can use ```src/console log:filter [--with=word1,word2,...] [--levels=error,info] [--date-from="2016-01-01 00:00:00"]```

# Running the example

Go to terminal and start the demo example ```php demo.php```

```
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
```


## Implementing the filter command in your app
 
You can see an example of how to do that (https://github.com/jlaso/simple-stats-demo/tree/master/app)[https://github.com/jlaso/simple-stats-demo/tree/master/app]

```
<?php

namespace App\Command;

use JLaso\SimpleLogger\Command\FilterCommand;

class FilterLogCommand extends FilterCommand
{

}
```

add it to app/console

```
#!/usr/bin/env php
<?php

namespace JLaso\SimpleStats;

require_once __DIR__ . '/../vendor/autoload.php';

use App\Command\FilterLogCommand;   <======
use App\Command\PostDeployCommand;
use Symfony\Component\Console\Application;

$application = new Application();
$application->addCommands(
    array(
        new PostDeployCommand(),
        new FilterLogCommand(),    <======
    )
);
$application->run();
```

