 <?php
     //allow access to API
 	header('Access-Control-Allow-Origin: *');
 	header('Access-Control-Allow-Headers: email, token');
 	//use files
 	require_once('classes/person.php');
 	require_once('classes/exceptions.php');
 	require_once('classes/catalogs.php');
 	require_once('classes/generatetoken.php');
 	//get headers
 	$headers = getallheaders();
 	//validate parameter and headers 
 	
 	
 	if( isset($headers['email']) & isset($headers['token']))
 	{
 		//validate
 		if ($headers['token'] == generate_token($headers['email']))
 		{
          try{
                $json = '{ "status" : 0, "tutors" : [';
 			//read makes 
 			$first = true ;
 			foreach (Catalogs::get_tutors() as $t)
 			{
 				if($first)$first = false; else $json .= ','; 
 				$json .= '{ "id" : "'.$t->get_id().'",
                            "photo" : "'.$t->get_photo().'",
                            "firstname" : "'.$t->get_first_name().'",
                            "lastname" : "'.$t->get_last_name().'",
                            "dateofbirth" : "'.$t->get_date_of_birth().'",
                            "email" : "'.$t->get_email().'",
                            "phone" : "'.$t->get_phone().'",
                            "address" : "'.$t->get_address().'",
                            "childs" : [';
                             $childs = $t->get_childs();
                             $second = true;
                             foreach($childs as $c)
                             {
                                 if($second) $second = false; else $json .= ',';
                                 $json .= '{	"id" : "'.$c->get_id().'",
                                             "photo" : "'.$c->get_photo().'",
                                             "firstname" : "'.$c->get_first_name().'",
                                             "lastname" : "'.$c->get_last_name().'",
                                             "dateofbirth" : "'.$c->get_date_of_birth().'",
                                             "skills" : "'.$c->get_skills().'",
                                            "status" : "'.$c->get_status().'",
                                            "status" : "'.$c->get_status().'"
                                             }';
                             }
                             $json.=']
 						  }';
 			}
 			$json .=  '] }';
 			echo $json;
                
            }catch(RecordNotFoundException $ex){
                echo '{"status" : 3, "errorMessage" : "'.$ex->get_message().'"}';
            }
			//start json 
			
 		}
 		else
 		{
 			echo '{"status" : 2, "errorMessage" : "Invalid Token"}';
 		
 		}
 	}
 	else
 	{
 	 echo '{"status" : 1, "errorMessage" : "Invalid Parameters"}';
 	}
 	
 ?>