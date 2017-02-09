<?php
	include('connection/connect.php');
	include('includes/injection.php');
	
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
			$url = "index.php?downloadCorpus=1&error=$error";
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
				<table border="1" cellpadding="3" cellspacing="0" id="searchResult">
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

?>
