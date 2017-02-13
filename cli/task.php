#!/usr/bin/php
<?php
define('TEMP_PATH', sys_get_temp_dir() . '/');
define('ADAPT_PATH', $argv[1]);
define('ADAPT_VERSION', $argv[2]);
define('ADAPT_STARTED', true);
require(ADAPT_PATH . 'adapt/adapt-' . ADAPT_VERSION . '/boot.php');

if ($argv[3] && preg_match("/^[0-9]+$/", $argv[3])){
    $task = new model_task($argv[3]);
    $task->execute();
}
