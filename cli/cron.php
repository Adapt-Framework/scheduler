#!/usr/bin/php
<?php
define('TEMP_PATH', sys_get_temp_dir() . '/');
define('ADAPT_PATH', $argv[1]);
define('ADAPT_VERSION', '2.0.0');
define('ADAPT_STARTED', true);
require(ADAPT_PATH . 'adapt/adapt-' . ADAPT_VERSION . '/boot.php');


while(true){
    $tasks = $adapt->data_source->sql
        ->select('*')
        ->from('task')
        ->where(
            new sql_cond('date_deleted', sql::IS, new sql_null())
        )
        ->execute(0)
        ->results();
    
    foreach($tasks as $data){
        $task = new model_task();
        $task->load_by_data($data);
        
        if ($task->can_run()){
            $task->run();
        }
    }
    
    sleep(60);
}

?>