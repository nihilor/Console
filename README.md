# console-lib
A simple library that mimics the Console-API of modern browsers in PHP.

## Installation

Just copy `console.lib.php` into your project directory.

## Usage

Console is implemented as a class. To make use of Console, you have to include the library and instantiate a new object from `Console`.

```php
require_once('console.lib.php');
$console = new Console();
```

Please note that, although the `console` object is properly instatiated and provides all the functionality, it won't log a single line. That's due to the design decision, that you have to explicitly declare, that Console shall log. You have to enable the log mode by assigning `true`. For a later version of Console it will be possible to set an environment variable, that acitivates the debug mode.

```php
require_once('console.lib.php');
/* debug mode on */
$console = new Console(true);
```

### Log Level

Console differentiates six logging level. The levels are `Silly`, `Debug`, `Verbose`, `Info`, `Warning`, and `Error`. `Silly` is the lowest possible log level, `error` is the highest possible log level. The higher the log level, the less messages will be logged. Setting the log level to `Error` measns, that only errors will be logged, no warnings, no infos, and especially no silly messages.

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

If want to log a simple message without setting a special log level, just use the method `log`. Please note that, even if no log level will be shown, you must set the log level to at least `Info`. Otherwise the simple message won't show up in the log.

```php
$console->log('A simple log message.');
```

This will create the following line in the log:

```
[2016-09-24 18:34:23.243500] 5 cups of coffee were consumed during the development.
```

As an alternative to `log` you can choose one of the six following specialized methods.

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



### Timing


### Piping


