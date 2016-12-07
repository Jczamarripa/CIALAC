<?php
  function generate_token()
  {
    //token_name
    $token='';
	$today=date_create();
    //if  arguments reveived, generate token with date only
    if(func_num_args()==0)
    {
      //generate token
      $token=sha1(date_format($today,'Ymd'));
    }
    //if 1 arguments received , generate token with user and date
    if(func_num_args()==1)
    {
      //get user id
      $args=func_get_args();
      $email=$args[0];
      //generate token
      $token=sha1($email.(date_format($today,'Ymd')));
    }
    //return token
    return $token;
  }
?>
