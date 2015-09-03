<?php

namespace extensions\scheduler{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class model_task extends \frameworks\adapt\model{
        
        public function __construct($id = null, $data_source = null){
            parent::__construct('task', $id, $data_source);
        }
        
        public function can_run($date = null){
            $date = new \frameworks\adapt\date($date);
            $months = $this->get_months();
            $matched = false;
            foreach($months as $month){
                if ($month == '*' || $month == $date->date('n')){
                    $matched = true;
                    break;
                }
            }
            
            if ($matched){
                
                $matched = false;
                $days_of_month = $this->get_days_of_month();
                foreach($days_of_month as $day){
                    if ($day == '*' || $day == $date->day){
                        $matched = true;
                        break;
                    }
                }
                
                if ($matched){
                    
                    $matched = false;
                    $days_of_week = $this->get_days_of_week();
                    
                    foreach($days_of_week as $day){
                        if ($day == '*' || $day == $date->date('w')){
                            $matched = true;
                            break;
                        }
                    }
                    
                    if ($matched){
                        
                        $matched = false;
                        $hours = $this->get_hours();
                        
                        foreach($hours as $hour){
                            if ($hour == '*' || $hour == $date->hour){
                                $matched = true;
                                break;
                            }
                        }
                        
                        if ($matched){
                            
                            $minutes = $this->get_minutes();
                            
                            foreach($minutes as $minute){
                                if ($minute == '*' || $minute == $date->minute){
                                    return true;
                                }
                            }
                        }
                    }
                }
                
            }
            
            return false;
        }
        
        public function can_spawn(){
            $date = new \frameworks\adapt\date($date);
            $date->goto_hours($this->setting('scheduler.spawn_time'));
            
            for($i = 0; $i <= 59; $i++){
                $date->minute = $i;
                //print "<pre>" . $date->date('Y-m-d H:i:s') . "</pre>";
                if ($this->can_run($date->date('Y-m-d H:i:s'))){
                    return true;
                }
            }
            
            return false;
            //return $this->can_run($date->date('Y-m-d H:i:s'));
        }
        
        public function spawn(){
            if ($this->is_loaded){
                $this->status = 'spawned';
                $this->save();
                
                $log = new model_task_log();
                $log->task_id = $this->task_id;
                $log->label = $this->label;
                $log->date_spawned = $this->data_source->sql('now()');
                $log->save();
                
                $id = $log->task_log_id;
                
                if ($id){
                    $pid = shell_exec(EXTENSION_PATH . "scheduler/cli/task {$id} > /tmp/matt.log 2>&1 & echo \$!");
                    $log->output = "PID {$pid}";
                    $log->save();
                    return true;
                }else{
                    $this->status = 'waiting';
                    $this->save();
                }
            }
            return false;
        }
        
        public function get_months(){
            return $this->parse_time($this->months);
        }
        
        public function get_days_of_week(){
            return $this->parse_time($this->days_of_week);
        }
        
        public function get_days_of_month(){
            return $this->parse_time($this->days_of_month);
        }
        
        public function get_hours(){
            return $this->parse_time($this->hours);
        }
        
        public function get_minutes(){
            return $this->parse_time($this->minutes);
        }
        
        public function parse_time($time){
            $values = array();
            
            /* Break up groups */
            $groups = explode(",", $time);
            foreach($groups as $value){
                $value = trim($value);
                /* Check for ranges */
                if (preg_match("/^[0-9]+\-[0-9]+$/", $value)){
                    list($start, $end) = explode("-", $value);
                    $start = intval($start);
                    $end = intval($end);
                    if ($start > $end){
                        for($i = $start; $i <= $end; $i++){
                            $values[] = $i;
                        }
                    }
                }elseif(preg_match("/^[0-9]$/", $value)){
                    $values[] = intval($value);
                }elseif($value == '*'){
                    $values[] = $value;
                }
            }
            return $values;
        }
    }
    
}

?>