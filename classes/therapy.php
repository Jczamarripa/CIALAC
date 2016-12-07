<?php
    //use files
    require_once('connection.php');
    require_once('exceptions.php');

    class Therapy extends Connection{
        private $id;
        private $name;
        private $description;
        
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
            }
            if(func_num_args() == 1){
                $args = func_get_args();
                $id = $args[0];
                parent::open_connection();
                $query = "select therapy_id, therapy_name, therapy_description from Therapies_Therapy where therapy_id = ?";
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
        
        public function get_childs(){
            parent::open_connection();
            $ids = array();
            $list = array();
            $query = "select child_id from Therapies_Therapy_Child where therapy_id = ?";
            $command = parent::$connection->prepare($query);
            $command->bind_param('s',$this->id);
            $command->execute();
            $command->bind_result($id);
            while($command->fetch()) array_push($ids,$id);
            mysqli_stmt_close($command);
            parent::close_connection();
            for($i = 0; $i < count($ids); $i++) array_push($list, new Child($ids[$i]));
            return $list;
        }
    }