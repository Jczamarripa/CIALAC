<?php
    //use files
    require_once('connection.php');
    require_once('exceptions.php');
    require_once('activity.php');
    require_once('day.php');

    class Schedule extends Connection{
        private $id;
        private $begin_hour;
        private $end_hour;
        
        public function get_id(){ return $this->id; }
        public function set_id($value){ $this->id = $value; }
        public function get_begin_hour(){ return $this->begin_hour; }
        public function set_begin_hour($value){ $this->begin_hour = $value; }
        public function get_end_hour(){ return $this->end_hour; }
        public function set_end_hour($value){ $this->end_hour = $value; }
        
        function __construct(){
            if(func_num_args() == 0){
                $this->id = '';
                $this->begin_hour = '';
                $this->end_hour = '';
            }
            if(func_num_args() == 1){
                $args = func_get_args();
                $id = $args[0];
                parent::open_connection();
                $query = "select schedule_id, activitie_begin_hour, activitie_end_hour from Activities_Schedule_Days where schedule_id = ?";
                $command = parent::$connection->prepare($query);
                $command->bind_param('s', $id);
                $command->execute();
                $command->bind_result($this->id, $this->begin_hour, $this->end_hour);
                $found = $command->fetch();
                mysqli_stmt_close($command);
                parent::close_connection();  
                if(!$found) throw(new RecordNotFoundException());          
            }
            
        }
        
        public function get_days(){
                parent::open_connection();
                $ids = array();
                $list = array();
                $query = "select day_id from Activities_Schedule_Days where schedule_id = ?";
                $command = parent::$connection->prepare($query);
                $command->bind_param('i',$this->id);
                $command->execute();
                $command->bind_result($id);
                while($command->fetch()) array_push($ids,$id);
                mysqli_stmt_close($command);
                parent::close_connection();
                for($i = 0; $i < count($ids); $i++) array_push($list, new Day($ids[$i]));
                return $list;
        }
        
        public function get_activities(){
                parent::open_connection();
                $ids = array();
                $list = array();
                $query = "select schedule_id from Activities_Schedule_Days where schedule_id = ?";
                $command = parent::$connection->prepare($query);
                $command->bind_param('i',$this->id);
                $command->execute();
                $command->bind_result($id);
                while($command->fetch()) array_push($ids,$id);
                mysqli_stmt_close($command);
                parent::close_connection();
                for($i = 0; $i < count($ids); $i++) array_push($list, new Activity($ids[$i]));
                return $list;
        }
    }