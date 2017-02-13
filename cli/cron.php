#!/usr/bin/php
<?php
define('ADAPT_PATH', $argv[1]);
define('ADAPT_VERSION', $argv[2]);
define('SCHEDULER_VERSION', $argv[3]);
sleep(300);
while (true) {
    $path = ADAPT_PATH . "scheduler/scheduler-" . SCHEDULER_VERSION . "/cli/process.php";
    $command = 'bash -c "exec nohup setsid ' . $path . ' ' . ADAPT_PATH  . ' ' . ADAPT_VERSION . ' > /dev/null 2>&1 &"';
    //$command = $path . ' ' . ADAPT_PATH;
    //print $command . "\n";
    exec($command);
    sleep(60);
}

?>