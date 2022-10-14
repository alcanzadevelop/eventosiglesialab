<?php

session_start();

require_once "vendor/autoload.php";

use GuzzleHttp\Client;
use PHPMailer\PHPMailer\PHPMailer;

require('al-admin/core.php');
require('al-admin/functions.php');

require('al-includes/emails.php');

//Recibimos el Id y el Token
if (!empty($_GET['id']) && !empty($_GET['token'])) {

$id=$_GET['id'];
$token=$_GET['token'];

    $stmt = $conn->query("SELECT * FROM `order` WHERE ticketId=" . $id);
    while ($row = $stmt->fetch()) {
        echo "
        <tr>
          <th scope='row'>Información de la Entrada</th>
          <td>Nombre: " . getPersonSingle($conn, 'personName', $row['personId']) . " " . getPersonSingle($conn, 'personLastName', $row['personId']) . "</td>
          <td>RUT: " . getPersonSingle($conn, 'personRut', $row['personId']) . "</td>
          <td>Nº de Entradas: " . $row['orderTickets'] . "</td>
          <td>Evento: " . getEventInfoSingle($conn, 'eventName', $row['eventId']) . "</td>
        
        ";
        if(updateTicketQrLinkValidate($conn, $id)){
            echo "
<td><b>Entrada Validada</b></td>
</tr>";
        }else{
            echo "
<td><b>ESTA ENTRADA YA FUE VALIDADA</b></td>
</tr>";
        }
    }

}

?>
