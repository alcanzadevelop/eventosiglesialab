<?php

function login ($conn, $user, $pass){
    try{
        $stmt = $conn->query("SELECT * FROM `user` WHERE userEmail='" . $user . "' AND userPassword='" . $pass . "'");
        while ($row = $stmt->fetch()) {
            $_SESSION['userId'] = $row['userId'];
            $_SESSION['userName'] = $row['userName'];
            $_SESSION['userLastName'] = $row['userLastName'];
            $_SESSION['userRut'] = $row['userRut'];
            $_SESSION['userEmail'] = $row['userEmail'];
            $_SESSION['userOrganization'] = $row['userOrganization'];
            $_SESSION['userState'] = $row['userState'];
            $_SESSION['organizationId'] = $row['organizationId'];
        }
        if(!empty($_SESSION['userId'])){
            return true;
        }
        else{
            return false;
        }
    }catch(Exception $e){

    }
}

function checkIfExistsCreditor ($conn, $email, $userOrganization){
    try{
        $stmt = $conn->query("SELECT * FROM `user` WHERE userEmail='" . $email . "' AND userOrganization='" . $userOrganization . "' AND userState='ACREDITADOR'");
        while ($row = $stmt->fetch()) {
            $userId = $row['userId'];
        }
        if(!empty($userId)){
            return true;
        }
        else{
            return false;
        }
    }catch(Exception $e){

    }
}

function createCreditor ($conn, $name, $lastName, $email, $pass, $userOrganization, $organizationId){
    if (checkIfExistsCreditor($conn, $email, $userOrganization)) {
        return false;
    } else {
        $sql = "INSERT INTO `user`(`userName`, `userLastName`, `userRut`, `userEmail`, `userPassword`, `userOrganization`, `userState`, `organizationId`) VALUES ('" . $name . "', '" . $lastName . "', '', '" . $email . "', '" . $pass . "', '" . $userOrganization . "', 'ACREDITADOR', 0)";
        $conn->exec($sql);
        return true;
    }
}

function getTransactionDate($conn, $ticketId)
{
    $stmt = $conn->query("SELECT transactionDate FROM transaction WHERE ticketId=" . $ticketId);
    $transaction = "";
    while ($row = $stmt->fetch()) {
        $transaction = $row['transactionDate'];
    }
    return $transaction;
}

function getNumberSold($conn, $eventId)
{
    $sql = "SELECT count(*) FROM `ticket` WHERE eventId=" . $eventId . " AND ticketState='VALID'";
    $result = $conn->prepare($sql);
    $result->execute([$bar]);
    $number_of_rows = $result->fetchColumn();
    echo $number_of_rows;
}

function validateIfEventExists($conn, $eventId){
    try{
        $stmt = $conn->query("SELECT * FROM `event` WHERE eventId=" . $eventId);
        while ($row = $stmt->fetch()) {
            $theEventId = $row['eventId'];
        }
        if(!empty($theEventId)){
            return true;
        }
        else{
            return false;
        }
    }catch(Exception $e){

    }
}

function getPersonSingle($conn, $personSingle, $personId){
    try{
        $stmt = $conn->query("SELECT ".$personSingle." FROM `person` WHERE personId=" . $personId);
        while ($row = $stmt->fetch()) {
            $theInfo = $row[$personSingle];
        }
        if(!empty($theInfo)){
            return $theInfo;
        }
        else{
            return "";
        }
    }catch(Exception $e){

    }
}

function getOrderSingle($conn, $orderSingle, $orderId){
    try{
        $stmt = $conn->query("SELECT ".$orderSingle." FROM `order` WHERE orderId=" . $orderId);
        while ($row = $stmt->fetch()) {
            $theInfo = $row[$orderSingle];
        }
        if(!empty($theInfo)){
            return $theInfo;
        }
        else{
            return "";
        }
    }catch(Exception $e){

    }
}

function getTicketSingle($conn, $ticketSingle, $personId){
    try{
        $stmt = $conn->query("SELECT ".$ticketSingle." FROM `ticket` WHERE personId=" . $personId);
        while ($row = $stmt->fetch()) {
            $theInfo = $row[$ticketSingle];
        }
        if(!empty($theInfo)){
            return $theInfo;
        }
        else{
            return "";
        }
    }catch(Exception $e){

    }
}

function getEventInfoSingle($conn, $eventInfoSingle, $eventId){
    try{
        $stmt = $conn->query("SELECT ".$eventInfoSingle." FROM `event` WHERE eventId=" . $eventId);
        while ($row = $stmt->fetch()) {
            $theInfo = $row[$eventInfoSingle];
        }
        if(!empty($theInfo)){
            return $theInfo;
        }
        else{
            return "";
        }
    }catch(Exception $e){

    }
}

