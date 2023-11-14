<?php
    include '../templates/db_connect.php';

    if ($_POST['newmessage']) {

        // PREPARE AND BIND
        $stmt = $db->prepare("INSERT INTO chats (datetime, chat_id, sender_id, recipient_id, offer_id, seen_by_recipient, message) VALUES (NOW(), ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssss", $chat_id, $sender_id, $recipient_id, $offer_id, $seen, $newmessage);

        // SET PARAMETERS AND EXECUTE
        $chat_id = $_POST['chatid'];
        $sender_id = $_POST['userid'];
        $recipient_id = $_POST['recipientid'];
        $offer_id = $_POST['offerid'];
        $seen = "N";
        $newmessage = $_POST['newmessage'];

        $stmt->execute();
        $stmt->close();

    }

?>