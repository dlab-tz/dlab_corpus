<?php

	function Injection($value){
		
		/******************************
		 * HANDLING SERVER CONNECTIONS
		 *****************************/
		//credentials
		$host_name = "localhost";
		$db_name = "acalan_corpus_db";
		$username = "root";
		$password = "Peter7387";
		
		//server connection
		//$connect = mysql_connect($host_name, $username, $password);
		$connect = mysqli_connect($host_name, $username, $password);
		if(!$connect){
			echo "<div style='text-aling:center; color:red;'>Server not available! Try again later.</div>";
			exit;
			}
		
		//select database to connect to
		//$db_select = mysql_select_db($db_name, $connect);
		$db_select = mysqli_select_db($connect, $db_name);
		if(!$db_select){
			echo "<div style='text-aling:center; color:red;'>Database connection failed! Try again later.</div>";
			exit;
			}
		
		
		if(!is_numeric($value)){
			$value = mysqli_real_escape_string($connect, $value);
			}
		
		if(get_magic_quotes_gpc()){
			$value = stripslashes($value);
			}
		
		return $value;
		}
		
	
	//password encryption function
	function chasKey($value){
		$val = 'RWIGE_'.base64_decode(hash('ripemd160',$value));		
		return $val;
		}
?>
