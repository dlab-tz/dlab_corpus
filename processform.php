<?php

include('connection/connect.php');
include('includes/injection.php');
include 'vendor/autoload.php';

	
	//download user profile photo
	if(isset($_GET['id'])){
		$ID = $_GET['id'];
			
		$download_file = mysqli_query($connect, "SELECT type, size, photo FROM login WHERE username='$ID'");
		list($type, $size, $content) = mysqli_fetch_array($download_file, MYSQLI_BOTH);
			
		@header("Content-length:$size");
		@header("Content-type:$type");
			
		echo $content;
		}
	
	//TRIGGER TO MANAGE USERS
	//CREATION OF NEW USER
	if(isset($_POST['Register_User'])){
		$fname = trim(Injection($_POST['fname']));
		$lname = trim(Injection($_POST['lname']));
		$gender = Injection($_POST['gender']);
		$position = Injection($_POST['position']);
		$mobile = Injection($_POST['mobile']);
		$email = trim(Injection($_POST['email']));
		$recorder = $_SESSION['userlogin'];
		$url = 'corpus.php?manageUser=1';
		$fullname = ucwords(strtolower($fname)).' '.ucwords(strtolower($lname));
		
		$username1 = strtolower($fname).'.'.strtolower($lname);
		$username2 = strtolower($fname).strtolower($lname);
		$username3 = strtolower($lname).'.'.strtolower($fname);
		$username4 = strtolower($lname).strtolower($fname);
		
		//check if the values are empty or too short
		if(strlen($fname)<2 || strlen($lname)<2 || strlen($mobile)<10 || strlen($email)<6){
			$error = urlencode('<span style="color:red"><br/>Too short inputs</span>');
			$url .= "&error=$error";
			echo '<meta http-equiv="refresh" content="0; url='.$url.'" />';
			exit;
			}
		
		//check username
		$check1 = mysqli_query($connect, "SELECT * FROM login WHERE username='$username1'");
		$check2 = mysqli_query($connect, "SELECT * FROM login WHERE username='$username2'");
		$check3 = mysqli_query($connect, "SELECT * FROM login WHERE username='$username3'");
		$check4 = mysqli_query($connect, "SELECT * FROM login WHERE username='$username4'");
		
		//record user in database
		if(mysqli_num_rows($check1)==0){
			registration($fullname,$gender,$mobile,$email,$recorder,$lname,$username1,$position);
			}
		elseif(mysqli_num_rows($check2)==0){
			registration($fullname,$gender,$mobile,$email,$recorder,$lname,$username2,$position);
			}
		elseif(mysqli_num_rows($check3)==0){
			registration($fullname,$gender,$mobile,$email,$recorder,$lname,$username3,$position);
			}
		elseif(mysqli_num_rows($check4)==0){
			registration($fullname,$gender,$mobile,$email,$recorder,$lname,$username4,$position);
			}
		else{
			$error = urlencode('<span style="color:red"><br/>Usernames are exhausted, please contact developer</span>');
			$url .= "&error=$error";
			echo '<meta http-equiv="refresh" content="0; url='.$url.'" />';
			}
		}

		
	//user registration function
	function registration($fullname,$gender,$mobile,$email,$recorder,$lname,$username,$position){
		$url = 'corpus.php?manageUser=1';
		mysqli_query($connect, "INSERT INTO users VALUES('','$fullname','$gender','$mobile','$email','$recorder',now(),now())");
		if(mysqli_error($connect)){
			$error = urlencode('<span style="color:red"><br/>User registration failed</span>');
			$url .= "&error=$error";
			echo '<meta http-equiv="refresh" content="0; url='.$url.'" />';
			exit;
			}
		
		//create username
		$get_id = mysqli_query($connect, "SELECT userid FROM users WHERE mobileno='$mobile'");
		list($id) = mysqli_fetch_array($get_id, MYSQLI_BOTH);
		
		$password = MD5(strtoupper($lname));
		mysqli_query($connect, "INSERT INTO login(username,userid,password,privilegeid) VALUES('$username','$id','$password','$position')");
		
		if(mysqli_error($connect)){
			mysqli_query($connect, "DELETE FROM login WHERE mobile='$mobile'");
			$error = urlencode('<span style="color:red"><br/>Username creation failed</span>');
			$url .= "&error=$error";
			echo '<meta http-equiv="refresh" content="0; url='.$url.'" />';
			exit;
			}
		
		$error = urlencode('<span style="color:blue"><br/>User was registered</span>');
		$url .= "&error=$error";
		echo '<meta http-equiv="refresh" content="0; url='.$url.'" />';
		exit;
		}

	
	//add new category to the corpus
	if(isset($_POST['Add_Category'])){
		if($_POST['category']==''){
			echo '<meta http-equiv="refresh" content="0; url=corpus.php?uploadCategory=1" />';
			exit;
			}
			
		$category = trim(Injection($_POST['category']));
		$parent = trim(Injection($_POST['parentCat']));
		$category = ucwords(strtolower($category));
		$page = "corpus.php?uploadCategory=1";
		
		//check if the category exists
		$check = mysqli_query($connect, "SELECT * FROM category WHERE title='$category'");
		if(mysqli_num_rows($check)>0){
			$error = urlencode("Category exists already");
			$url = $page."&error=$error";
			echo '<meta http-equiv="refresh" content="0; url='.$url.'" />';
			exit;
			}
			
		mysqli_query($connect, "INSERT INTO category VALUES (DEFAULT, '$parent', '$category')");
		if(mysqli_error($connect)){
			$error = urlencode("Category not added");
			$url = $page."&error=$error";
			echo '<meta http-equiv="refresh" content="0; url='.$url.'" />';
			exit;
			}
		
		echo '<meta http-equiv="refresh" content="0; url=corpus.php?uploadCategory=1" />';
		exit;
		}
		
	
	//upload corpus contents
	if(isset($_POST['Upload_Corpus'])){
		$category = $_POST['category'];
		$username = $_SESSION['userlogin'];
		$pub_date = addslashes($_POST['filePubYear']);
		
		//known file extensions array
		$known_extensions = array('txt');

		$max_size = $_POST['MAX_FILE_SIZE'];
				
		$fileName = $_FILES['fileUploaded']['name'];
		$tmpName  = $_FILES['fileUploaded']['tmp_name'];
		$fileSize = $_FILES['fileUploaded']['size'];
		$fileType = $_FILES['fileUploaded']['type'];
		$author = addslashes($_POST['author']);
		$title = addslashes($_POST['title']);
        $pub_info = addslashes($_POST['pub_info']);
		$year = addslashes($_POST['year']);
		//check if the file size has zero bytes
		if($fileSize <= 0){
			$error = urlencode("<span style='color:red'>Please select file to upload first<br/></span>");
			$url = "corpus.php?uploadCorpus=1&error=$error";
			echo '<meta http-equiv="refresh" content="0; url='.$url.'">';
			exit;
			}

		//check if the file size has exceeded the maximum upload limit size
		if($fileSize > $max_size){
			$error = urlencode("<span style='color:red'>The uploaded file has exceeded maximum upload size limit<br/></span>");
			$url = "corpus.php?uploadCorpus=1&error=$error";
			echo '<meta http-equiv="refresh" content="0; url='.$url.'">';
			exit;
			}

		//get file information
		$file_info = pathinfo($_FILES['fileUploaded']['name']); 
		$ext = strtolower($file_info['extension']);

		//check if the file extension is among the predefined file extensions
		if(!in_array($ext, $known_extensions, true)){
			$error = urlencode("<span style='color:red'>Selected file is not of allowed type<br/></span>");
			$url = "corpus.php?uploadCorpus=1&error=$error";
			echo '<meta http-equiv="refresh" content="0; url='.$url.'">';
			exit;
			}

			// $parser = new \Smalot\PdfParser\Parser();
			// $pdf = $parser->parseFile($tmpName);
			// $content = $pdf->getText();
			// $details = $pdf->getDetails();
			// $creator = !(empty($details['Creator'])) ? $details['Creator'] : $details['Producer'];
			// $author = !(empty($details['Author'])) ? $details['Author'] : $creator;
			// $title = $details['Title'];
			// $creationDate = $details['CreationDate'];

		//read contents of the file
		$fp = fopen($tmpName, 'r');
		$content = fread($fp, filesize($tmpName));
		$content = addslashes($content);
		fclose($fp);
		
		$str = '';
		for($i=0; $i<sizeof($content); $i++){
			$line = explode("\n",trim($content));
			
			foreach($line as $row){
				$words = explode(" ",trim($row));
				
				foreach($words as $word){
					$str .= (strlen($word)<1)? '':ucwords(strtolower($word)).' ';
					}
				}
			}
		$content = trim($str);
		// var_dump($_SESSION);
		// exit('username: '. $_SESSION['userlogin']);
		
		//get the corpus to update
		$qcorpus = mysqli_query($connect, "SELECT content,recorder FROM corpus WHERE categoryid='$category'");
		if(mysqli_num_rows($qcorpus)>0){
			list($corpus,$recorder) = mysqli_fetch_array($qcorpus, MYSQLI_BOTH);
			
			//update the corpus
			$corpus .= '|'.$corpus_str;
			$recorder .= '|'.$_SESSION['userlogin'];
			$corpus = addslashes($corpus);
			mysqli_query($connect, "UPDATE corpus SET content='$corpus', recorder='$recorder', lastupdate=now() WHERE categoryid='$category'");
			
			if(mysqli_error($connect)){
				$error = urlencode("<span style='color:red'>File was not uploaded<br/></span><br/>".mysqli_error($connect));
				$url = "corpus.php?uploadCorpus=1&error=$error";
				echo '<meta http-equiv="refresh" content="0; url='.$url.'">';
				exit;
				}
			else{
				//index the uploaded content
				mysqli_query($connect, "INSERT INTO corpus_update VALUES(DEFAULT,'$category','$username',now(),DEFAULT), '$pub_date'");
				$error = urlencode("<span style='color:blue'>Upload was successful<br/></span>");
				$url = "corpus.php?uploadCorpus=1&error=$error";
				echo '<meta http-equiv="refresh" content="0; url='.$url.'">';
				exit;
				}
			}
		// else{
			//insert corpus for the category
			mysqli_query($connect, "INSERT INTO corpus (categoryid, author, title, content, recorder, lastupdate, pub_year, pub_info) VALUES('$category', '$author', '$title', '$content', '$username', now(), '$year', '$pub_info')") or exit(mysqli_error($connect));
			if(mysqli_error($connect)){
				$error = urlencode("<span style='color:red'>File was not uploaded<br/></span>");
				$url = "corpus.php?uploadCorpus=1&error=$error";
				echo '<meta http-equiv="refresh" content="0; url='.$url.'">';
				exit;
				}
			else{
				//index the uploaded content
				mysqli_query($connect, "INSERT INTO  corpus_update VALUES(DEFAULT,'$category','$username',now(),DEFAULT)");
				$error = urlencode("<span style='color:blue'>Upload was successful<br/></span>");
				$url = "corpus.php?uploadCorpus=1&error=$error";
				echo '<meta http-equiv="refresh" content="0; url='.$url.'">';
				exit;
				}
			// }
		}
	
	
	//downlod of the corpus
	if(isset($_POST['Download'])){
		$category = $_POST['category'];
		$username = $_SESSION['userlogin'];
		
		//download condition
		$WHERE = ($category=='ALL')? "":"WHERE categoryid='$category'";
		
		//query contents
		$get_corpus = mysqli_query($connect, "SELECT content FROM corpus $WHERE");
		if(mysqli_num_rows($get_corpus)<1){
			$error = urlencode("Sorry, no contents available");
			$url = "corpus.php?downloadCorpus=1&error=$error";
			echo '<meta http-equiv="refresh" content="0; url='.$url.'" />';
			exit;
			}
		
		//get contents and sort them
		$corpus_array = array();
		$corpus_str = '';
		while(list($content) = mysqli_fetch_array($get_corpus, MYSQLI_BOTH)){
			$line = explode("|",trim($content));
			
			foreach($line as $row){
				$words = explode(" ",trim($row));
				
				foreach($words as $word){
					$word = str_replace("\'","'",$word);
					$word = str_replace('\"',"'",$word);
					$corpus_array[] = $word;
					}
				}
			}
		
		//sort the corpus
		//asort($corpus_array);
		
		//convert corpus into a string
		foreach($corpus_array as $value){
			$corpus_str .= $value.' ';
			}
		$corpus_str = trim($corpus_str);
		
		//download the file
		$filename = $username;
		@header("Cache-Control: ");
		@header("Content-type: text/plain");
		@header('Content-Disposition: attachment; filename="corpus.txt"');
		echo $corpus_str;
		}
	
	
	//dynamic search for words
	if(isset($_POST['searchWord'])){
		$key = trim($_POST['searchWord']);
		//query corpus contents
		$get_corpus = mysqli_query($connect, "SELECT corp.content,cat.title FROM corpus corp, category cat 
									WHERE cat.categoryid=corp.categoryid");
		if(mysqli_num_rows($get_corpus)<1){
			echo 'Database has no contents';
			exit;
			}
		
		//search for content				
		$i=0;
		$corpus_str = '';
		while(list($content,$category) = mysqli_fetch_array($get_corpus, MYSQLI_BOTH)){
			$corpus_array = array();
			
			$line = explode("|",trim($content));
			
			//get the contents in array
			foreach($line as $row){
				$words = explode(" ",trim($row));
				
				foreach($words as $word){
					$pos = strpos($word,$key);
					$sub = strtolower(substr($word,0,strlen($key)));
					if($pos !== false || $sub==$key){
						$corpus_array[] = str_replace($key,"<b>$key</b>",$word);
						}
					}
				}
			//sort the array
			asort($corpus_array);
			
			//convert the contents in a string
			if(count($corpus_array)>0){
				foreach($corpus_array as $str_val){
					$corpus_str .= $str_val.' ';
					}
				$corpus_str .= '->'.$category.'|';
				}
			}
		
		//check if there were any matches to the keyword
		if($corpus_str==''){
			echo "<br/><br/>No match was found for <b>$key</b>";
			exit;
			}
		else{
			$tbl =<<<EOD
				<br/><br/>
				<table class="table table-responsive table-bordered table-hover" id="searchResult">
					<tr>
						<th><center>SNo</center></th>
						<th><center>CATEGORY</center></th>
						<th><center>WORDS</center></th>
					</tr>
EOD;
			$corpus_str = rtrim($corpus_str,'|');
			$search_content = explode("|",$corpus_str);
			$i=1;
			foreach($search_content as $rowValue){
				$column = explode("->",$rowValue);
				$category = $column[1];
				$corpus = $column[0];
				
				$tbl .=<<<EOD
					<tr>
						<td>$i</td>
						<td>$category</td>
						<td>$corpus</td>
					</tr>
EOD;
				$i++;
				}
			$tbl .=<<<EOD
					</table>
EOD;
			echo $tbl;
			}
		}

	
	//dynamic search for system user details
	if(isset($_POST['searchName'])){
		$key = trim($_POST['searchName']);
		
		//query user details
		if($key=='' || empty($key)){
			$get_username = mysqli_query($connect, "SELECT u.userid, u.fullname, u.gender, u.mobileno, l.username, p.privilege 
										FROM users u, login l, privilege p WHERE l.userid=u.userid 
										AND p.privilegeid=l.privilegeid
										ORDER BY u.fullname ASC");
			
			$get_fullname = mysqli_query($connect, "SELECT * FROM USERS LIMIT 0");
			}
		else{
			$get_username = mysqli_query($connect, "SELECT u.userid, u.fullname, u.gender, u.mobileno, l.username, p.privilege 
										FROM users u, login l, privilege p WHERE l.userid=u.userid 
										AND p.privilegeid=l.privilegeid and l.username like '%$key%'
										ORDER BY u.fullname ASC");
										
			$get_fullname = mysqli_query($connect, "SELECT u.userid, u.fullname, u.gender, u.mobileno, l.username, p.privilege 
										FROM users u, login l, privilege p WHERE l.userid=u.userid 
										AND p.privilegeid=l.privilegeid and u.fullname like '%$key%'
										ORDER BY u.fullname ASC");
			}
			
		if(mysqli_num_rows($get_username)<1 && mysqli_num_rows($get_fullname)<1){
			echo "<span style='color:red'>Search key <b>$key</b> has no associated users</span>";
			exit;
			}
		
		//prepare search results
		$report .= "<table border='1' cellpadding='3' cellspacing='0' id='registeredUser'>
						<tr>
							<th>SNo</th>
							<th><center>FULLNAME</center></th>
							<th><center>USERNAME</center></th>
							<th><center>GENDER</center></th>
							<th><center>MOBILE</center></th>
							<th><center>POSITION</center></th>
							<th><center>PHOTO</center></th>							
						</tr>";
		$i=0;
		$registered_users = array();
		
		//get registered users
		while(list($id, $name, $sex, $mobile, $uname, $priv) = @mysqli_fetch_array($get_username, MYSQLI_BOTH)){
			//check if user has a photo
			$check_log = mysqli_query($connect, "SELECT browser FROM loginlog WHERE username='$uname'");
			$browz = mysqli_num_rows($check_log);
			
			$img = ($browz==0)? '<img src="images/default.jpg" height="80" width="80">':'<img src="processform.php?id='.$uname.'" height="80" width="80">';
			
			$registered_users[$i]['id'] = $id;
			$registered_users[$i]['name'] = $name;
			$registered_users[$i]['uname'] = $uname;
			$registered_users[$i]['sex'] = $sex;
			$registered_users[$i]['mobile'] = $mobile;
			$registered_users[$i]['priv'] = $priv;
			$registered_users[$i]['img'] = $img;
			}
			
		//get registered users
		while(list($id, $name, $sex, $mobile, $uname, $priv) = @mysqli_fetch_array($get_fullname, MYSQLI_BOTH)){
			//check if user has a photo
			$check_log = mysqli_query($connect, "SELECT browser FROM loginlog WHERE username='$uname'");
			$browz = mysqli_num_rows($check_log);
			
			$img = ($browz==0)? '<img src="images/default.jpg" height="80" width="80">':'<img src="processform.php?id='.$uname.'" height="80" width="80">';
			
			if(empty($registered_users)){
				$registered_users[$i]['id'] = $id;
				$registered_users[$i]['name'] = $name;
				$registered_users[$i]['uname'] = $uname;
				$registered_users[$i]['sex'] = $sex;
				$registered_users[$i]['mobile'] = $mobile;
				$registered_users[$i]['priv'] = $priv;
				$registered_users[$i]['img'] = $img;
				}
			else{
				//check if user already exists
				$status = true;
				foreach($registered_users as $storedVal){
					if(in_array($uname,$storedVal,true)){
						$status = false;
						}
					}
				
				//add non existing users
				if($status){
					$registered_users[$i]['id'] = $id;
					$registered_users[$i]['name'] = $name;
					$registered_users[$i]['uname'] = $uname;
					$registered_users[$i]['sex'] = $sex;
					$registered_users[$i]['mobile'] = $mobile;
					$registered_users[$i]['priv'] = $priv;
					$registered_users[$i]['img'] = $img;
					$i++;
					}
				}
			}
			
		$index=1;
		foreach($registered_users as $valueKey){
			$report .= "<tr>
						<td><a href=corpus.php?manageUser=1&Edit=$valueKey[id]><b><u>$index</u></b></a></td>
						<td>$valueKey[name]</td>
						<td>$valueKey[uname]</td>
						<td>$valueKey[sex]</td>
						<td>$valueKey[mobile]</td>
						<td>$valueKey[priv]</td>
						<td>$valueKey[img]</td>
					</tr>";
			$index++;
			}
		$report .= "</table>";
		
		//print report
		echo $report;
		exit;
		}
	
	
	if(isset($_POST['Edit_User'])){
		$userid = $_POST['userid'];
		$uname = $_POST['uname'];
		$name = Injection($_POST['fname']).' '.Injection($_POST['lname']);
		$gender = Injection($_POST['gender']);
		$priv = Injection($_POST['position']);
		$mobile = Injection($_POST['mobile']);
		$mail = Injection($_POST['email']);
		
		//update user information
		mysqli_query($connect, "UPDATE users SET fullname='$name', gender='$sex', mobile='$mobile', email='$mail', lastupdate=now() WHERE userid='$userid'");
		mysqli_query($connect, "UPDATE login SET privilegeid='$priv' WHERE username='$uname'");
		
		//print msg
		$msg = (mysqli_error($connect))? urlencode("<span style='color:red'>User update failed</span>"):urlencode("<span style='color:blue'>User was updated</span>");
		$url = "corpus.php?manageUser=1&Edit=$userid&error=$msg";
		
		echo '<meta http-equiv="refresh" content="0; url='.$url.'" />';
		exit;
		}
	
	
	//dynamic search for system user details
	if(isset($_POST['deleteName'])){
		$key = trim($_POST['deleteName']);
		$report = '';
		
		//query user details
		if($key=='' || empty($key)){
			$get_fullname = mysqli_query($connect, "SELECT u.userid, u.fullname, u.gender, u.mobileno, l.username, p.privilege 
										FROM users u, login l, privilege p WHERE l.userid=u.userid 
										AND p.privilegeid=l.privilegeid
										ORDER BY u.fullname ASC LIMIT 1");
			
			}
		else{						
			$get_fullname = mysqli_query($connect, "SELECT u.userid, u.fullname, u.gender, u.mobileno, l.username, p.privilege 
										FROM users u, login l, privilege p WHERE l.userid=u.userid 
										AND p.privilegeid=l.privilegeid and u.fullname like '%$key%'
										ORDER BY u.fullname ASC");
			}
			
		if(mysqli_num_rows($get_fullname)<1){
			echo "<span style='color:red'>Search key <b>$key</b> has no associated users</span>";
			exit;
			}
		
		//prepare search results
		$report .= "<p></p><table border='1' cellpadding='3' cellspacing='0' id='registeredUser'>
						<tr>
							<th>SNo</th>
							<th><center>FULLNAME</center></th>
							<th><center>USERNAME</center></th>
							<th><center>GENDER</center></th>
							<th><center>MOBILE</center></th>
							<th><center>POSITION</center></th>
							<th><center>PHOTO</center></th>
							<th><center>DELETE</center></th>
						</tr>";
		$i=1;
		//get registered users
		while(list($id, $name, $sex, $mobile, $uname, $priv) = @mysqli_fetch_array($get_fullname, MYSQLI_BOTH)){
			//check if user has a photo
			$check_log = mysqli_query($connect, "SELECT browser FROM loginlog WHERE username='$uname'");
			$browz = mysqli_num_rows($check_log);
			
			$img = ($browz==0)? '<img src="images/default.jpg" height="80" width="80">':'<img src="processform.php?id='.$uname.'" height="80" width="80">';
			
			$report .= "<tr>
						<td>$i</td>
						<td>$name</td>
						<td>$uname</td>
						<td>$sex</td>
						<td>$mobile</td>
						<td>$priv</td>
						<td>$img</td>
						<td>
							<a href='processform.php?Delete=".$id."'>
								<input type='submit' name='Delete' value='Delete' />
							</a>
						</td>
					</tr>";
			$i++;
			}
		$report .= "</table>";
		
		//print report
		echo $report;
		exit;
		}

		
	//Delete selected user
	if(isset($_GET['Delete'])){
		//get userid
		$id = $_GET['Delete'];
		
		//get username
		$get_username = mysqli_query($connect, "SELECT username FROM login WHERE userid='$id'");
		list($username) = mysqli_fetch_array($get_username, MYSQLI_BOTH);
		
		//check if user has reported
		$reporter = mysqli_query($connect, "SELECT * FROM corpus_update WHERE recorder='$username'");
		
		if(@mysqli_num_rows($reporter)>0){
			echo "<script language='javascript'>window.alert(\"User has related contents can not be deleted\")</script>";
			echo '<meta http-equiv="refresh" content="0; url=corpus.php?manageUser=1" />';
			exit;
			}
		
		//cleaning user information
		mysqli_query($connect, "DELETE FROM loginlog WHERE username='$username'");
		mysqli_query($connect, "DELETE FROM login WHERE userid='$id'");
		mysqli_query($connect, "DELETE FROM users WHERE userid='$id'");
		
		//alert and redirect
		echo "<script language='javascript'>window.alert(\"User was deleted successfull\")</script>";
		echo '<meta http-equiv="refresh" content="0; url=corpus.php?manageUser=1" />';
		exit;
		}
		
?>
