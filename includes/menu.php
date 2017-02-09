<?php

	//function to display left menu
	function displayLeftMenu(){
		$menu = '';
		$pageurl = $_SERVER['HTTP_HOST'];
		
		//check if user has logged in and has updated password
		if(isset($_SESSION['userlogin']) && isset($_SESSION['passwordchange']) && $_SESSION['passwordchange']!='Yes'){
			$menu .=<<<EOD
					<table border="0" cellpadding="2" cellspacing="0">
						<tr>
							<td>
								<img src="processform.php?id=$_SESSION[userlogin]" height="120" width="100">
								<BR/>
								<a href="$_SERVER[PHP_SELF]?uploadPhoto=1">Change Photo</a>
							</td>
						</tr>
						<tr><td><a href="$_SERVER[PHP_SELF]?changePassword=1">Change Password</a></td></tr>
						<tr>
							<td>
								<a href='includes/login.php?logout'>
									<div id='logout'>
										Logout $_SESSION[userlogin]
									</div>
								</a>
							</td>
						</tr>
					</table>
EOD;
			}
		else{
			$menu .=<<<EOD
					<table border="0" cellpadding="2" cellspacing="0">
						<tr>
							<td>
								<a href='includes/login.php?logout'>
									<div id='logout'>
										Logout $_SESSION[userlogin]
									</div>
								</a>
							</td>
						</tr>
					</table>
EOD;
			}
		echo $menu;
		}
	
	//function to display right menu
	function displayRightMenu(){
		$menu = '';
		$pageurl = $_SERVER['PHP_SELF'];
		
		//check if user has logged in
		if(isset($_SESSION['userlogin']) && isset($_SESSION['passwordchange']) && $_SESSION['passwordchange']!='Yes'){
			//check if user is an administrator
			if($_SESSION['privilege']==1){
				$menu .=<<<EOD
						<br/>
							<ul class="nav nav-pills nav-justified">
								<li><a href="$_SERVER[PHP_SELF]?manageUser=1">Manage Users</a></li>
								<li><a href="$_SERVER[PHP_SELF]?uploadCategory=1">Add Category</a></li>
								<li><a href="$_SERVER[PHP_SELF]?uploadCorpus=1">Upload Content</a></li>
								<li><a href="$_SERVER[PHP_SELF]?downloadCorpus=1">Download Content</a></li>
								<li><a href="$_SERVER[PHP_SELF]?searchContent=1">Search Content</a></li>
							</ul>
EOD;
				}
			else{
				$menu .=<<<EOD
						<br/>
						<table class="table">
							<tr>
								<td><a href="$_SERVER[PHP_SELF]?uploadCorpus=1">Upload Content</a></td>
								<td><a href="$_SERVER[PHP_SELF]?downloadCorpus=1">Download Content</a></td>
								<td><a href="$_SERVER[PHP_SELF]?searchContent=1">Search Content</a></td>
							</tr>
						</table>
EOD;
				}
			}
		if(!isset($_SESSION['userlogin']) && @$_SESSION['userlogin']==''){
			$menu .=<<<EOD
						<table cellpadding="4" cellspacing="0" border="0">
							<tr><td><a href="$_SERVER[PHP_SELF]?searchContent=1">Search Content</a></td></tr>
							<tr><td><a href="$_SERVER[PHP_SELF]">Home</a></td></tr>
						</table>
EOD;
			}
		echo $menu.'<br/>';
		}

?>
