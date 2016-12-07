<?php
	class Connection
	{
		//attributes
		private static $server = '127.0.0.1';
		private static $database = 'cialac';
		private static $user = 'root';
		private static $password = ''; 

		//connection to DBMS
		protected static $connection;

		//open connection
		protected static function open_connection()
		{
			//initialize connection
			self::$connection = new mysqli(self::$server, self::$user, self::$password, self::$database);
			//error in connection
			if (self::$connection->connect_errno)
			{
				echo 'Cannot connect to MySQL server : '.self::$connection->connect_error;
				die;
			}
		}

		//close connection
		protected static function close_connection()
		{
			self::$connection->close();
		}
	}
?>
