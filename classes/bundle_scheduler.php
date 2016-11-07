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
                    $command = 'bash -c "exec nohup setsid ' . $path . ' ' . ADAPT_PATH . ' > /dev/null 2>&1 &"';
                    //print $command;die();
                    exec($command);
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
        }
    }
    
    
}

?>