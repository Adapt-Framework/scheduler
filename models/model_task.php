<?php

namespace adapt\scheduler{
    
    /* Prevent direct access */
    defined('ADAPT_STARTED') or die;
    
    class model_task extends \adapt\model{
        
        public function __construct($id = null, $data_source = null){
            parent::__construct('task', $id, $data_source);
        }
        
        public function parse_minutes(){
            $mins = array();
            
            $minutes = explode(",", $this->minutes);
            $matches = array();
            
            foreach($minutes as $group){
                if (preg_match("/^[0-9]+$/", $group)){
                    $min = intval($group);
                    if (!in_array($min, $mins)) $mins[] = $min;
                    
                }elseif(preg_match_all("/^([0-9]+)-([0-9]+)$/", $group, $matches)){
                    $lower = intval($matches[1][0]);
                    $upper = intval($matches[2][0]);
                    if ($lower < $upper){
                        for($i = $lower; $i <= $upper; $i++){
                            if (!in_array($i, $mins)) $mins[] = $i;
                        }
                    }
                }elseif($group == "*"){
                    for($i = 0; $i <= 59; $i++){
                        if (!in_array($i, $mins)) $mins[] = $i;
                    }
                }elseif(preg_match_all("/^\*\/(2|3|4|5|6|10|12|15|20|30)$/", $group, $matches)){
                    $modifier = intval($matches[1][0]);
                    $min = 60 / $modifier;
                    
                    for($i = 0; $i < 60; $i += $min){
                        if (!in_array($i, $mins)) $mins[] = $i;
                    }
                    
                    //print ">>{$min}<<\n";
                }
            }
            
            sort($mins);
            
            return $mins;
        }
        
        public function parse_hours(){
            $output = array();
            
            $hours = explode(",", $this->hours);
            $matches = array();
            
            foreach($hours as $group){
                if (preg_match("/^[0-9]+$/", $group)){
                    $hour = intval($group);
                    if (!in_array($hour, $output)) $output[] = $hour;
                    
                }elseif(preg_match_all("/^([0-9]+)-([0-9]+)$/", $group, $matches)){
                    $lower = intval($matches[1][0]);
                    $upper = intval($matches[2][0]);
                    if ($lower < $upper){
                        for($i = $lower; $i <= $upper; $i++){
                            if (!in_array($i, $output)) $output[] = $i;
                        }
                    }
                }elseif($group == "*"){
                    for($i = 0; $i <= 23; $i++){
                        if (!in_array($i, $output)) $output[] = $i;
                    }
                }elseif(preg_match_all("/^\*\/(2|3|4|6|8|12)$/", $group, $matches)){
                    $modifier = intval($matches[1][0]);
                    $hour = 24 / $modifier;
                    
                    for($i = 0; $i < 24; $i += $hour){
                        if (!in_array($i, $output)) $output[] = $i;
                    }
                    
                    //print ">>{$min}<<\n";
                }
            }
            
            sort($output);
            
            return $output;
        }
        
        
        public function parse_day_of_month(){
            $output = array();
            
            $days_of_month = explode(",", $this->days_of_month);
            $matches = array();
            
            foreach($days_of_month as $group){
                if (preg_match("/^[0-9]+$/", $group)){
                    $dom = intval($group);
                    if (!in_array($dom, $output)) $output[] = $dom;
                    
                }elseif(preg_match_all("/^([0-9]+)-([0-9]+)$/", $group, $matches)){
                    $lower = intval($matches[1][0]);
                    $upper = intval($matches[2][0]);
                    if ($lower < $upper){
                        for($i = $lower; $i <= $upper; $i++){
                            if (!in_array($i, $output)) $output[] = $i;
                        }
                    }
                }elseif($group == "*"){
                    for($i = 1; $i <= 31; $i++){
                        if (!in_array($i, $output)) $output[] = $i;
                    }
                }
            }
            
            sort($output);
            
            return $output;
        }
        
