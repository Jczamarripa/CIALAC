<?php
    //use files
    require_once('connection.php');
    require_once('exceptions.php');
    require_once('activity.php');
    require_once('day.php');

    class Activity extends Connection{
        private $id;
        private $name;
        private $note;
        
        public function get_id(){ return $this->id; }
        public function set_id($value){ $this->id = $value; }
        public function get_name(){ return $this->name; }
        public function set_name($value){ $this->name = $value; }
        public function get_description(){ return $this->description; }
        public function set_description($value){ $this->description = $value; }
        
        function __construct(){
            if(func_num_args() == 0){
                $this->id = '';
                $this->name = '';
                $this->description = '';
                $this->dosification = '';
                $this->peridiocity = "";
            }
            if(func_num_args() == 1){
                $args = func_get_args();
                $id = $args[0];
                parent::open_connection();
                $query = "select schedule_id, schedule_name, schedule_note from Activities_Schedule where schedule_id = ?";
                $command = parent::$connection->prepare($query);
                $command->bind_param('s', $id);
                $command->execute();
                $command->bind_result($this->id, $this->name, $this->description);
                $found = $command->fetch();
                mysqli_stmt_close($command);
                parent::close_connection();  
                if(!$found) throw(new RecordNotFoundException());          
            }
            
        }
    }