<?php
require('al-admin/core.php');

if($_GET['action']=="3647ef876f87"){
    echo "Hay que acreditar a: ".$_GET['idTicket'];
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE ticket SET ticketState='ACCREDITED' WHERE ticketId=".$_GET['idTicket'];
    $stmt = $conn->prepare($sql);
    $stmt->execute();
    header("Location: acreditar.php?eventId=".$_GET['eventId']);
}

?>
