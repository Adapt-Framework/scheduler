<?php

namespace adapt\scheduler{
    
    /* Prevent Direct Access */
    defined('ADAPT_STARTED') or die;
    
    class task extends \adapt\base{
        
        /*
         * This class is called from the command
         * line and so both the dom and sessions
         * are unavailable
         */
        
        protected $_log;
        protected $_task;
        protected $_output;
        
        public function __construct($task_log, $task){
            $this->_log = $task_log;
            $this->_task = $task;
        }
        
        public function set_progress($progress){
            $progress = intval($progress);
            if ($progress >= 0 && $progress <= 100 && $this->_log->is_loaded){
                $this->_log->progress = $progress;
                $this->_log->save();
            }
        }
        
        public function spawn(){
            /* This function is fired directly from cli
             * it is responsible for setting the spawn
             * date(nope) and for calling start(), task() and end()
             */
            
            if ($this->_log->is_loaded){
                if ($this->_task->is_loaded){
                    while (!$this->_task->can_run()){
                        sleep(60);
                    }
                    
                    $this->start();
                    $this->_output = $this->task();
                    $this->end();
                }
            }
        }
        
        public function start(){
            /*
             * Sets the started date
             */
            $this->_task->status = 'running';
            $this->_task->save();
            $this->_log->date_started = $this->data_source->sql('now()');
            $this->_log->save();
            
            $this->set_progress(0);
            
            $this->_output = $this->task();
            $this->end();
        }
        
        public function task(){
            /* Children should override this with the code they wish to run */
        }
        
        public function end(){
            /*
             * Sets the ended date
             */
            $this->_task->status = 'waiting';
            $this->_task->save();
            $this->_log->output = $this->_output;
            $this->_log->date_ended = $this->data_source->sql('now()');
            $this->_log->save();
            
            $this->set_progress(100);
        }
        
        
        
        
    }
    
}

?>