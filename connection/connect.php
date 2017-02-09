<?php
	/******************************
	 * HANDLING SERVER CONNECTIONS
	 *****************************/
	//credentials
	$host_name = "localhost";
	$db_name = "acalan_corpus_db";
	$username = "root";
	$password = "Peter7387";
	
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
