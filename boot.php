<?php

namespace extensions\scheduler;

/* Prevent Direct Access */
defined('ADAPT_STARTED') or die;

$adapt = $GLOBALS['adapt'];
$sql = $adapt->data_source->sql;

/* Only run for one in ten requests */
if (!isset($_SERVER['SHELL']) && rand(5, 5) == 5){
    print "<pre>Checking for scheduled tasks</pre>";
    /*
     * Find any tasks that are due to
     * run in the next hour that haven't
     * yet been spawned
     */
    
    $tasks = $sql->select('*')
        ->from('task', 't')
        //->join('schedule_log', 'l', 'schedule_id')
        ->where(
            new \frameworks\adapt\sql_and(
                new \frameworks\adapt\sql_condition(
                    new \frameworks\adapt\sql('t.date_deleted'),
                    'is',
                    new \frameworks\adapt\sql('null')
                ),
                /*new \frameworks\adapt\sql_condition(
                    new \frameworks\adapt\sql('l.date_deleted'),
                    'is',
                    new \frameworks\adapt\sql('null')
                ),*/
                new \frameworks\adapt\sql_condition(
                    new \frameworks\adapt\sql('t.status'),
                    '=',
                    'waiting'
                )
            )
        )
        ->execute(0) //No caching
        ->results();
    
    foreach($tasks as $task_item){
        print new html_pre(print_r($task_item, true));
        $task = new model_task();
        $task->load_by_data($task_item);
        
        if ($task->can_spawn()){
            //Spawn the task
            print "<pre>Can spawn</pre>";
            $task->spawn();
        }
        
    }   
}



?>