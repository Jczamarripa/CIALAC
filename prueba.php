<?php
require_once('classes/person.php');
require_once('classes/connection.php');


try{
    $p = new Person("hogi@gmail.com");

    echo $p->get_first_name();
}
catch (RecordNotFoundException $ex){
    echo $ex->get_message();
}
?>
