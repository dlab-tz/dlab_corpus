<?php
	/******************************
	 * HANDLING SERVER CONNECTIONS
	 *****************************/
	//credentials
	$host_name = "";
	$db_name = "";
	$username = "";
	$password = "";
	
	//server connection
	$connect = mysqli_connect($host_name, $username, $password);
	if(!$connect){
		echo "<div style='text-aling:center; color:red;'>Server not available! Try again later.</div>";
		exit;
		}
	
	//select database to connect to
	$db_select = mysqli_select_db($connect, $db_name);
	if(!$db_select){
		echo "<div style='text-aling:center; color:red;'>Database connection failed! Try again later.</div>";
		exit;
		}
?>
