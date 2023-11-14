
<?php

// Create connection
$db = new mysqli('localhost', 'uge2tu5r4idiz', '&)#1A215*2*e', 'dbvfylgjseyivt');

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

?>