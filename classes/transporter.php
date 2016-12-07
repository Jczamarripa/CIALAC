<?php
    //use files
    require_once('connection.php');
    require_once('exceptions.php');

    class Transporter extends Connection{
        private $id;
        private $photo;
        private $first_name;
        private $last_name;
        private $phone;
        private $email;
        private $address;
        
        public function get_id(){ return $this->id; }
        public function set_id($value){ $this->id = $value; }
        public function get_photo(){ return $this->photo; }
        public function set_photo($value){ $this->photo = $value; }
        public function get_first_name(){ return $this->first_name; }
        public function set_first_name($value){ $this->first_name = $value; }
        public function get_last_name(){ return $this->last_name; }
        public function set_last_name($value){ $this->last_name = $value; }
        public function get_phone(){ return $this->phone; }
        public function set_phone($value){ $this->phone = $value; }
        public function get_email(){ return $this->email; }
        public function set_email($value){ $this->email = $value; }
        public function get_address(){ return $this->address; }
        public function set_address($value){ $this->address = $value; }
        
        function __construct(){
            if(func_num_args() == 0){
                $this->id = '';
                $this->photo = '';
                $this->first_name = '';
                $this->last_name = '';
                $this->phone = '';
                $this->email = '';
                $this->address = '';
            }
            if(func_num_args() == 1){
                $args = func_get_args();
                $id = $args[0];
                parent::open_connection();
                $query = "select transporter_id, transporter_photo, transporter_first_name, transporter_last_name, transporter_phone, transporter_email, transporter_address from Persons_Transporter where transporter_id = ?";
                $command = parent::$connection->prepare($query);
                $command->bind_param('s', $id);
                $command->execute();
                $command->bind_result($this->id, $this->photo, $this->first_name, $this->last_name, $this->phone, $this->email, $this->address);
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
            $query = "select child_id from Persons_Child where child_transporter_id = ?";
            $command = parent::$connection->prepare($query);
            $command->bind_param('s',$this->id);
            $command->execute();
            $command->bind_result($id);
            mysqli_stmt_close($command);
            parent::close_connection();
            return $id;
        }
        
 
    }