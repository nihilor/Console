<?php

require('console.lib.php');
$console = new Console(true);
$console->timer();
$console->pipe(ConsoleLogPipes::File);
$console->level(ConsoleLogLevels::Silly);

/* log simple */
$console->error('test');
$console->timer('first');

/* log array */
$console->warn($_SERVER);

/* log object */
$console->info($console);

/* log formatted string */
$console->log('%d %s',5,'cups of coffee were consumed during the development.');

/* log list of complex' */
$console->silly($_ENV,$_SERVER,$console);

/* log runtime */
$console->log('Exection time: %s',$console->timer('first'));
$console->log('Exection time: %s',$console->timer());