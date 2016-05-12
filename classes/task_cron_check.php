<?php

namespace adapt\scheduler{
    
    /* Prevent Direct Access */
    defined('ADAPT_STARTED') or die;
    
    class task_cron_check extends task{
        
        public function task(){
            parent::task();
            
            $this->_log->label = "Cleaning cron";
            $this->_log->save();
            
            $output = "";
            
            $c = new \adapt\date();
            $c->goto_hours(1);
            
            $sql = $this->data_source->sql
                ->select('*')
                ->from('task', 't')
                ->where(
                    new sql_and(
                        new sql_cond('t.status', sql::EQUALS, sql::q('running')),
                        new sql_cond('t.date_modified', sql::GREATER_THAN, sql::q($c->date("Y-m-d H:i:s"))),
                        new sql_cond('t.date_deleted', sql::IS, new sql_null())
                    )
                );
            
            /* Disable caching and get the results */
            $results = $sql->execute(0)->results();
            
            if (count($results)){
                foreach($results as $result_data){
                    $task = new model_task();
                    $task->load_by_data($result_data);
                    $task->status = "waiting";
                    $task->save();
                    $output .= "Restarted task #{$task->task_id} {$task->name} because the task took more than an hour to run\n";
                }
            }else{
                $output .= "Everything is working fine\n";
            }
            
            /* Children should override this with the code they wish to run */
            return $output;
        }

    }
    
}

?>