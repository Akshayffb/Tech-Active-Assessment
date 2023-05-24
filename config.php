<?php

$servername = 'localhost';
$username = 'root';
$password = '';
$db = 'techActive';

// creating connection to db
$con = mysqli_connect($servername,$username,$password,$db);

if(!$con){
    exit('Try again, Could not connect to Database');
}

?>