<?php

/* Prevent Direct Access */
defined('ADAPT_STARTED') or die;

$adapt = $GLOBALS['adapt'];
$sql = $adapt->data_source->sql;


$sql->create_table('task')
    ->add('task_id', 'bigint')
    ->add('bundle_name', 'varchar(128)', false)
    ->add('name', 'varchar(64)', false)
    ->add('status', "enum('paused', 'waiting', 'spawned', 'running')", false, 'waiting')
    ->add('label', 'varchar(128)')
    ->add('description', 'text')
    ->add('class_name', 'varchar(256)', false) //The namespace and path to the class to run the task, it must be an instance of '\extensions\scheduler\task'
    ->add('minutes', 'varchar(32)')
    ->add('hours', 'varchar(32)')
    ->add('days_of_month', 'varchar(32)')
    ->add('days_of_week', 'varchar(32)')
    ->add('months', 'varchar(32)')
    ->add('date_created', 'datetime')
    ->add('date_modified', 'timestamp')
    ->add('date_deleted', 'datetime')
    ->primary_key('task_id')
    ->execute();

$sql->create_table('task_log')
    ->add('task_log_id', 'bigint')
    ->add('task_id', 'bigint')
    ->add('label', 'varchar(128)') //Default to the tasks label
    ->add('output', 'text')
    ->add('progress', 'int', true, 0)
    ->add('date_created', 'datetime')
    ->add('date_spawned', 'datetime')
    ->add('date_started', 'datetime')
    ->add('date_ended', 'datetime')
    ->add('date_modified', 'timestamp')
    ->add('date_deleted', 'datetime')
    ->primary_key('task_log_id')
    ->foreign_key('task_id', 'task', 'task_id')
    ->execute();




?>