        public function parse_months(){
            $output = array();
            
            $months = explode(",", strtoupper($this->months));
            $matches = array();
            
            foreach($months as $group){
                $reps = array(
                    'JAN' => '1',
                    'FEB' => '2',
                    'MAR' => '3',
                    'APR' => '4',
                    'MAY' => '5',
                    'JUN' => '6',
                    'JUL' => '7',
                    'AUG' => '8',
                    'SEP' => '9',
                    'OCT' => '10',
                    'NOV' => '11',
                    'DEC' => '12'
                );
                
                foreach($reps as $rep => $val){
                    $group = preg_replace("/{$rep}/", $val, $group);
                }
                
                if (preg_match("/^[0-9]+$/", $group)){
                    $dom = intval($group);
                    if (!in_array($dom, $output)) $output[] = $dom;
                    
                }elseif(preg_match_all("/^([0-9]+)-([0-9]+)$/", $group, $matches)){
                    $lower = intval($matches[1][0]);
                    $upper = intval($matches[2][0]);
                    if ($lower < $upper){
                        for($i = $lower; $i <= $upper; $i++){
                            if (!in_array($i, $output)) $output[] = $i;
                        }
                    }
                }elseif($group == "*"){
                    for($i = 1; $i <= 12; $i++){
                        if (!in_array($i, $output)) $output[] = $i;
                    }
                }
            }
            
            sort($output);
            
            return $output;
        }
        
        public function parse_day_of_week(){
            $output = array();
            
            $day_of_week = explode(",", strtoupper($this->days_of_week));
            $matches = array();
            
            foreach($day_of_week as $group){
                $reps = array(
                    'SUN' => '0',
                    'MON' => '1',
                    'TUE' => '2',
                    'WED' => '3',
                    'THU' => '4',
                    'FRI' => '5',
                    'SAT' => '6'
                );
                
                foreach($reps as $rep => $val){
                    $group = preg_replace("/{$rep}/", $val, $group);
                }
                
                if (preg_match("/^[0-9]+$/", $group)){
                    $dom = intval($group);
                    if (!in_array($dom, $output)) $output[] = $dom;
                    
                }elseif(preg_match_all("/^([0-9]+)-([0-9]+)$/", $group, $matches)){
                    $lower = intval($matches[1][0]);
                    $upper = intval($matches[2][0]);
                    if ($lower < $upper){
                        for($i = $lower; $i <= $upper; $i++){
                            if (!in_array($i, $output)) $output[] = $i;
                        }
                    }
                }elseif($group == "*"){
                    for($i = 1; $i <= 7; $i++){
                        if (!in_array($i, $output)) $output[] = $i;
                    }
                }
            }
            
            sort($output);
            
            return $output;
        }
        
        public function can_run(){
            
            if ($this->status != "waiting"){
                return false;
            }
            
            $minutes = $this->parse_minutes();
            
            if (in_array(intval(date('i')), $minutes)){
                //print "Passed minutes\n";
                
                $hours = $this->parse_hours();
                
                if (in_array(intval(date('H')), $hours)){
                    //print "Passed hours\n";
                    
                    $day_of_month = $this->parse_day_of_month();
                    
                    if (in_array(intval(date('d')), $day_of_month)){
                        //print "Passed dom\n";
                        
                        $months = $this->parse_months();
                        
                        //print_r($months);
                        if (in_array(intval(date('m')), $months)){
                            //print "Passed months\n";
                            return true;
                            
                        }else{
                            //print "Failed months\n";
                            return false;
                        }
                    }else{
                        
                        //print "Failed day of month\n";
                        return false;
                    }
                    
                }else{
                    //print "Failed hours\n";
                    return false;
                }
                
            }else{
                //print "Failed minutes\n";
                return false;
            }
            
        }
        
        public function run(){
            if ($this->is_loaded && $this->can_run()){
                $this->status = 'spawned';
                if ($this->save()){
                    $path = ADAPT_PATH . $this->name . "/" . $this->name . "-" . $this->version . "/cli/task.php";
                    $command = 'bash -c "exec nohup setsid ' . $path . ' ' . ADAPT_PATH . ' ' . $this->taks_id . ' > /dev/null 2>&1 &"';
                    exec($command);
                }
            }
        }
        
        public function execute(){
            if ($this->is_loaded && $task->status == 'spawned'){
                $log = new model_task_log();
                $log->task_id = $task->task_id;
                
                $class = $this->class_name;
                
                if (class_exists($class)){
                    $task = new $class($log, $this);
                    
                    if ($task instanceof \adapt\scheduler\task){
                        $task->start();
                        $task->task();
                        $task->end();
                    }
                }
            }
        }
        
