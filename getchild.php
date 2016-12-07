<?php
    //allow access to API
	header('Access-Control-Allow-Origin: *');
	header('Access-Control-Allow-Headers: email, token');
	//use files
	require_once('classes/child.php');
	require_once('classes/exceptions.php');
	require_once('classes/catalogs.php');
	require_once('classes/generatetoken.php');
    require_once('classes/transporter.php');
    require_once('classes/medicine.php');
    require_once('classes/medicineDescription.php');
    require_once('classes/schedule.php');
    require_once('classes/day.php');

	//get headers
	$headers = getallheaders();
	//validate parameter and headers 
	
	
	if( isset($_GET['id']) & isset($headers['email']) & isset($headers['token']))
	{
		//validate
		if ($headers['token'] == generate_token($headers['email']))
		{
            try{
                //start json 
                $c = new Child($_GET['id']);
                $json = '{ "status" : 0, "childs" : [';
                    $json .= '{ "id" : "'.$c->get_id().'",
                               "photo" : "'.$c->get_photo().'",
                               "firstname" : "'.$c->get_first_name().'",
                               "lastname" : "'.$c->get_last_name().'",
                               "dateofbirth" : "'.$c->get_date_of_birth().'",
                               "skills" : "'.$c->get_skills().'",
                               "status" : "'.$c->get_status().'",';
                                $id = $c->get_tutor();
                                $tutor = new Person($id);
                                $json.='
                               "tutor" : {
                                        "id" : "'.$tutor->get_id().'",
                                        "role" : "'.$tutor->get_role().'",
                                        "photo" : "'.$tutor->get_photo().'",
                                        "firstname" : "'.$tutor->get_first_name().'",
                                        "lastname" : "'.$tutor->get_last_name().'",
                                        "date_of_birth" : "'.$tutor->get_date_of_birth().'",
                                        "email" : "'.$tutor->get_email().'",
                                        "phone" : "'.$tutor->get_phone().'",
                                        "address" : "'.$tutor->get_address().'"

                               },';
                                $id_transporter = $c->get_transporter();
                                $transporter = new Transporter($id_transporter);
                                 $json.='
                               "transporter" : {
                                        "id" : "'.$transporter->get_id().'",
                                        "photo" : "'.$transporter->get_photo().'",
                                        "firstname" : "'.$transporter->get_first_name().'",
                                        "lastname" : "'.$transporter->get_last_name().'",
                                        "email" : "'.$transporter->get_email().'",
                                        "phone" : "'.$transporter->get_phone().'",
                                        "address" : "'.$transporter->get_address().'"

                               },
                               "medicines" :[';
                                $medicines = $c->get_medicines();  
                                $second = true;
                                foreach($medicines as $m){
                                    if($second)$second = false; else $json .= ','; 
                                    $json.='{
                                        "id" : '.$m->get_id().',
                                        "name" : "'.$m->get_name().'",
                                        "description" : "'.$m->get_description().'",
                                        "dosification" : "'.$m->get_dosification().'",
                                        "periodicity" : "'.$m->get_periodicity().'"
                                    }';
                                }
                    
                    
                    
                    
                    $json.='],';
                            
                    $json.='
                                "therapies" : [';
                                $therapies = $c->get_therapies();
                                $third = true;
                                foreach($therapies as $th){
                                    if($third)$third = false; else $json .= ','; 
                                    $json.='{
                                        "id" : '.$th->get_id().',
                                        "name" : "'.$th->get_name().'",
                                        "description" : "'.$th->get_description().'"
                                    }';
                                }
                    
                    $json.='],
                    
                    "schedule" : [';
                            $schedule = $c->get_schedule();
                            $four = true;
                            foreach($schedule as $sh){
                                if($four)$four = false; else $json .= ','; 
                                 $json.='{
                                        "days" : [';
                                        $days = $sh->get_days();
                                        $five = true;
                                        foreach($days as $day){
                                          if($five)$five = false; else $json .= ','; 
                                            $json.='{
                                                "id" : '.$day->get_id().',
                                                "name" : "'.$day->get_name().'",
                                                "activities" : [';
                                                $activities = $sh->get_activities();
                                                $six = true;
                                                foreach($activities as $activity){
                                                    if($six)$six = false; else $json .= ',';  
                                                    $json.='{
                                                    "id" : "'.$activity->get_id().'",
                                                    "name" : "'.$activity->get_name().'",
                                                    "beginhour" : "'.$sh->get_begin_hour().'",
                                                    "endhour" : "'.$sh->get_begin_hour().'"
                                                    }';
                                                }
                                            
                                            $json.=']';                                            
                                            $json.='
                                            }';  
                                        }
                                $json.=']';
                                $json.='
                                    }';
                            }
                    
                    $json.=']';
                    
                    $json.='}';
                
                $json .=  '] }';
                echo $json;
            }
            catch(RecordNotFoundException $ex){
                echo '{"status" : 3, "errorMessage" : "'.$ex->get_message().'"}';
            }
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