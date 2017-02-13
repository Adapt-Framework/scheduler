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
                
                if (!$this->is_cron_running()){
                    $path = ADAPT_PATH . $this->name . "/" . $this->name . "-" . $this->version . "/cli/cron.php";
                    $command = 'bash -c "exec nohup setsid ' . $path . ' ' . ADAPT_PATH . ' ' . ADAPT_VERSION . ' ' . $this->version . ' > /dev/null 2>&1 &"';
                    //print $command;
                    exec($command);
                }
                
                // Listen for Adapt updates
                \adapt\bundle::listen(
                    \adapt\bundle::EVENT_ON_UPDATE, 
                    function($data){
                        if ($data['event_data']['bundle_name'] == 'adapt'){
                            // Restart the scheduler
                            $command = "pgrep cron.php | xargs kill";
                            exec($command);
                        }
                    }
                );
                
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
        }
        
        public function update(){
            $new_version = parent::update();
            
            if ($new_version !== false){
                // Restart the scheduler
                $command = "pgrep cron.php | xargs kill";
                exec($command);
                return true;
            }
            
            return false;
        }
    }
    
    
}

?>