#!/usr/bin/php
<?php
define('TEMP_PATH', sys_get_temp_dir() . '/');
define('ADAPT_PATH', $argv[1]);
define('ADAPT_VERSION', '2.0.0');
define('ADAPT_STARTED', true);
require(ADAPT_PATH . 'adapt/adapt-' . ADAPT_VERSION . '/boot.php');

if ($argv[2] && preg_match("/^[0-9]+$/", $argv[2])){
    $task = new model_task($argv[2]);
    
    $task->execute();
}

?>