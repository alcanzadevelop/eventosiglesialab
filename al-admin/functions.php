<?php

function login($conn, $user, $pass)
{
    try {
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
        if (!empty($_SESSION['userId'])) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {

    }
}

function getIdFromSlug($conn, $slug)
{
    $stmt = $conn->query("SELECT * FROM `event` WHERE eventSlug='" . $slug . "'");
    while ($row = $stmt->fetch()) {
        $eventId = $row['eventId'];
    }
    return $eventId;
}

function checkIfExistsCreditor($conn, $email, $userOrganization)
{
    try {
        $stmt = $conn->query("SELECT * FROM `user` WHERE userEmail='" . $email . "' AND userOrganization='" . $userOrganization . "' AND userState='ACREDITADOR'");
        while ($row = $stmt->fetch()) {
            $userId = $row['userId'];
        }
        if (!empty($userId)) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {

    }
}

function createCreditor($conn, $name, $lastName, $email, $pass, $userOrganization, $organizationId)
{
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
    $sql = "SELECT SUM(orderTickets) FROM order";
    $nRows = $conn->query("SELECT count(*) FROM `ticket` WHERE eventId=" . $eventId . " AND ticketState='VALID'")->fetchColumn();
    echo $nRows;
}

//Funciones Plataforma

// Nº de Entradas vendidas
function getSoldTickets($conn, $eventId)
{
    $orderTotal = 0;
    $stmt = $conn->query("SELECT ticketId FROM `ticket` WHERE ticketState='VALID' OR ticketState='ACCREDITED' AND eventId=" . $eventId);
    while ($row = $stmt->fetch()) {
        $sql = "SELECT * FROM `order` WHERE ticketId=" . $row['ticketId'] . " AND eventId=" . $eventId;
        $stmts = $conn->query($sql);
        while ($rows = $stmts->fetch()) {
            $orderTotal += $rows['orderTickets'];
        }
    }
    return $orderTotal;
}

// Nº de Entradas vendidas
function getSoldAmount($conn, $eventId)
{
    $orderTotal = 0;
    $stmt = $conn->query("SELECT ticketId FROM `ticket` WHERE ticketState='VALID' OR ticketState='ACCREDITED' AND eventId=" . $eventId);
    while ($row = $stmt->fetch()) {
        $sql = "SELECT * FROM `order` WHERE ticketId=" . $row['ticketId'] . " AND eventId=" . $eventId;
        $stmts = $conn->query($sql);
        while ($rows = $stmts->fetch()) {
            $orderTotal += $rows['orderValue'];
        }
    }
    return $orderTotal;
}


//Fin funciones plataforma

function validateIfEventExists($conn, $eventId)
{
    try {
        $stmt = $conn->query("SELECT * FROM `event` WHERE eventId=" . $eventId);
        while ($row = $stmt->fetch()) {
            $theEventId = $row['eventId'];
        }
        if (!empty($theEventId)) {
            return true;
        } else {
            return false;
        }
    } catch (Exception $e) {

    }
}

function getReportEventOrder($conn, $info, $ticketId)
{
    try {
        $stmt = $conn->query("SELECT " . $info . " FROM `order` WHERE ticketId=" . $ticketId);
        while ($row = $stmt->fetch()) {
            $theInfo = $row[$info];
        }
        if (!empty($theInfo)) {
            return $theInfo;
        } else {
            return "";
        }
    } catch (Exception $e) {

    }
}

function getReportEvent($conn, $eventId)
{
    $sql = "SELECT * FROM ticket WHERE eventId=" . $eventId . " AND ticketState='ACCREDITED' OR ticketState='VALID' AND eventId=" . $eventId ;
    $stmt = $conn->query($sql);
    while ($row = $stmt->fetch()) {
        echo "<tr>";
        echo "<td>" . getReportEventOrder($conn, 'orderId', $row['ticketId']) . "</td>";
        echo "<td>" . getPersonSingle($conn, 'personName', $row['personId']) . "</td>";
        echo "<td>" . getPersonSingle($conn, 'personRut', $row['personId']) . "</td>";
        echo "<td>" . getPersonSingle($conn, 'personEmail', $row['personId']) . "</td>";
        echo "<td>" . getReportEventOrder($conn, 'orderTickets', $row['ticketId']) . "</td>";
        echo "<td>" . getReportEventOrder($conn, 'orderValue', $row['ticketId']) . "</td>";
        echo "</tr>";
    }
}

function getPersonSingle($conn, $personSingle, $personId)
{
    try {
        $stmt = $conn->query("SELECT " . $personSingle . " FROM `person` WHERE personId=" . $personId);
        while ($row = $stmt->fetch()) {
            $theInfo = $row[$personSingle];
        }
        if (!empty($theInfo)) {
            return $theInfo;
        } else {
            return "";
        }
    } catch (Exception $e) {

    }
}

function getOrderSingle($conn, $orderSingle, $orderId)
{
    try {
        $stmt = $conn->query("SELECT " . $orderSingle . " FROM `order` WHERE orderId=" . $orderId);
        while ($row = $stmt->fetch()) {
            $theInfo = $row[$orderSingle];
        }
        if (!empty($theInfo)) {
            return $theInfo;
        } else {
            return "";
        }
    } catch (Exception $e) {

    }
}

function calculateIncomeWithDiscount($theValue){
    $paykuDiscount=($theValue*3.333333333333333333)/100;
    $preValue=$theValue-$paykuDiscount;
    $finalValue=($preValue*98)/100;
    return $finalValue;
}

function getTicketSingle($conn, $ticketSingle, $personId)
{
    try {
        $stmt = $conn->query("SELECT " . $ticketSingle . " FROM `ticket` WHERE personId=" . $personId);
        while ($row = $stmt->fetch()) {
            $theInfo = $row[$ticketSingle];
        }
        if (!empty($theInfo)) {
            return $theInfo;
        } else {
            return "";
        }
    } catch (Exception $e) {

    }
}

function getTicketSingleForEmail($conn, $ticketSingle, $ticketId)
{
    try {
        $stmt = $conn->query("SELECT " . $ticketSingle . " FROM `ticket` WHERE ticketId=" . $ticketId);
        while ($row = $stmt->fetch()) {
            $theInfo = $row[$ticketSingle];
        }
        if (!empty($theInfo)) {
            return $theInfo;
        } else {
            return "";
        }
    } catch (Exception $e) {

    }
}

function getQrCodeSingle($conn, $ticketId)
{
    try {
        $stmt = $conn->query("SELECT ticketQrLink FROM `ticket` WHERE ticketId=" . $ticketId);
        while ($row = $stmt->fetch()) {
            $theInfo = $row['ticketQrLink'];
        }
        if (!empty($theInfo)) {
            return $theInfo;
        } else {
            return "";
        }
    } catch (Exception $e) {

    }
}

function getEventInfoSingle($conn, $eventInfoSingle, $eventId)
{
    try {
        $stmt = $conn->query("SELECT " . $eventInfoSingle . " FROM `event` WHERE eventId=" . $eventId);
        while ($row = $stmt->fetch()) {
            $theInfo = $row[$eventInfoSingle];
        }
        if (!empty($theInfo)) {
            return $theInfo;
        } else {
            return "";
        }
    } catch (Exception $e) {

    }
}

function getOrganizationInfoSingle($conn, $organizationInfoSingle, $organizationId)
{
    try {
        $stmt = $conn->query("SELECT " . $organizationInfoSingle . " FROM `organization` WHERE organizationId=" . $organizationId);
        while ($row = $stmt->fetch()) {
            $theInfo = $row[$organizationInfoSingle];
        }
        if (!empty($theInfo)) {
            return $theInfo;
        } else {
            return "";
        }
    } catch (Exception $e) {

    }
}

function checkIfExistsPerson($conn, $personEmail, $personRut)
{
    try {
        $stmt = $conn->query("SELECT * FROM `person` WHERE personEmail='" . $personEmail . "' AND personRut='" . $personRut . "'");
        while ($row = $stmt->fetch()) {
            $personId = $row['personId'];
        }
        if (!empty($personId)) {
            return $personId;
        } else {
            return 0;
        }
    } catch (Exception $e) {

    }
}

function generatePerson($conn, $orderName, $orderRut, $orderEmail)
{
    if (!checkIfExistsPerson($conn, $orderEmail, $orderRut)) {
        $sql = "INSERT INTO `person`(`personName`, `personLastName`, `personRut`, `personEmail`, `personPhone`, `personExtra`, `personAge`) VALUES ('" . $orderName . "', '', '" . $orderRut . "','" . $orderEmail . "', '', '', 0)";

        if ($conn->exec($sql)) {
            return checkIfExistsPerson($conn, $orderEmail, $orderRut);
        } else {
            return 0;
        }
    } else {
        return checkIfExistsPerson($conn, $orderEmail, $orderRut);
    }
}

function getOrderId($conn, $eventId, $personId, $organizationId, $orderTickets, $orderValue)
{
    try {
        $stmt = $conn->query("SELECT * FROM `order` WHERE eventId=" . $eventId . " AND personId=" . $personId . " AND organizationId=" . $organizationId . " AND orderTickets=" . $orderTickets . " AND orderValue=" . $orderValue);
        while ($row = $stmt->fetch()) {
            $orderId = $row['orderId'];
        }
        if (!empty($personId)) {
            return $orderId;
        } else {
            return 0;
        }
    } catch (Exception $e) {

    }
}

function generateOrder($conn, $eventId, $personId, $ticketId, $organizationId, $orderTickets, $orderValue)
{
    $sql = "INSERT INTO `order`(`eventId`, `personId`, `ticketId`, `organizationId`, `orderTickets`, `orderValue`) VALUES (" . $eventId . "," . $personId . "," . $ticketId . "," . $organizationId . "," . $orderTickets . "," . $orderValue . ")";
    if ($conn->exec($sql)) {
        return getOrderId($conn, $eventId, $personId, $organizationId, $orderTickets, $orderValue);
    } else {
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
    $sql = "UPDATE `event` SET eventCapacity=" . $newcapacity . " WHERE eventId=" . $eventId;
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function updateTicketQrLink($conn, $ticketId, $ticketQrLink)
{
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE `ticket` SET ticketQrLink='" . $ticketQrLink . "' WHERE ticketId=" . $ticketId;
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function updateTicketQrLinkValidate($conn, $ticketId)
{
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE `ticket` SET ticketState='ACCREDITED' WHERE ticketId=" . $ticketId;
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function updateOrderWithTicketId($conn, $ticketId, $orderId)
{
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $sql = "UPDATE `order` SET ticketId=" . $ticketId . " WHERE orderId=" . $orderId;
    $stmt = $conn->prepare($sql);
    if ($stmt->execute()) {
        return true;
    } else {
        return false;
    }
}

function sanitizeAndClean($variable)
{
    $variable = str_replace("'", '', $variable);
    $variable = str_replace('"', '', $variable);
    return $variable;
}

function sanitizeAndCleanRut($rut)
{
    $rut = strtoupper($rut);
    $rut = str_replace(" ", '', $rut);
    $rut = str_replace("'", '', $rut);
    $rut = str_replace('"', '', $rut);
    $rut = str_replace("-", '', $rut);
    $rut = str_replace(".", '', $rut);
    return $rut;
}

function recordTransaction($conn, $id, $personId, $ticketId, $orderId)
{
    $client = new \GuzzleHttp\Client();
    $body = $client->request('GET', 'https://app.payku.cl/api/transaction/' . $id, [
        'headers' => [
            'Authorization' => 'Bearer tkpuaa19341fe10942f825f247c13843'
        ]
    ])->getBody();
    $response = json_decode($body);
    if ($response->status == "success") {
        $transactionId = $id;
        $transactionAmount = $response->amount;
        $transactionDate = $response->created_at;
        $transactionStatus = $response->status;
        $sql = "INSERT INTO `transaction`(`transactionId`, `personId`, `ticketId`, `transactionSubject`, `transactionAmount`, `transactionDate`, `transactionStatus`) VALUES 
                ('" . $transactionId . "'," . $personId . "," . $ticketId . ",'" . $orderId . "'," . $transactionAmount . ",'" . $transactionDate . "','" . $transactionStatus . "')";
        $conn->exec($sql);
    }

}

function listEventsOrganization($conn, $userOrganization)
{
    $stmt = $conn->query("SELECT * FROM `event` WHERE organizationId=" . $userOrganization);
    while ($row = $stmt->fetch()) {
        echo "
        <tr>
          <th scope='row'>" . $row['eventId'] . "</th>
          <td>" . $row['eventName'] . "</td>
          <td>" . $row['eventDate'] . "</td>
          <td>" . $row['eventCapacity'] . "</td>
          <td>" . getSoldTickets($conn, $row['eventId']) . "</td>
        </tr>
        ";
    }
}

function getOrderInfo($conn, $orderSingle, $ticketId)
{
    try {
        $stmt = $conn->query("SELECT " . $orderSingle . " FROM `order` WHERE ticketId=" . $ticketId);
        while ($row = $stmt->fetch()) {
            $theInfo = $row[$orderSingle];
        }
        if (!empty($theInfo)) {
            return $theInfo;
        } else {
            return false;
        }
    } catch (Exception $e) {

    }
}

function getPersonInfo($conn, $personSingle, $personId)
{
    try {
        $stmt = $conn->query("SELECT " . $personSingle . " FROM `person` WHERE personId=" . $personId);
        while ($row = $stmt->fetch()) {
            $theInfo = $row[$personSingle];
        }
        if (!empty($theInfo)) {
            return $theInfo;
        } else {
            return "";
        }
    } catch (Exception $e) {

    }
}

function displayTickets($conn, $eventId)
{
    try {
        $stmt = $conn->query("SELECT * FROM ticket WHERE eventId=" . $eventId . " AND ticketState='VALID'");
        while ($row = $stmt->fetch()) {
            $orderId = getOrderInfo($conn, 'orderId', $row['ticketId']);
            $personName = getPersonInfo($conn, 'personName', $row['personId']);
            $personRut = getPersonInfo($conn, 'personRut', $row['personId']);
            $personEmail = getPersonInfo($conn, 'personEmail', $row['personId']);
            $orderTickets = getOrderInfo($conn, 'orderTickets', $row['ticketId']);
            if($orderId!=false){
                echo "<tr>";
                echo "<td>#0" . $orderId . "</td>";
                echo "<td>" . $personName . "</td>";
                echo "<td>" . $personRut . "</td>";
                echo "<td>" . $personEmail . "</td>";
                echo "<td>" . $orderTickets . "</td>";
                echo "<td>
                    <a href='api-fun.php?action=3647ef876f87&idTicket=" . $row['ticketId'] . "&eventId=" . $eventId . "' class='btn btn-primary btn-sm light'>Acreditar</a>
                  </td>";
                echo "</tr>";
            }
        }
    } catch (Exception $e) {

    }
}

function displayTeam($conn)
{
    try {
        $stmt = $conn->query("SELECT * FROM user WHERE userState='ACREDITADOR'");
        while ($row = $stmt->fetch()) {
            echo "<tr>";
            echo "<td>" . $row['userId'] . "</td>";
            echo "<td>" . $row['userName'] . "</td>";
            echo "<td>" . $row['userLastName'] . "</td>";
            echo "<td>" . $row['userEmail'] . "</td>";
            echo "<td>" . $row['userOrganization'] . "</td>";
            echo "</tr>";
        }
    } catch (Exception $e) {

    }
}

/* FUNCIONES PAYKU */

function checkPayment($id)
{
    $client = new \GuzzleHttp\Client();
    $body = $client->request('GET', 'https://app.payku.cl/api/transaction/' . $id, [
        'headers' => [
            'Authorization' => 'Bearer tkpuaa19341fe10942f825f247c13843'
        ]
    ])->getBody();
    $response = json_decode($body);
    if ($response->status == "success") {
        return true;
    } else {
        return false;
    }
}

function getOrder($id)
{
    $client = new \GuzzleHttp\Client();
    $body = $client->request('GET', 'https://app.payku.cl/api/transaction/' . $id, [
        'headers' => [
            'Authorization' => 'Bearer tkpuaa19341fe10942f825f247c13843'
        ]
    ])->getBody();
    $response = json_decode($body);
    if ($response->status == "success") {
        return $response->order;
    } else {
        return 0;
    }
}