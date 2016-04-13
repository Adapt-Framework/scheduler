#!/usr/bin/php
<?php
define('TEMP_PATH', sys_get_temp_dir() . '/');
define('ADAPT_PATH', $argv[1]);
define('ADAPT_VERSION', '2.0.0');
define('ADAPT_STARTED', true);
require(ADAPT_PATH . 'adapt/adapt-' . ADAPT_VERSION . '/boot.php');


$last_time = array(
    'year' => 0,
    'month' => 0,
    'day' => 0,
    'hour' => 0,
    'min' => 0
);

while(true){
    $time = array(
        'year' => intval(date('Y')),
        'month' => intval(date('m')),
        'day' => intval(date('d')),
        'hour' => intval(date('H')),
        'min' => intval(date('i'))
    );
    
    $tasks = $adapt->data_source->sql
        ->select('*')
        ->from('task')
        ->where(
            new sql_cond('date_deleted', sql::IS, new sql_null())
        )
        ->execute(0)
        ->results();
    
    print_r($tasks);
    
    foreach($tasks as $data){
        $task = new model_task();
        $task->load_by_data($data);
        
        $task->are_minutes_valid();
    }
    
    //print_r($time);
    //
    //print $i . "\n";
    //$i++;
    sleep(60);
}

?>