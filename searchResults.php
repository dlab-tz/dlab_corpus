<?php
    include('connection/session.php');
	include('connection/connect.php');
	include('includes/injection.php');

    //search wordwrap

	if(isset($_POST['search'])) {
		$keyword = addslashes($_POST['searchKey']);
		$sql = "SELECT c.content, c.id, c.pub_year, ct.title FROM corpus c
		         JOIN category ct ON c.categoryid=ct.categoryid
				 WHERE c.content LIKE '%$keyword%'";
				//  exit($sql);
		$results = mysqli_query($connect,$sql) or exit(mysqli_error_list($connect));
		if(mysqli_num_rows($results) > 0) {
            $returnArray = [];
		    $count = 0;
			while($row=mysqli_fetch_assoc($results)) {
                 $content = explode(" ", $row['content']);
				 $p = '<p>';
				 foreach($content as $key=>$word) {
                     if (strtolower($word) == strtolower($keyword)) {
                          $p .= '<span style="background: red; font-weight: bold;">'.$word.'</span> ';
						  $count;
					 } else {
						 $p .= $word.' ';
					 }
				 }
				 $returnArray[] = [
					 'id' =>  $row['id'],
					 'content' => $p,
					 'pub_year' => $row['pub_year'],
					 'category' => $row['title']
				 ];
				 
			}
            $table = " <table class='table'>
            <tbody>";
           
               foreach($returnArray as $key=>$value) {
                $table .= '<tr><td>'.$value["id"].'</td>
                                <td>'.$value["pub_year"].'</td>
                                <td>'.$value["content"].'</td></tr>';
            }
           $table .=' </tbody></table>';
           echo $table;
           
            // var_dump($returnArray);
			// $returnArray['freq'] = $count;
			// $location = 'corpus.php?result='.$returnArray;
			// header('Location: '.$location);
			exit();
		} else {
			$error = urlencode('<span style="color:red"><br/>No matching words found.</span>');
			$url .= "&error=$error";
			echo '<meta http-equiv="refresh" content="0; url='.$url.'" />';
			exit;
		}
	}