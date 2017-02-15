<?php
/*
 * counter.php
 * 
 * Copyright 2017 Peter Abely <abelypeter@hotmail.com>
 * 
 * Used for counting the visitors to the site
 * 
 * 
 * 
 */

$counter_name = "./counter.txt";

//check if a text file exists. if not create one and initialize
if (!file_exists($counter_name)){
    $f = fopen($counter_name, "w");
    fwrite($f,"1000");
    fclose($f);
}
	
// Read the current value of our counter file
$f = fopen($counter_name, "r");
$counterVal = fread($f, filesize($counter_name));
fclose($f);

// Has visitor been counted in this session?
// If not, increase counter value by one
if(!isset($_SESSION['hasVisited'])){
    $_SESSION['hasVisited']="yes";
    $counterVal++;
    $f = fopen($counter_name, "w");
    fwrite($f, $counterVal);
    fclose($f);
}


echo "You are visitor number: ".$counterVal;





?>
