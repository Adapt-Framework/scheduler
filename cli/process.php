#!/usr/bin/php
<?php

define('TEMP_PATH', sys_get_temp_dir() . '/');
define('ADAPT_PATH', $argv[1]);
define('ADAPT_VERSION', $argv[2]);
define('ADAPT_STARTED', true);
require(ADAPT_PATH . 'adapt/adapt-' . ADAPT_VERSION . '/boot.php');

//print "foo\n";
//while(true){
    $tasks = $adapt->data_source->sql
        ->select('*')
        ->from('task')
        ->where(
            new sql_cond('date_deleted', sql::IS, new sql_null())
        )
        ->execute(0)
        ->results();
    //print_r($tasks);
    foreach($tasks as $data){
        $task = new model_task();
        $task->load_by_data($data);
        
        if ($task->can_run()){
            $task->run();
        }
    }
    
//    $count = 0;
//    while($count <= 60){
//        print "{$count}\n";
//        $count++;
//        sleep(1);
//    }
    //sleep(60);
//}

?>