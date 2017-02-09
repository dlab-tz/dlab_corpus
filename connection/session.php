<?php
	/************************
	 * CONTROL USER SESSIONS
	 ***********************/
	
	@session_start();
	if(isset($_SESSION['userlogin'])){
		if($_SESSION['userlogin']==''){
			@header("Refresh:1; URL=http://".$_SERVER['HTTP_HOST']."/acalan");
			echo "Please wait we are redirection you!<br/>";
			echo "If your browser doesn't support this, please, <a href='http://".$_SERVER['HTTP_HOST']."/acalan'>Click here</a>";
			die();
			}
		
		global $userLogin, $userPrivilege, $userPassword;
		$userLogin = $_SESSION['userlogin'];
		$userPrivilege = $_SESSION['privilege'];
		$userPassword = $_SESSION['passwordchange'];
		}
	else{
		@header("Refresh:1; URL=http://".$_SERVER['HTTP_HOST']."/acalan");
		echo "Please wait we are redirecting you!<br/>";
		echo "If your browser doesn't support this, please, <a href='http://".$_SERVER['HTTP_HOST']."/acalan'>Click here</a>";
		die();
		}
?>
