<?php
  //allow access to API
  header('Access-Control-Allow-Origin:*');
  header('Access-Control-Allow-Headers:email,password');
  //use files
  require_once('classes/person.php');
  require_once('classes/generatetoken.php');
  //read headers
  $headers=getallheaders();
  //check if headers were received
  if(isset($headers['email'])& isset($headers['password']))
  {
    try {
        //create object
        $p = new Person($headers['email'], $headers['password']);
        //display j
        echo'{"status":0,
          "id":"'.$p->get_id().'",
          "name":"'.$p->get_first_name().'",
          "email":"'.$headers['email'].'",
          "token":"'.generate_token($p->get_email()).'"
        }';
    } catch (RecordNotFoundException $ex) {
      echo'{"status": "1","errorMessage":"'.$ex->get_message().'"}';
    }
  }
  else {
    echo'{"status":2,"errorMessage":"invalidHeaders"}';
  }
?>