function getOrganizationInfoSingle($conn, $organizationInfoSingle, $organizationId){
    try{
        $stmt = $conn->query("SELECT ".$organizationInfoSingle." FROM `organization` WHERE organizationId=" . $organizationId);
        while ($row = $stmt->fetch()) {
            $theInfo = $row[$organizationInfoSingle];
        }
        if(!empty($theInfo)){
            return $theInfo;
        }
        else{
            return "";
        }
    }catch(Exception $e){

    }
}

function checkIfExistsPerson ($conn, $personEmail, $personRut){
    try{
        $stmt = $conn->query("SELECT * FROM `person` WHERE personEmail='" . $personEmail . "' AND personRut='" . $personRut . "'");
        while ($row = $stmt->fetch()) {
            $personId = $row['personId'];
        }
        if(!empty($personId)){
            return $personId;
        }
        else{
            return 0;
        }
    }catch(Exception $e){

    }
}

function generatePerson($conn, $orderName, $orderRut, $orderEmail)
{
    if(!checkIfExistsPerson ($conn, $orderEmail, $orderRut)) {
        $sql = "INSERT INTO `person`(`personName`, `personLastName`, `personRut`, `personEmail`, `personPhone`, `personExtra`, `personAge`) VALUES ('" . $orderName . "', '', '" . $orderRut . "','" . $orderEmail . "', '', '', 0)";

        if ($conn->exec($sql)) {
            return checkIfExistsPerson ($conn, $orderEmail, $orderRut);
        } else {
            return 0;
        }
    }else{
        return checkIfExistsPerson ($conn, $orderEmail, $orderRut);
    }
}

function getOrderId($conn, $eventId, $personId, $organizationId, $orderTickets, $orderValue){
    try{
        $stmt = $conn->query("SELECT * FROM `order` WHERE eventId=" . $eventId . " AND personId=" . $personId . " AND organizationId=".$organizationId." AND orderTickets=".$orderTickets." AND orderValue=".$orderValue);
        while ($row = $stmt->fetch()) {
            $orderId = $row['orderId'];
        }
        if(!empty($personId)){
            return $orderId;
        }
        else{
            return 0;
        }
    }catch(Exception $e){

    }
}

function generateOrder($conn, $eventId, $personId, $ticketId, $organizationId, $orderTickets, $orderValue){
    $sql = "INSERT INTO `order`(`eventId`, `personId`, `ticketId`, `organizationId`, `orderTickets`, `orderValue`) VALUES (".$eventId.",".$personId.",".$ticketId.",".$organizationId.",".$orderTickets.",".$orderValue.")";
    if ($conn->exec($sql)) {
        return getOrderId($conn, $eventId, $personId, $organizationId, $orderTickets, $orderValue);
    }
    else{
        return false;
    }
}

function createTicket($conn, $eventId, $personId)
{
    $sql = "INSERT INTO `ticket`(`eventId`, `personId`, `ticketState`, `ticketQrLink`) VALUES (" . $eventId . "," . $personId . ",'VALID', ' ')";
    $conn->exec($sql);
}

function updateCapacity($conn, $eventId, $newcapacity)
{
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE `event` SET eventCapacity=".$newcapacity." WHERE eventId=".$eventId;
    $stmt = $conn->prepare($sql);
    if($stmt->execute()){
        return true;
    }
    else{
        return false;
    }
}

function updateTicketQrLink($conn, $ticketId, $ticketQrLink)
{
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE `ticket` SET ticketQrLink='".$ticketQrLink."' WHERE ticketId=".$ticketId;
    $stmt = $conn->prepare($sql);
    if($stmt->execute()){
        return true;
    }
    else{
        return false;
    }
}

function updateOrderWithTicketId($conn, $ticketId, $orderId)
{
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE `order` SET ticketId=".$ticketId." WHERE orderId=".$orderId;
    $stmt = $conn->prepare($sql);
    if($stmt->execute()){
        return true;
    }
    else{
        return false;
    }
}

/* FUNCIONES PAYKU */

function checkPayment($id){
    $client = new \GuzzleHttp\Client();
    $body = $client->request('GET', 'https://app.payku.cl/api/transaction/'.$id, [
        'headers' => [
            'Authorization' => 'Bearer tkpuaa19341fe10942f825f247c13843'
        ]
    ])->getBody();
    $response = json_decode($body);
    if($response->status=="success"){
        return true;
    }
    else{
        return false;
    }
}

function getOrder($id){
    $client = new \GuzzleHttp\Client();
    $body = $client->request('GET', 'https://app.payku.cl/api/transaction/'.$id, [
        'headers' => [
            'Authorization' => 'Bearer tkpuaa19341fe10942f825f247c13843'
        ]
    ])->getBody();
    $response = json_decode($body);
    if($response->status=="success"){
        return $response->order;
    }
    else{
        return 0;
    }
}