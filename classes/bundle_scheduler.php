<?php

namespace adapt\scheduler{
    
    /* Prevent Direct Access */
    defined('ADAPT_STARTED') or die;
    
    class bundle_scheduler extends \adapt\bundle{
        
        public function __construct($data){
            parent::__construct('scheduler', $data);
        }
        
        public function boot(){
            if (parent::boot()){
                //print "Task booting";
                
                if (!$this->is_cron_running()){
                    //print new html_h2("Starting cron");
                    $path = ADAPT_PATH . $this->name . "/" . $this->name . "-" . $this->version . "/cli/cron.php";
                    $command = 'bash -c "exec nohup setsid ' . $path . ' ' . ADAPT_PATH . ' > /dev/null 2>&1 &"';
                    print new html_pre($command);
                    //print $command;
                    //exec($command);
                }
                
                return true;
            }
            
            return false;
        }
        
        public function is_cron_running(){
            $command = "pgrep cron.php";
            $output = array();
            $return = null;
            exec($command, $output, $return);
            return ($return == 0);
            //print new html_pre(print_r($output, true));
            //if (is_array($output) && count($output) && preg_match("/^[0-9]+$/", $output[0])){
            //    return true;
            //}
            //
            //return false;
        }
        
        //public function boot(){
        //    if (parent::boot()){
        //        
        //        $sql = $this->data_source->sql;
        //        
        //        /* Only run for one in ten requests */
        //        if (!isset($_SERVER['SHELL']) && rand(5, 5) == 5){
        //            print "<pre>Checking for scheduled tasks</pre>";
        //            /*
        //             * Find any tasks that are due to
        //             * run in the next hour that haven't
        //             * yet been spawned
        //             */
        //            
        //            $tasks = $sql->select('*')
        //                ->from('task', 't')
        //                //->join('schedule_log', 'l', 'schedule_id')
        //                ->where(
        //                    new \adapt\sql_and(
        //                        new \adapt\sql_condition(
        //                            new \adapt\sql('t.date_deleted'),
        //                            'is',
        //                            new \adapt\sql('null')
        //                        ),
        //                        /*new \frameworks\adapt\sql_condition(
        //                            new \frameworks\adapt\sql('l.date_deleted'),
        //                            'is',
        //                            new \frameworks\adapt\sql('null')
        //                        ),*/
        //                        new \adapt\sql_condition(
        //                            new \adapt\sql('t.status'),
        //                            '=',
        //                            'waiting'
        //                        )
        //                    )
        //                )
        //                ->execute(0) //No caching
        //                ->results();
        //            
        //            foreach($tasks as $task_item){
        //                print new html_pre(print_r($task_item, true));
        //                $task = new model_task();
        //                $task->load_by_data($task_item);
        //                
        //                if ($task->can_spawn()){
        //                    //Spawn the task
        //                    print "<pre>Can spawn</pre>";
        //                    $task->spawn();
        //                }
        //                
        //            }   
        //        }
        //        
        //        return true;
        //    }
        //    
        //    return false;
        //}
        
    }
    
    
}

?>