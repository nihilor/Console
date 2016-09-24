<?php

require('console.lib.php');
$console = new Console(true);
$console->pipe(ConsoleLogPipes::JSConsole);
$console->timer();
$console->level(ConsoleLogLevels::Silly);

/* log simple */
$console->error('test');
$console->timer('first');

/* log array */
$console->warn($_SERVER);

/* log object */
$console->info($console);

/* log formatted string */
$console->log('%d %s',5,'cups of coffee were harmed during the development.');

/* log list of complex' */
$console->silly($_ENV,$_SERVER,$console);

/* log runtime */
$console->log('Exection time: %s',$console->timer());
$console->log('Exection time: %s',$console->timer('first'));