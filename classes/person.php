<?php
    //use files
    require_once('connection.php');
    require_once('exceptions.php');

    class Person extends Connection{
        private $id;
        private $role;
        private $photo;
        private $first_name;
        private $last_name;
        private $date_of_birth;
        private $phone;
        private $email;
        private $password;
        private $address;
        
        public function get_id(){ return $this->id; }
        public function set_id($value){ $this->id = $value; }
        public function get_role(){ return $this->role; }
        public function set_role($value){ $this->role = $value; }
        public function get_photo(){ return $this->photo; }
        public function set_photo($value){ $this->photo = $value; }
        public function get_first_name(){ return $this->first_name; }
        public function set_first_name($value){ $this->first_name = $value; }
        public function get_last_name(){ return $this->last_name; }
        public function set_last_name($value){ $this->last_name = $value; }
        public function get_date_of_birth(){ return $this->date_of_birth; }
        public function set_date_of_birth($value){ $this->date_of_birth = $value; }
        public function get_phone(){ return $this->phone; }
        public function set_phone($value){ $this->phone = $value; }
        public function get_email(){ return $this->email; }
        public function set_email($value){ $this->email = $value; }
        public function get_pasword(){ return $this->password; }
        public function set_password($value){ $this->password = $value; }
        public function get_address(){ return $this->address; }
        public function set_address($value){ $this->address = $value; }
        
        function __construct(){
            if(func_num_args() == 0){
                $this->id = '';
                $this->role = '';
                $this->photo = '';
                $this->first_name = '';
                $this->last_name = '';
                $this->date_of_birth = '';
                $this->phone = '';
                $this->email = '';
                $this->password = '';
                $this->address = '';
            }
            if(func_num_args() == 1){
                $args = func_get_args();
                $id = $args[0];
                parent::open_connection();
                $query = "select person_id, person_role, person_photo, person_first_name, person_last_name, person_date_of_birth, person_phone, person_email, person_password, person_address from Persons_Person where person_id = ?";
                $command = parent::$connection->prepare($query);
                $command->bind_param('s', $id);
                $command->execute();
                $command->bind_result($this->id, $this->role, $this->photo, $this->first_name, $this->last_name, $this->date_of_birth, $this->phone, $this->email, $this->password, $this->address);
                $found = $command->fetch();
                mysqli_stmt_close($command);
                parent::close_connection();  
                if(!$found) throw(new RecordNotFoundException());          
            }
            if  (func_num_args() == 2){
                $args = func_get_args();
                $email = $args[0];
                $password = $args[1];
                parent::open_connection();
                $query ="select person_id, person_role, person_photo, person_first_name, person_last_name, person_date_of_birth, person_phone, person_email, person_address from Persons_Person where person_email = ? and person_password = sha1(?)";
                $command = parent::$connection->prepare($query);
                $command->bind_param('ss', $email,$password);
                $command->execute();
                $command->bind_result($this->id, $this->role, $this->photo, $this->first_name, $this->last_name, $this->date_of_birth, $this->phone, $this->email, $this->address);
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
            $query = "select child_id from Persons_Child where child_tutor_id = ?";
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
        
        public function get_transporter(){
            parent::open_connection();
            $query = "select child_transporter_id from Persons_Child where child_tutor_id = ?";
            $command = parent::$connection->prepare($query);
            $command->bind_param('s', $this->id);
            $command->execute();
            $command->bind_result($id);
            $command->fetch();
            mysqli_stmt_close($command);
            parent::close_connection();
            return $id;
        }
    }