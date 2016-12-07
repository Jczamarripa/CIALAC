<?php
    //use files
    require_once('connection.php');
    require_once('exceptions.php');
    require_once('person.php');
    require_once('therapy.php');
    require_once('activity.php');

    class Child extends Connection{
        private $id;
        private $photo;
        private $first_name;
        private $last_name;
        private $date_of_birth;
        private $skills;
        private $status;
        
        public function get_id(){ return $this->id; }
        public function set_id($value){ $this->id = $value; }
        public function get_photo(){ return $this->photo; }
        public function set_photo($value){ $this->photo = $value; }
        public function get_first_name(){ return $this->first_name; }
        public function set_first_name($value){ $this->first_name = $value; }
        public function get_last_name(){ return $this->last_name; }
        public function set_last_name($value){ $this->last_name = $value; }
        public function get_date_of_birth(){ return $this->date_of_birth; }
        public function set_date_of_birth($value){ $this->date_of_birth = $value; }
        public function get_skills(){ return $this->skills; }
        public function set_skills($value){ $this->skills = $value; }
        public function get_status(){ return $this->status; }
        public function set_status($value){ $this->status = $value; }
        
        function __construct(){
            if(func_num_args() == 0){
                $this->id = '';
                $this->photo = '';
                $this->first_name = '';
                $this->last_name = '';
                $this->date_of_birth = '';
                $this->skills = '';
                $this->status = '';
            }
            if(func_num_args() == 1){
                $args = func_get_args();
                $id = $args[0];
                parent::open_connection();
                $query = "select child_id, child_photo, child_first_name, child_last_name, child_date_of_birth, child_skills, child_status from Persons_Child where child_id = ?";
                $command = parent::$connection->prepare($query);
                $command->bind_param('s', $id);
                $command->execute();
                $command->bind_result($this->id, $this->photo, $this->first_name, $this->last_name, $this->date_of_birth, $this->skills, $this->status);
                $found = $command->fetch();
                mysqli_stmt_close($command);
                parent::close_connection();
                if(!$found) throw(new RecordNotFoundException());
            }
        }
        
        public function get_tutor(){
            parent::open_connection();
            $query = "select child_tutor_id from Persons_Child where child_id = ?";
            $command = parent::$connection->prepare($query);
            $command->bind_param('s', $this->id);
            $command->execute();
            $command->bind_result($id);
            $command->fetch();
            mysqli_stmt_close($command);
            parent::close_connection();
            return $id;
        }
        
        public function get_transporter(){
            parent::open_connection();
            $query = "select child_transporter_id from Persons_Child where child_id = ?";
            $command = parent::$connection->prepare($query);
            $command->bind_param('s', $this->id);
            $command->execute();
            $command->bind_result($id);
            $command->fetch();
            mysqli_stmt_close($command);
            parent::close_connection();
            return $id;
        }
        
        public function get_medicines(){
            parent::open_connection();
            $ids = array();
            $list = array();
            $query = "select medicine_id from Medicines_Medicine_Child where child_id = ?";
            $command = parent::$connection->prepare($query);
            $command->bind_param('s',$this->id);
            $command->execute();
            $command->bind_result($id);
            while($command->fetch()) array_push($ids,$id);
            mysqli_stmt_close($command);
            parent::close_connection();
            for($i = 0; $i < count($ids); $i++) array_push($list, new Medicine($ids[$i]));
            return $list;
        }
        
        public function get_therapies(){
            parent::open_connection();
            $ids = array();
            $list = array();
            $query = "select therapy_id from Therapies_Therapy_Child where child_id = ?";
            $command = parent::$connection->prepare($query);
            $command->bind_param('s',$this->id);
            $command->execute();
            $command->bind_result($id);
            while($command->fetch()) array_push($ids,$id);
            mysqli_stmt_close($command);
            parent::close_connection();
            for($i = 0; $i < count($ids); $i++) array_push($list, new Therapy($ids[$i]));
            return $list;
        }
        
        public function get_schedule(){
            parent::open_connection();
            $ids = array();
            $list = array();
            $query = "select schedule_id from Activities_Schedule_Days where child_id = ?";
            $command = parent::$connection->prepare($query);
            $command->bind_param('s',$this->id);
            $command->execute();
            $command->bind_result($id);
            while($command->fetch()) array_push($ids,$id);
            mysqli_stmt_close($command);
            parent::close_connection();
            for($i = 0; $i < count($ids); $i++) array_push($list, new Schedule($ids[$i]));
            return $list;
        }
    }