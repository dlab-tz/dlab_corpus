<?php
	
	global $pageurl;	
	
	/***************************************************************
	 * FUNCTION TO DISPLAY CONTENTS OF THE CENTRAL PART OF THE PAGE
	 **************************************************************/
	function displayContent(){
		include('connection/connect.php');
		include('includes/injection.php');
		
		$pageContent = '';		
		$pageurl = $_SERVER['PHP_SELF'];
		
		/************************
		 *LOGIN FORM DISPLAY
		 *************************/
		if(!isset($_SESSION['userlogin']) || @$_SESSION['userlogin']==''){
			
			//Form to download corpus content
			if(isset($_GET['downloadCorpus'])){
				$corp_category = mysqli_query($connect, "SELECT categoryid, title FROM category");
				
				$option = '';
				while(list($id,$category) = @mysqli_fetch_array($corp_category, MYSQLI_BOTH)){
					$option .= '<option value="'.$id.'">'.$category.'</option>';
					}
				$pageContent .=<<<EOD
							
										<h3>Download Corpus of Your Choice</h3>
								
										<form action="downloadSearch.php?Download" name="search" method="post" id="search">
								 
									

									<div class="form-group">
										<label for="category" class="control-label col-sm-3">Category:</label>
											<div class="col-sm-6">
												<select name="category" required>
													<option value="ALL">All Categories</option>
													$option
												</select>
											</div>
											
										</div>
									<div class="form-group">
										<label for="Download" class="col-sm-3 control-label">Download</label>
										<div class="col-sm-6">
											<input type="submit" name="Download" class="form-control" id="Download" value="Download" />
										</div>
									</div>
								</form>
EOD;
					}
			
			//Form to search corpus content
			elseif(isset($_GET['searchContent'])){
				$pageContent .=<<<EOD
							<form action='processform.php' method='POST'>
								<div class='form-group'>								
									<th>
										Word Search:
									</th>
									<td>
										<input type="text" name="searchKey" id="searchKey"/>
									</td>
								</form>
							</form>
							
		
EOD;
					}	
			
			//LOGIN FORM
			else{
				$pageContent .=<<<EOD
							<table class="table table-responsive" id="loginForm">
								<tr>								
									<th>
										<form action="includes/login.php" name="login" method="post" id="login">
										Username: 
									</th>
									<td>
										<input type="text" name="username" id="username" placeholder="acalan.corpus" required />
									</td>
								</tr>
								<tr>
									<th>
										Password:
									</th>
									<td>
										<input type="password" name="password" id="password" placeholder="Password" required />
									</td>
								</tr>
								<tr>
									<td colspan="2" align="right">
										<input type="submit" name="Login" id="Login" value="Login" />
										</form>
										<br/><br/>
										
EOD;
					if(isset($_GET['error'])){
						$pageContent .=<<<EOD
										<span style="color:red">$_GET[error]</span>
EOD;
						}
					$pageContent .=<<<EOD
									</td>
								</tr>
							</table>
EOD;
				}
			
			echo $pageContent;
			}
			
		else{
			/***********************************************************
			 * TRIGGERS TO DISPLAY CONTENTS ACCORDING TO USER SELECTIONS
			 ***********************************************************/
			
			//IF IT IS FIRST LOGIN AND PASSWORD IS NOT CHANGED
			if($_SESSION['passwordchange']=='Yes'){
				if(!isset($_GET['uploadPhoto'])){
					$pageContent .=<<<EOD
						<form action="corpus.php" name="changePassword" method="post" class="form-horizontal" id="changePassword">
							<div class="form-group">
								<label class="control-label col-sm-2" for="password">Password:
									(minimum 6 characters)
								</label>
								<div class="col-sm-6">
									<input type="password" name="password" id="password" placeholder="Password" required />
								</div>

							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="password2">Re-type Password: </label>
								<div class="col-sm-6">
									<input type="password" name="password2" id="password2" placeholder="Password" required />
								</div>
							</div>
						</form>

						<form action="corpus.php" name="changePassword" method="post" class="form-horizontal" id="changePassword">
							<div class="form-group">
								<label class="control-label col-sm-2" for="password">Password: 
								(Minimum 6 characters)
								</label>
								<div class="col-sm-6">
									<input type="password" name="password" id="password" placeholder="Password" required />
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-sm-2" for="password2">Re-type Password: </label>
								<div class="col-sm-6">
									<input type="password" name="password2" id="password2" placeholder="Password" required />
								</div>
							</div>

							<div class="form-group">
								<label class="control-label col-sm-2" for="Change_Password">Change Password</label>
								<div class="col-sm-6">
									<input type="submit" name="Change_Password" id="Change_Password" />
								</div>
							</div>
					</form>														
EOD;
					}
				else{
					$pageContent .=<<<EOD
								<table class="table table-responsive" id="loginForm">
									<tr>
										<td>
										<form action="corpus.php" method="POST" enctype="multipart/form-data">
											<input type="hidden" name="MAX_FILE_SIZE" value="5300000"/>
											<input type="file" name="fileUp" id="fileUp" size="20"/>
										</td>
										<td>
											<input type="submit" name="Upload" value="Upload" />
										</form>
										<br/><br/>
											<span style="color:red">$_GET[error]</span>								
										</td>
									</tr>
								</table>	
EOD;
					}
				
				echo $pageContent;
				if(!isset($_GET['uploadPhoto'])){
					changePassword();
					}
				}
				
			else{
				//IF PASSWORD AND PHOTO ARE UPLOADED
				
				//Form to change password
				if(isset($_GET['changePassword'])){
					$pageContent .=<<<EOD
						<form action="corpus.php?changePassword=1&Change_Password" method="post" name="changePassword" class="form-horizontal" id="changePassword">
							<div class="form-group">
							 	<label class="control-label col-sm-2" for="password">Password:
								 (Minimum 6 characters)
								 </label>
								 <div class="col-sm-6">
								 	<input type="password" name="password" id="password" placeholder="Password" required />
								 </div>
							</div>

							<div class="form-group">
								<label for="password2" class="control-label col-sm-2">Re-type password: </label>
								<div class="col-sm-6">
									<input type="password" name="password2" id="password2" placeholder="Password" required />
								</div>
							</div>

							<div class="form-group">
								<label for="Change_Password" class="control-label col-sm-2"></label>
								<div class="col-sm-6">
									<button class="btn" type="submit" name="Change_Password" id="Change_Password">Change Password</button>
								</div>
							</div>
						
						</form>	
EOD;
					}
				
				//Form to upload photo
				elseif(isset($_GET['uploadPhoto'])){
					$pageContent .=<<<EOD
								<table class="table table-responsive" id="loginForm">
									<tr>
										<td>
										<form action="corpus.php" method="POST" enctype="multipart/form-data">
											<input type="hidden" name="MAX_FILE_SIZE" value="5300000"/>
											<input type="file" name="fileUp" id="fileUp" size="20"/>
										</td>
										<td>
											<input type="submit" name="Upload" value="Upload" />
										</form>
										</td>
										<span style="color:red">$_GET[error]</span><br/>
									</tr>
								</table>
EOD;
					}
				
				//User management
				elseif(isset($_GET['manageUser'])){
					//get privileges
					$get_position = mysqli_query($connect, "SELECT privilegeid, privilege FROM privilege");
					$position = '';
					while(list($id,$pos) = mysqli_fetch_array($get_position, MYSQLI_BOTH)){
						$position .= '<option value="'.$id.'">'.$pos.'</option>';
						}
					
					/*************************
					 * USER ACCOUNT EDIT MODE
					 ************************/
					if(isset($_GET['Edit'])){
						$id = $_GET['Edit'];
						
						//get user details
						$get_users = mysqli_query($connect, "SELECT u.fullname, u.gender, u.mobileno, u.email, l.username, p.privilege, p.privilegeid 
												FROM users u, login l, privilege p WHERE l.userid=u.userid 
												AND p.privilegeid=l.privilegeid AND u.userid='$id'");
						
						list($name, $sex, $mobile, $mail, $uname, $priv, $privid) = mysqli_fetch_array($get_users, MYSQLI_BOTH);
						$names = explode(" ",$name);
						$pageContent .=<<<EOD
							<div class="container">
								<h1>User Information</h1>
								<hr>

								<div class="row">
								<!-- left column -->
								<div class="col-sm-2">
									<div class="text-center">
									<img src="//placehold.it/100" class="avatar img-circle" alt="avatar">
									<h6>Upload a different photo...</h6>
									
									<input class="form-control" type="file">
									</div>
								</div>
								
								<!-- edit form column -->
								<div class="col-sm-6 personal-info">
									<div class="alert alert-info alert-dismissable">
										<a class="panel-close close" data-dismiss="alert">×</a> 
										<i class="fa fa-coffee"></i>
										This is a <strong>page</strong> showing the information about you.
									</div>
									<h3>Personal info</h3>
									<form action="processform.php?Edit_User" method="POST" name="userEdit" class="form-horizontal" role="form" id="userEdit">
										<input type="hidden" name="userid" id="userid" value="$id">
									<div class="form-group">
										<label for="uname" class="col-sm-3 control-label">Username:</label>
										<div class="col-sm-6">
											<input type="text" name="uname" id="uname" value="$uname" class="form-control" readonly style="background:#cccccc">
										</div>
									</div>
									<div class="form-group">
										<label for="fname" class="col-sm-3 control-label">First name:</label>
										<div class="col-sm-6">
											<input type="text" name="fname" id="fname" class="form-control" value="$names[0]" required>
										</div>
									</div>
									<div class="form-group">
										<label for="lname" class="col-sm-3 control-label">Last name:</label>
										<div class="col-sm-6">
											<input type="text" name="lname" id="lname" class="form-control" value="$names[1]" required>
										</div>
									</div>
									<div class="form-group">
									 <label for="gender" class="control-label col-sm-3">Gender:</label>
									 	<div class="col-sm-6">
										 	<select name="gender" class="form-control" id="gender" required >
												<option value="$sex">$sex</option>
												<option value="Female">Female</option>
												<option value="Male">Male</option>
											</select>
										 </div>
									</div>

									<div class="form-group">
										<label class="col-sm-3 control-label">Institution:</label>
										<div class="col-sm-6">
										<input class="form-control" value="" type="text">
										</div>
									</div>
									<div class="form-group">
										<label for="email" class="col-sm-3 control-label">Email:</label>
										<div class="col-sm-6">
											<input type="text" name="email" class="form-control" id="email" value="$mail">
										</div>
									</div>
									<div class="form-group">
										<label for="position" class="col-sm-3 control-label">Position:</label>
										<div class="col-sm-6">
											<select name="position" class="form-control" id="position" required >
												<option value="$privid">$priv</option>
												$position
											</select>
										</div>
									</div>
									
									<div class="form-group">
										<label for="mobile" class="col-sm-3 control-label">Mobile No:</label>
										<div class="col-sm-6">
											<input type="text" name="mobile" class="form-control" id="mobile" value="$mobile" >
										</div>
									</div>
									<div class="form-group">
										<label for="Edit_User" class="col-sm-3 control-label"></label>
										<div class="col-sm-6">
											<input class="btn btn-default" type="submit" name="Edit_User" value="Save Changes" type="submit">									
											<span></span>
											<input class="btn btn-default" value="Cancel" type="reset">
										</div>
									</div>
									</form>
									$_GET[error]<br/>
								</div>
							</div>
							</div>
							<hr>
EOD;
						}
						
					else{
						/**************************************
						 * NON-EDIT USER MANAGEMENT ACTIVITIES
						 *************************************/
						//get registered users
						$get_users = mysqli_query($connect, "SELECT u.userid, u.fullname, u.gender, u.mobileno, l.username, p.privilege 
												FROM users u, login l, privilege p WHERE l.userid=u.userid 
												AND p.privilegeid=l.privilegeid ORDER BY u.fullname ASC");
						$report = '';
						if(mysqli_num_rows($get_users)>0){
							$report .= "<table class='table table-bordered table-responsive table-hover' id='registeredUser'>
										<tr>
											<th>SNo</th>
											<th><center>FULLNAME</center></th>
											<th><center>USERNAME</center></th>
											<th><center>GENDER</center></th>
											<th><center>MOBILE</center></th>
											<th><center>POSITION</center></th>
											<th><center>PHOTO</center></th>										
										</tr>";
							
							$i=1;
							//get registered users
							while(list($id, $name, $sex, $mobile, $uname, $priv) = mysqli_fetch_array($get_users, MYSQLI_BOTH)){
								//check if user has a photo
								$check_log = mysqli_query($connect, "SELECT browser FROM loginlog WHERE username='$uname'");
								$browz = mysqli_num_rows($check_log);
								
								$img = ($browz==0)? '<img src="images/default.jpg" height="80" width="80">':'<img src="processform.php?id='.$uname.'" height="80" width="80">';
								
								$report .= "<tr>
												<td><a href=corpus.php?manageUser=1&Edit=$id><b><u>$i</u></b></a></td>
												<td>$name</td>
												<td>$uname</td>
												<td>$sex</td>
												<td>$mobile</td>
												<td>$priv</td>
												<td>$img</td>
											</tr>";
								$i++;
								}
							}
						
						$report .= "</table>";
							
						$pageContent .=<<<EOD
								
									<ul class="list-inline">
										<li>Add User: <input type="radio" name="management" value="1" checked onclick="manageUser(this.value)" /></li>
										<li>Search User: <input type="radio" name="management" value="2" onclick="manageUser(this.value)" /></li>
										<li>Delete user: <input type="radio" name="management" value="3" onclick="manageUser(this.value)" /></li>
									</ul>
								
								<br/>
								<div id="addUser">
								
										<h3> USER REGISTRATION</h3>
										<form action="processform.php?Register_User" method="POST" class="form-horizontal" name="userRegister" id="userRegister">	
											<div class="form-group">
												<label class="control-label col-sm-2" for="fname">First Name: </label>
												<div class="col-sm-6">
													<input type="text" name="fname" id="fname" class="form-control" required />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-sm-2" for="lname">Surname:</label>
												<div class="col-sm-6">
													<input type="text" name="lname" id="lname" class="form-control" required />
												</div>
											</div>										
											<div class="form-group">
												<label class="control-label col-sm-2" for="gender">Gender: </label>
												<div class="col-sm-6">
													<select name="gender" id="gender" class="form-control" required >
														<option value="Female">Female</option>
														<option value="Male">Male</option>
													</select>
												</div>
									
											</div>

											<div class="form-group">
												<label class="control-label col-sm-2" for="position">Position: </label>
												<div class="col-sm-6">
													<select name="position" id="position" class="form-control" required >
														$position
													</select>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-sm-2" for="mobile">Mobile No: </label>
												<div class="col-sm-6">
													<input type="text" name="mobile" class="form-control" id="mobile" />
												</div>
											</div>
											<div class="form-group">
												<label class="control-label col-sm-2" for="email">Email: </label>
												<div class="col-sm-6">
													<input type="email" name="email" class="form-control" id="email" />
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-2 col-sm-6">
													<button type="submit" name="Register_User" class="btn btn-default">Register User</button>
												</div>
											</div>
									
										</form>										
										
										$_GET[error]<br/>
								</div>
								
								<div id="searchUser">
									<div class="form-group">
										<label for="searchName" class="control-label col-sm-3">Name or Username:</label>
										<div class="col-sm-6">
											<input type="text" name="searchName" id="searchName" class="form-control" onkeyup="searchUser(this.value)" />
										</div>
									</div>
									<div></div>
									<div id="results">$report</div>
								</div>	
								
								<div id="deleteUser">
								<div class="form-group">
									<label for="deleteName" class="control-label col-sm-3">User Official Name:</label>
									<div class="col-sm-6">
										<input type="text" name="deleteName" id="deleteName" onkeyup="deleteUser(this.value)" class="form-control">
									</div>
								</div>
								
									<div id="deleteResults"></div>
								</div>	
EOD;
						}
					}
				
				//Form to register corpus category
				elseif(isset($_GET['uploadCategory'])){
					$selection = '<option value="">Parent Category</option>';
					$qcategories = mysqli_query($connect, "SELECT categoryid, title FROM category ORDER BY title ASC");
					while(list($id, $cat) = mysqli_fetch_array($qcategories, MYSQLI_BOTH)){
							$selection .= '<option value="'.$id.'">'.$cat.'</option>';
							}
					
					//get available categories
					$categories = '';
					$qcategories = mysqli_query($connect, "SELECT tit.title AS title, tit.parentid AS parent FROM category tit WHERE parentid IS NULL UNION SELECT chd.title AS title, (SELECT title FROM category WHERE categoryid =(SELECT parentid FROM category WHERE title=chd.title)) AS parent FROM category chd WHERE chd.parentid IS NOT NULL ORDER BY title ASC");
					
					if(@mysqli_num_rows($qcategories)>0){
						$categories .= "<table class='table table-responsive table-bordered' id='categoryTable'>
										<tr>
											<th colspan='3' style='text-align:center'>AVAILABLE CATEGORIES</th>											
										</tr>
										<tr>
											<th align='center'>SNo</th>
											<th align='center'>CATEGORY</th>
											<th align='center'>PARENT CATEGORY</th>
										</tr>";
						$sn = 1;
						
						while(list($cat,$parent) = mysqli_fetch_array($qcategories, MYSQLI_BOTH)){
							$categories .= "<tr>
												<td>$sn</td>
												<td>$cat</td>
												<td>$parent</td>
											</tr>";
							$sn++;
							}
						$categories .= "</table>";
						}
						
					$pageContent .=<<<EOD
					<form class="form-horizontal" action="processform.php?Add_Category" method="POST" name="corpusCategory">
								<h4>Upload Corpus Category</h4>
							<div class="form-group">
								<label class="control-label col-sm-2" for="parentCat">Parent: </label>
								<div class="col-sm-6">
									<select name="parentCat" class="form-control">$selection</select>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label col-sm-2" for="category">Category: </label>
								<div class="col-sm-6">
									<input type="text" name="category" id="category" class="form-control" required />
								</div>
							</div>
							<div class="form-group">
								<div class="col-sm-offset-4">
									<button class="btn btn-default" type="submit" name="Add_Category" id="Add_Category">Add Category</button>
								</div>
							</div>


							$_GET[error]<br/>
							<br/><br/>
							$categories
					</form>
EOD;
					}
					
				//Form to upload corpus contents
				elseif(isset($_GET['uploadCorpus'])){
					$selection = '';
					$qcategories = mysqli_query($connect, "SELECT categoryid, title FROM category ORDER BY title ASC");
					while(list($id, $cat) = mysqli_fetch_array($qcategories, MYSQLI_BOTH)){
							$selection .= '<option value="'.$id.'">'.$cat.'</option>';
							}
							
					$pageContent .=<<<EOD
								<form action="processform.php?Upload_Corpus" method="POST" class="form-horizontal" enctype="multipart/form-data">
									<div class="form-group">
										<label class="control-label col-sm-4" for="category">Select Category: </label>
										<div class="col-sm-6">
											<select name="category" class="form-control" required>$selection</select>
										</div>
									</div>

									<div class="form-group">
										<label for="year" class="control-label col-sm-4">Date:</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" placeholder="Year" name="year">
										</div>
									</div>

									<div class="form-group">
										<label for="pub_info" class="control-label col-sm-4">Publication Information:</label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="pub_info">
										</div>
										
									</div>

									<div class="form-group">
										<label for="title" class="control-label col-sm-4">Title: </label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="title">
										</div>
									</div>

									<div class="form-group">
										<label for="author" class="control-label col-sm-4">Author: </label>
										<div class="col-sm-6">
											<input type="text" class="form-control" name="author">
										</div>
									</div>


									<div class="form-group">
										<label class="control-label col-sm-4" for="fileUploaded">Choose File: </label>
										<div class="col-sm-6">
											<input type="hidden" name="MAX_FILE_SIZE" class="form-control" value="53000000"/>
											<input type="file" name="fileUploaded" id="fileUploaded" class="form-control" size="20"/>
										</div>
									</div>
									
									<div class="form-group">
										<label class="control-label col-sm-4" for="Upload_Corpus"></label>
										<div class="col-sm-4">
											<button class="btn btn-default" type="submit" name="Upload_Corpus">Upload Corpus</button>
										</div>
									</div>
									<span style="color:red">$_GET[error]</span><br/>
								</form>
EOD;
					}
					
				//Form to download corpus content
				elseif(isset($_GET['downloadCorpus'])){
					$corp_category = mysqli_query($connect, "SELECT categoryid, title FROM category");
				
					$option = '';
					while(list($id,$category) = @mysqli_fetch_array($corp_category, MYSQLI_BOTH)){
						$option .= '<option value="'.$id.'">'.$category.'</option>';
						}
					$pageContent .=<<<EOD
								
								
									<h4>Download Corpus of Your Choice</h4>
								
								<form action="processform.php?Download" name="search" method="post" class="form-horizontal" id="search">
								<div class="form-group">
									<label class="control-label col-sm-2" for="category">Category</label>
									<div class="col-sm-6">
										<select name="category" class="form-control" required>
												<option value="ALL">All Categories</option>
												$option
										</select>
									</div>
								</div>
								<div class="form-group">
									<div class="col-sm-offset-2 col-sm-10">
										<button class="btn btn-default" type="submit" name="Download" id="Download">Download</button>
									</div>
								</div>
								</form>
EOD;
					}
				
				//Form to search corpus content
				elseif(isset($_GET['searchContent'])){
					$pageContent .=<<<EOD
					      <form action="searchResults.php" class="form-inline" method='POST'>
						      <div class="form-group">
									
									<div class="col-sm-6">
										<input type="text" name="searchKey" id="searchKey" />
									</div>								
							  </div>
								<input type="hidden" name='search' value='1'>
								<div class="form-group"><input type="hidden" name='search' value='1'><button class="btn btn-default" type='submit'>Search</button></div>
						  </form>
							
							<div id="results"></div>
EOD;
					}
								
				echo $pageContent;
				if(isset($_GET['changePassword'])){					
					changePassword();
					}
				}			
			}
		}
		
		if(isset($_GET['result'])) {
			$result = $_GET['result'];
			var_dump($result);
			exit();
		}
		
		
	/***************************************************************
	 * FUNCTIONS TO PROCESS DIFFERENT FORMS AS TRIGGERED ABOVE
	 **************************************************************/
	
	//FUNCTION TO CHANGE PASSWORD
	function changePassword(){
		include('connection/connect.php');
		
		$pageurl = $_SERVER['PHP_SELF'];
				
		if(isset($_POST['Change_Password'])){
			
			//get values
			$username = $_SESSION['userlogin'];
			$password = Injection($_POST['password']);
			$passwor2 = Injection($_POST['password2']);
			
			if($password<>$passwor2){
				echo "<span style='color:red'>Sorry, passwords do not match</span>";
				}
			elseif(strlen($password)<6){
				echo "<span style='color:red'>Sorry, password is too short</span>";
				}
			else{
				$password = MD5($password);
				mysqli_query($connect, "UPDATE login SET password='$password' WHERE username='$username'");
				
				if(mysqli_error($connect)){
					echo "<span style='color:red'>There was an error, try again</span>";
					}
				else{
					$url = ($_SESSION['passwordchange']=='Yes')? 'corpus.php?uploadPhoto=1':'includes/login.php?logout';					
					echo '<meta http-equiv="refresh" content="0; url='.$url.'" />';
					exit;
					}
				}
			}
		}

	
	//TRIGGER TO UPLOAD PROFILE PICTURE
	if(isset($_POST['Upload'])){
		$pageurl = $_SERVER['HTTP_HOST'];
		$username = $_SESSION['userlogin'];
		$url = ($_SESSION['passwordchange']=='Yes')? $pageurl.'?uploadPhoto=1':$pageurl;
		
		//known file extensions array
		$known_extensions = array('png','jpg','jpeg');

		$max_size = $_POST['MAX_FILE_SIZE'];
				
		$fileName = $_FILES['fileUp']['name'];
		$tmpName  = $_FILES['fileUp']['tmp_name'];
		$fileSize = $_FILES['fileUp']['size'];
		$fileType = $_FILES['fileUp']['type'];


		//check if the file size has zero bytes
		if($fileSize <= 0){
			$error = urlencode("Please select photo to upload first");
			$url = $_SERVER['PHP_SELF']."?uploadPhoto=1&error=$error";
			echo '<meta http-equiv="refresh" content="3; url='.$url.'">';
			exit;
			}

		//check if the file size has exceeded the maximum upload limit size
		if($fileSize > $max_size){
			$error = urlencode("The uploaded file has exceeded the maximum upload size limit");
			$url = $_SERVER['PHP_SELF']."?uploadPhoto=1&error=$error";
			echo '<meta http-equiv="refresh" content="3; url='.$url.'">';
			exit;
			}

		//get file information
		$file_info = pathinfo($_FILES['fileUp']['name']); 
		$ext = strtolower($file_info['extension']);

		//check if the file extension is among the predefined file extensions
		if(!in_array($ext, $known_extensions, true)){
			$error = urlencode("Selected file is not of allowed image type");
			$url = $_SERVER['PHP_SELF']."?uploadPhoto=1&error=$error";
			echo '<meta http-equiv="refresh" content="0; url='.$url.'">';
			exit;
			}

		//read contents of the file
		$fp = fopen($tmpName, 'r');
		$content = fread($fp, filesize($tmpName));
		$content = addslashes($content);
		fclose($fp);

		if(!get_magic_quotes_gpc()){
			$fileName = addslashes($fileName);
			}
			
		mysqli_query($connect, "UPDATE login SET photo='$content', type='$fileType', size='$fileSize' WHERE username='$username'");

		if(mysqli_error($connect)){
			$error = urlencode("Profile photo was not updated");
			$url = $_SERVER['PHP_SELF']."?uploadPhoto=1&error=$error";
			echo '<meta http-equiv="refresh" content="0; url='.$url.'">';
			exit;
			}
		else{
			$agent = $_SERVER['HTTP_USER_AGENT'];
			$address = $_SERVER['REMOTE_ADDR'];
			$time = date('Y-m-d H:i:s');
			mysqli_query($connect, "UPDATE login SET lastlogin='$time', loginip='$address' WHERE username='$_SESSION[userlogin]'");
			mysqli_query($connect, "INSERT INTO loginlog VALUES(now(),'$username','$address','$agent')");
			
			$url = ($_SESSION['passwordchange']=='Yes')? 'includes/login.php?logout':$_SERVER['PHP_SELF'].'?uploadCorpus=1';
			echo '<meta http-equiv="refresh" content="0; url='.$url.'">';
			exit;
			}
		}
	
?>
