# console-lib
A simple library that mimics the Console-API of modern browsers in PHP.

## Installation

Just copy `console.lib.php` into your project directory.

## Usage

Console is implemented as a class. To make use of Console, you have to include the library and instantiate a new object from `Console`.

```php
require_once('console.lib.php');
$console = new Console();
$console->log('A simple log message.');
```

Please note that, although the `$console` object is properly instantiated, it won't log a single line. That's due to the design decision, that you have to explicitly declare that Console shall log. You have to enable the log mode by assigning `true`. For a later version of Console it will be possible to define an environment variable, that activates the debug mode from command line or by a GET parameter.

```php
require_once('console.lib.php');
/* debug mode on */
$console = new Console(true);
$console->log('A simple log message.');
```

### Log Level

Console differentiates six logging level. The levels are `Silly`, `Debug`, `Verbose`, `Info`, `Warning`, and `Error`. `Silly` is the lowest possible log level, `Error` is the highest. The higher the log level, the less messages will be logged. Setting the log level to `Error` means that Console just logs errors, but no warnings, no infos, no verboses, no debugs, and especially no silly messages.

The default log level is `Debug`. But you can change it to whatever level you like.

```php
$console->level(ConsoleLogLevels::Silly);
$console->level(ConsoleLogLevels::Debug);
$console->level(ConsoleLogLevels::Verbose);
$console->level(ConsoleLogLevels::Info);
$console->level(ConsoleLogLevels::Warning);
$console->level(ConsoleLogLevels::Error;
```

### Logging

If want just want log a simple message, use the method `log()`. In this cases Console will automatically set the log level of the message to `Info`. That means, you must set the log level to at least `Info` or lower, so the simple message will show up in the log. By default, the log level ist set to `Debug`.

```php
$console->log('A simple log message.');
```

This will create the following line in the log:

```
[2016-09-24 18:34:23.243500] 5 cups of coffee were consumed during the development.
```

As an alternative to `log()` you can choose one of six specialized methods: `silly()`, `debug()`, `verbose()`, `info()`, `warn()`, and `error()`. Every method sets a different type of log level for the message.

```php
$console->silly('A log message with the log level Silly.');
$console->debug('A log message with the log level Debug.');
$console->verbose('A log message with the log level Verbose.');
$console->info('A log message with the log level Info.');
$console->warn('A log message with the log level Warning.');
$console->error('A log message with the log level Error.');
```

The log level will show up in the log. The six method calls from above will create the following lines in the log:

```
[2016-09-24 18:34:23.243600] [silly] A log message with the log level Silly.
[2016-09-24 18:34:23.243700] [debug] A log message with the log level Debug.
[2016-09-24 18:34:23.243800] [verbose] A log message with the log level Verbose.
[2016-09-24 18:34:23.243900] [info] A log message with the log level Info.
[2016-09-24 18:34:23.244000] [warning] A log message with the log level Warning.
[2016-09-24 18:34:23.244100] [error] A log message with the log level Error.
```

### Formatting

The parameters of the logging methods are variable. By providing more than one parameters, Console interpretes the first one as a formatted string and the additional parameters as values for the formatted string.

```php
$console->log('Stopped: %s',time());
$console->silly('Stopped: %s',time());
$console->debug('Stopped: %s',time());
$console->verbose('Stopped: %s',time());
$console->info('Stopped: %s',time());
$console->warn('Stopped: %s',time());
$console->error('Stopped: %s',time());
```

### Dumping

The third variant of calling the log method is for dumping complex data structures like arrays or objects. Just provide one or more complex objects as parameters. The seven log methods of Console will convert them to the JSON format.

```php
$console->log($_SERVER,$_GET);
```

Please note that the log methods make their decision how to handle the parameters by the first given value. If the first parameter is an array or object, any additional parameters will interpreted as objects too and dumped to the log in JSON.

### Timing

Sometimes it's helpful to measures the time between to points. Console has a special method named `timer()`. The first time you call `timer()`, it will set the current time as a start time and returns `0`.

```php
echo $console->timer();
$console->log('Execution time: %s',$console->timer());
```

If you call `timer()` again, every time you call the method it will return the delta between the current time and the start time. So, if you call `timer()` at the beginning and at the end of the script, you can gauge and log the execution time.

To manage diffent start times, just assign an id to `timer()`.

```php
echo $console->timer();
echo $console->timer('logging');
$console->log('Execution time: %s',$console->timer('logging'));
$console->log('Execution time: %s',$console->timer());
```

### Piping

_This feature is currently in a testing phase!_

Per default, Console will store the log in a file named `console.log.txt`. But you can choose two additional output pipes. `HTML` echoes the log messages as HTML comments, `JSConsole` will convert the message to javascript snippets that will be echoed to the browser, too. So the browser will interprete the scripts and add the log message of your PHP script to the console of Javascript. Use `pipe()` to change the pipe.

```php
$console->pipe(ConsoleLogPipes::File);
$console->pipe(ConsoleLogPipes::HTML);
$console->pipe(ConsoleLogPipes::JSConsole);
```

Add the filename `console.log.txt` to `.gitignore` to prevent Git from uploading the log file to the repository.

## Example

The repository includes a test script named - who would have thought - `test.php`. It's incredible simple and demonstrates the usage of Console in a more considerable context.

```php
<?php

require('console.lib.php');
$console = new Console(true);
$console->timer();
$console->pipe(ConsoleLogPipes::JSConsole);
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
$console->log('Exection time: %s',$console->timer());
$console->log('Exection time: %s',$console->timer('first'));
```