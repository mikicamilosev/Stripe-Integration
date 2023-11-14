<?php
    include ('../templates/db_connect.php');

    if ($_POST['saveremove'] == 'save') {

        // PREPARE AND BIND
        $stmt = $db->prepare("INSERT INTO offers_watch (datetime_added, owner_id, offer_id) VALUES (CONVERT_TZ(NOW(), 'America/Chicago', 'America/New_York'), ?, ?)");
        $stmt->bind_param("ss", $ownerid, $offerid);

        // SET PARAMETERS AND EXECUTE
        $ownerid = $_POST['ownerid'];
        $offerid = $_POST['offerid'];

        $stmt->execute();
        $stmt->close();

    }

    if ($_POST['saveremove'] == 'remove') {

        // PREPARE AND BIND
        $stmt = $db->prepare("DELETE FROM offers_watch WHERE owner_id=? AND offer_id=?");
        $stmt->bind_param("ss", $ownerid, $offerid);

        // SET PARAMETERS AND EXECUTE
        $ownerid = $_POST['ownerid'];
        $offerid = $_POST['offerid'];

        $stmt->execute();
        $stmt->close();    
    }

    $stmt = $db->prepare("SELECT COUNT(*) FROM offers_watch WHERE owner_id=?");
    $stmt->bind_param("s", $ownerid);

    // SET PARAMETERS AND EXECUTE
    $ownerid = $_POST['ownerid'];
    
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    
    echo "(".$row['COUNT(*)'].")";

    $stmt->close();  

?>