        //public function can_run($date = null){
        //    
        //    if ($this->is_loaded){
        //        print "Chcking\n";
        //    }
        //    
        //    
        //    return false;
        //
        //
        //    $date = new \adapt\date($date);
        //    $months = $this->get_months();
        //    $matched = false;
        //    foreach($months as $month){
        //        if ($month == '*' || $month == $date->date('n')){
        //            $matched = true;
        //            break;
        //        }
        //    }
        //    
        //    if ($matched){
        //        
        //        $matched = false;
        //        $days_of_month = $this->get_days_of_month();
        //        foreach($days_of_month as $day){
        //            if ($day == '*' || $day == $date->day){
        //                $matched = true;
        //                break;
        //            }
        //        }
        //        
        //        if ($matched){
        //            
        //            $matched = false;
        //            $days_of_week = $this->get_days_of_week();
        //            
        //            foreach($days_of_week as $day){
        //                if ($day == '*' || $day == $date->date('w')){
        //                    $matched = true;
        //                    break;
        //                }
        //            }
        //            
        //            if ($matched){
        //                
        //                $matched = false;
        //                $hours = $this->get_hours();
        //                
        //                foreach($hours as $hour){
        //                    if ($hour == '*' || $hour == $date->hour){
        //                        $matched = true;
        //                        break;
        //                    }
        //                }
        //                
        //                if ($matched){
        //                    
        //                    $minutes = $this->get_minutes();
        //                    
        //                    foreach($minutes as $minute){
        //                        if ($minute == '*' || $minute == $date->minute){
        //                            return true;
        //                        }
        //                    }
        //                }
        //            }
        //        }
        //        
        //    }
        //    
        //    return false;
        //}
        
        //public function can_spawn(){
        //    $date = new \adapt\date($date);
        //    $date->goto_hours($this->setting('scheduler.spawn_time'));
        //    
        //    for($i = 0; $i <= 59; $i++){
        //        $date->minute = $i;
        //        //print "<pre>" . $date->date('Y-m-d H:i:s') . "</pre>";
        //        if ($this->can_run($date->date('Y-m-d H:i:s'))){
        //            return true;
        //        }
        //    }
        //    
        //    return false;
        //    //return $this->can_run($date->date('Y-m-d H:i:s'));
        //}
        //
        //public function spawn(){
        //    if ($this->is_loaded){
        //        $this->status = 'spawned';
        //        $this->save();
        //        
        //        $log = new model_task_log();
        //        $log->task_id = $this->task_id;
        //        $log->label = $this->label;
        //        $log->date_spawned = $this->data_source->sql('now()');
        //        $log->save();
        //        
        //        $id = $log->task_log_id;
        //        
        //        if ($id){
        //            $pid = shell_exec(EXTENSION_PATH . "scheduler/cli/task {$id} > /tmp/matt.log 2>&1 & echo \$!");
        //            $log->output = "PID {$pid}";
        //            $log->save();
        //            return true;
        //        }else{
        //            $this->status = 'waiting';
        //            $this->save();
        //        }
        //    }
        //    return false;
        //}
        //
        //public function get_months(){
        //    return $this->parse_time($this->months);
        //}
        //
        //public function get_days_of_week(){
        //    return $this->parse_time($this->days_of_week);
        //}
        //
        //public function get_days_of_month(){
        //    return $this->parse_time($this->days_of_month);
        //}
        //
        //public function get_hours(){
        //    return $this->parse_time($this->hours);
        //}
        //
        //public function get_minutes(){
        //    return $this->parse_time($this->minutes);
        //}
        //
        //public function parse_time($time){
        //    $values = array();
        //    
        //    /* Break up groups */
        //    $groups = explode(",", $time);
        //    foreach($groups as $value){
        //        $value = trim($value);
        //        /* Check for ranges */
        //        if (preg_match("/^[0-9]+\-[0-9]+$/", $value)){
        //            list($start, $end) = explode("-", $value);
        //            $start = intval($start);
        //            $end = intval($end);
        //            if ($start > $end){
        //                for($i = $start; $i <= $end; $i++){
        //                    $values[] = $i;
        //                }
        //            }
        //        }elseif(preg_match("/^[0-9]$/", $value)){
        //            $values[] = intval($value);
        //        }elseif($value == '*'){
        //            $values[] = $value;
        //        }
        //    }
        //    return $values;
        //}
    }
    
}

?>