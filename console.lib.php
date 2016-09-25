<?php

/**
 * Class Console
 *
 * @category Logging
 * @author   Mark Lubkowitz <www.mark-lubkowitz.de>
 * @version  0.9.0
 * @link     http://www.mark-lubkowitz.de
 */
class Console
{
    private $logfile = '';
    private $is_cli = false;
    private $log_level = ConsoleLogLevels::Debug;
    private $starttime = false;
    private $timers = array();
    private $log_pipe = ConsoleLogPipes::File;
    private $force_logging = false;


    public function __construct($log = false)
    {
        /* set the logfile name */
        $this->logfile = 'console.log.txt';

        /* determines, if the script runs from command line */
        $this->is_cli = (php_sapi_name() === 'cli');
        
        $this->force_logging = ($log === true);
    }
    
    
    public function pipe($log_pipe) {
        $this->log_pipe = $log_pipe;
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
        if(!$this->force_logging)
            return false;
        
        $outline = '[' . $this->strftimeu('%Y-%m-%d %H:%M:%S.%f',microtime(true)) . '] ' . vsprintf($format, $values);
        
        switch($this->log_pipe) {
            /* pipe as HTML comment */
            case ConsoleLogPipes::HTML:
                echo '<!-- '.htmlspecialchars($outline).' //-->';
                break;
                
            /* pipe to JavaScript console */
            case ConsoleLogPipes::JSConsole:
                echo '<script type="text/javascript">console.log(\''.htmlspecialchars($outline).'\');</script>';
                break;
                
            /* pipe to file */
            default:
            case ConsoleLogPipes::File:
                if($fh = fopen($this->logfile,'a')) {
                    fputs($fh,$outline."\r\n");
                    fclose($fh);
                }
                break;
        }
    }
    
    
    private function level_threshold($log_level)
    {
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

    
    public function timer($id = false)
    {
        if(!$id) {
            if(!$this->starttime) {
                $this->starttime = microtime(true);
                return 0;
            } else {
                return microtime(true) - $this->starttime;
            }
        } else {
            if(!array_key_exists($id,$this->timers)) {
                $this->timers[$id] = microtime(true);
                return 0;
            } else {
                return microtime(true) - $this->timers[$id];
            }
        }
    }

    
    public function level($new_level)
    {
        $this->log_level = $new_level;
    }

    
    private function is_complex($arg)
    {
        /* checks, if the argument is an array or an object */
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

abstract class ConsoleLogLevels
{
    const Silly = 0;
    const Debug = 1;
    const Verbose = 2;
    const Info = 3;
    const Warning = 4;
    const Error = 5;
}

abstract class ConsoleLogPipes
{
    const File = 0;
    const HTML = 1;
    const JSConsole = 2;
}