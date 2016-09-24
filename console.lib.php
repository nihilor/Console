<?php

/*
* FIX: check for environmental variable whether we want to log or not
* by default we dont log
*/

abstract class ConsoleLogLevels
{
    const Silly = 0;
    const Debug = 1;
    const Verbose = 2;
    const Info = 3;
    const Warning = 4;
    const Error = 5;
}

class Console
{
    //private $logfile = '';
    public $logfile = '';
    //private $is_cli = false;
    public $is_cli = false;
    //private $log_level = ConsoleLogLevels::Silly;
    public $log_level = ConsoleLogLevels::Debug;
    private $starttime = false;
    private $endtime = false;


    public function __construct()
    {
//        $this->logfile = $_SERVER['PHP_SELF'].'.log.txt';
        $this->logfile = 'console.log.txt';

        /* determines, if the script runs from command line */
        $this->is_cli = (php_sapi_name() === 'cli');
    }


    private function format_params($params) {
        $num_params = count($params);

        /* nothing to do */
        if($num_params == 0) return false;

        /* just a simple value */
        if($num_params == 1 and !$this->is_complex($params[0]))
            $output = $params[0];

        /* an array or object */
        if($num_params == 1 and $this->is_complex($params[0]))
            $output = json_encode($params[0], JSON_PRETTY_PRINT);

        /* a formatted string with arguments */
        if($num_params > 1 and is_string($params[0]))
            $output = vsprintf($params[0],array_slice($params,1));
        
        /* a list of complex arguments */
        if($num_params > 1 and $this->is_complex($params[0])) {
            $indexed_params = array();
            foreach($params as $key => $param) {
                $indexed_params['arg'.$key] = $param;
            }
            $output = json_encode($indexed_params, JSON_PRETTY_PRINT);
        }

        return $output;
    }
    
    
    private function out($format, $values)
    {
        $outline = '[' . $this->strftimeu('%Y-%m-%d %H:%M:%S.%f',microtime(true)) . '] ' . vsprintf($format, $values);
        if($fh = fopen($this->logfile,'a')) {
            fputs($fh,$outline."\r\n");
            fclose($fh);
        }
    }
    
    
    private function level_threshold($log_level) {
        return $log_level >= $this->log_level;
    }
    
    public function silly()
    {
        if(!$this->level_threshold(ConsoleLogLevels::Silly))
            return false;
        
        $logline = $this->format_params(func_get_args());
        $this->out('[silly] %s', $logline);
    }
    
    public function debug()
    {
        if(!$this->level_threshold(ConsoleLogLevels::Debug))
            return false;
        
        $logline = $this->format_params(func_get_args());
        $this->out('[debug] %s', $logline);
    }

    public function verbose()
    {
        if(!$this->level_threshold(ConsoleLogLevels::Verbose))
            return false;
        
        $logline = $this->format_params(func_get_args());
        $this->out('[verbose] %s', $logline);
    }

    public function info()
    {
        if(!$this->level_threshold(ConsoleLogLevels::Info))
            return false;
        
        $logline = $this->format_params(func_get_args());
        $this->out('[info] %s', $logline);
    }

    public function log()
    {
        if(!$this->level_threshold(ConsoleLogLevels::Info))
            return false;
        
        $logline = $this->format_params(func_get_args());
        $this->out('%s', $logline);
    }

    public function warn()
    {
        if(!$this->level_threshold(ConsoleLogLevels::Warning))
            return false;
        
        $logline = $this->format_params(func_get_args());
        $this->out('[warning] %s', $logline);
    }

    public function error()
    {
        if(!$this->level_threshold(ConsoleLogLevels::Error))
            return false;
        
        $logline = $this->format_params(func_get_args());
        $this->out('[error] %s', $logline);
    }

    public function timer() {
        $this->starttime = microtime(true);
    }

    public function timerEnd() {
        $this->endtime = microtime(true);
        return $this->starttime ? $this->endtime - $this->starttime : false;
    }

    public function group() {}
    public function groupEnd() {}
    
    
    public function level($new_level) {
        $this->log_level = $new_level;
    }

    private function is_complex($arg) {
        /* checks, is the argument an array or an object */
        return is_array($arg) || is_object($arg);
    }
    
    function strftimeu($format, $microtime)
    {
        if (preg_match('/^[0-9]*\\.([0-9]+)$/', $microtime, $reg)) {
            $decimal = substr(str_pad($reg[1], 6, "0"), 0, 6);
        } else {
            $decimal = "000000";
        }
        $format = preg_replace('/(%f)/', $decimal, $format);
        return strftime($format, $microtime);
    }
}

$_CONSOLE = new Console();