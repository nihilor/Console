<?php

require('console.lib.php');
$_CONSOLE->timer();
$_CONSOLE->level(ConsoleLogLevels::Silly);

/* log simple */
$_CONSOLE->error('test');

/* log array */
$_CONSOLE->warn($_SERVER);

/* log object */
$_CONSOLE->info($_CONSOLE);

/* log formatted string */
$_CONSOLE->log('%s: %d','Bäume',5);

/* log list of complex' */
$_CONSOLE->silly($_ENV,$_SERVER,$_CONSOLE);

/* log runtime */
$_CONSOLE->log('Runtime: %s',$_CONSOLE->timerEnd());