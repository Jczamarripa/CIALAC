<?php
    //use files
    require_once('connection.php');
    require_once('exceptions.php');

    class Day extends Connection{
        private $id;
        private $name;
        
        public function get_id(){ return $this->id; }
        public function set_id($value){ $this->id = $value; }
        public function get_name(){ return $this->name; }
        public function set_name($value){ $this->name = $value; }
        
        function __construct(){
            if(func_num_args() == 0){
                $this->id = '';
                $this->name = '';
            }
            if(func_num_args() == 1){
                $args = func_get_args();
                $id = $args[0];
                parent::open_connection();
                $query = "select day_id, day_name from Activities_Days where day_id = ?";
                $command = parent::$connection->prepare($query);
                $command->bind_param('i', $id);
                $command->execute();
                $command->bind_result($this->id, $this->name);
                $found = $command->fetch();
                mysqli_stmt_close($command);
                parent::close_connection();  
                if(!$found) throw(new RecordNotFoundException());          
            }   
        }
    }