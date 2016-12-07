<?php
    //use files
    require_once('connection.php');
    require_once('exceptions.php');

    class MedicineDescription extends Connection{
        private $medicine;
        private $child;
        private $dosification;
        private $periodicity;
        
        public function get_medicine(){ return $this->medicine; }
        public function set_medicine($value){ $this->medicine = $value; }
        public function get_child(){ return $this->child; }
        public function set_child($value){ $this->child = $value; }  
        public function get_dosification(){ return $this->dosification; }
        public function set_dosification($value){ $this->dosification = $value; }
        public function get_periodicity(){ return $this->periodicity; }
        public function set_periodicity($value){ $this->periodicity = $value; }
        
        function __construct(){
            if(func_num_args() == 0){
                $this->medicine = '';
                $this->child = '';
                $this->dosification = '';
                $this->peridiocity = "";
            }
            if(func_num_args() == 2){
                $args = func_get_args();
                $medicine = $args[0];
                $child = $args[1];
                parent::open_connection();
                $query = "select medicine_id, child_id, dosification, peroidicity from Medicines_Medicine_Child where medicine_id = ? and child_id = ?";
                $command = parent::$connection->prepare($query);
                $command->bind_param('ss', $medicine, $child);
                $command->execute();
                $command->bind_result($this->medicine, $this->child, $this->dosification, $this->periodicity);
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
            $query = "select child_id from Medicines_Medicine_Child where medicine_id = ?";
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