<?php
	//trigger user logout
	if (isset($_SERVER['QUERY_STRING']) && ($_SERVER['QUERY_STRING']=="logout")){
		session_start();
		logout();
		}
	
	
	//logout function definition
	function logout(){
		$_SESSION['userlogin'] = '';
		$_SESSION['passwordchange'] = '';
		$_SESSION['privilege'] = '';
		header("Refresh:0; URL=http://".$_SERVER['HTTP_HOST']."/acalan");
		}
			
	//login function definition
	function login(){
		require_once('../connection/connect.php');
		require_once('injection.php');
		
		
		if(isset($_POST['Login'])){
			$username = Injection($_POST['username']);
			$password = $_POST['password'];
			$agent = $_SERVER['HTTP_USER_AGENT'];
			$address = $_SERVER['REMOTE_ADDR'];

			//check if credentials are correct
			$get_user = mysqli_query($connect, "SELECT * FROM login WHERE username='$username' AND password=MD5('$password')");
			if(mysqli_num_rows($get_user)<1){
				$msg = urlencode("Wrong username and/or password");
				@header("Location:http://".$_SERVER['HTTP_HOST']."/acalan?error=$msg");
				exit;
				}
			
			//get user details
			$user_detail = mysqli_fetch_array($get_user, MYSQLI_BOTH);
			
			//check if user is blocked
			if($user_detail['privilegeid'] == "3"){
				$msg = urlencode("Sorry, your account is Blocked");
				@header("Location:http://".$_SERVER['HTTP_HOST']."/acalan?error=$msg");
				exit;
				}
			
			//check if user has logged before
			$active = mysqli_query($connect, "SELECT * FROM loginlog WHERE username='$username'");
			$rows = mysqli_num_rows($active);
			
			session_start();
			$_SESSION['userlogin']= $user_detail['username'];
			$_SESSION['privilege']= $user_detail['privilegeid'];

			if($rows == '0'){
				$_SESSION['passwordchange'] = 'Yes';
				@header("Location:http://".$_SERVER['HTTP_HOST']."/acalan/corpus.php");
				}
			else{
				//capture user login information
				$time = date('Y-m-d H:i:s');
				mysqli_query($connect, "INSERT INTO loginlog VALUES('$time','$user_detail[username]','$address','$agent')");
				mysqli_query($connect, "UPDATE login SET lastlogin='$time' WHERE username='$user_detail[username]'");
				$_SESSION['passwordchange'] = 'No';
				@header("Location:http://".$_SERVER['HTTP_HOST']."/acalan/corpus.php?uploadCorpus=1");
				}
			exit;
			}
		}
	
	//trigger user logout
	login();
	
?>
