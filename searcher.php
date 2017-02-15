<?php
class Database {
    public function __construct() {
        $this->host = "localhost";
	    $this->name = "acalan_corpus_db";
	    $this->username = "root";
	    $this->password = "Peter7387";
        $this->table = "corpus";
    }

	public function Connect() {
        // connect to the database
        $connect = mysqli_connect($this->host, $this->username, $this->password,$this->name);
	    if(!$connect){
		    echo "<div style='text-aling:center; color:red;'>Server not available! Try again later.</div>";
            return false;
        }
        return $connect; 
    }

    public function Disconnect($connection) {
        mysqli_close($connection);
    }
}

class Searcher {
    var $key;
    public function __construct($searchString) {
        $key = $searchString;
    }    

    public function Search() {
        $db = new Database();
        $connection = $db->Connect();
        $results = mysqli_query($connection,"Select * from `$db->table`;");
        $db->Disconnect($connection);
        if($results) {
            // for($i = 0; $i < mysqli_num_rows($results); $i++) {
            //     $words = mysqli_result($results,$i,"content");
            //     echo $words;
            // }
            $resultArray = mysqli_fetch_array($results, MYSQLI_BOTH);
            foreach($resultArray as $row) {
                echo $row;
                echo "\n\n";
            }
        } else {
            echo "No results";
        }
    }
}

$searcher = new Searcher("Edwin");
$searcher->Search();
?>
