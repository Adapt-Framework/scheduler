#!/usr/bin/php
<?php
define('ADAPT_PATH', $argv[1]);

while (true) {
    $path = ADAPT_PATH . "scheduler/scheduler-1.0.0/cli/process.php";
    $command = 'bash -c "exec nohup setsid ' . $path . ' ' . ADAPT_PATH  . ' > /dev/null 2>&1 &"';
    //$command = $path . ' ' . ADAPT_PATH;
    //print $command . "\n";
    exec($command);
    sleep(60);
}

?>