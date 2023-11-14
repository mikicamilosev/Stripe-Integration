
<?php

// Create connection
$db = new mysqli('localhost', 'root', '', 'stripe');

if($db->connect_errno > 0){
    die('Unable to connect to database [' . $db->connect_error . ']');
}

?>