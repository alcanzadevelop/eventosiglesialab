<?php

require_once "vendor/autoload.php";

include('al-includes/qrlib/qrlib.php');

use GuzzleHttp\Client;
use PHPMailer\PHPMailer\PHPMailer;

require('al-admin/core.php');
require('al-admin/functions.php');

require('al-includes/emails.php');

//Recibimos el pago
if (!empty($_GET['id'])) {

    $id = $_GET['id'];

    //Validamos la orden de pago que este pagada
    if (checkPayment($id)) {
        $orderId = getOrder($id);

        //Si está pagada la registramos
        if ($orderId != 0) {

            //Creamos Tickets
            $eventId = getOrderSingle($conn, 'eventId', $orderId);
            $personId = getOrderSingle($conn, 'personId', $orderId);
            $orderTickets = getOrderSingle($conn, 'orderTickets', $orderId);
            $organizationId = getOrderSingle($conn, 'organizationId', $orderId);
            createTicket($conn, $eventId, $personId, $orderTickets);

            //Descontamos aforo del evento
            $eventCapacity = getEventInfoSingle($conn, "eventCapacity", $eventId);
            $newcapacity = $eventCapacity - $orderTickets;
            updateCapacity($conn, $eventId, $newcapacity);

            //Generamos Código QR
            $ticketId = getTicketSingle($conn, 'ticketId', $personId);
            $link = 'https://eventos.iglesialab.com/validate?id=' . $ticketId . '&token=' . md5($ticketId);
            $codeContents = $link;
            $randName = md5(rand(1, 100000) . md5(rand(1, 100000)) . rand(1, 100000)) . ".png";
            QRcode::png($codeContents, 'al-uploads/events/qrcodes/' . $randName, QR_ECLEVEL_L, 3);
            $ticketQrLink = "https://eventos.iglesialab.com/al-uploads/events/qrcodes/" . $randName;
            updateTicketQrLink($conn, $ticketId, $ticketQrLink);

            //Enviamos comprobante de pago al cliente

            $organizationEmail = getOrganizationInfoSingle($conn, "organizationEmail", $organizationId);
            $organizationName = getOrganizationInfoSingle($conn, "organizationName", $organizationId);
            $personEmail=getPersonSingle($conn, 'personEmail', $personId);
            $personName=getPersonSingle($conn, 'personEmail', $personId);
            $eventName=getEventInfoSingle($conn, 'eventName', $eventId);

            $mail = new PHPMailer;
            $mail->isSMTP();
            $mail->SMTPDebug = 2;
            $mail->Host = 'smtp.hostinger.com';
            $mail->Port = 587;
            $mail->SMTPAuth = true;

            $mail->Username = 'noresponder@iglesialab.com';
            $mail->Password = '748159263';

            $mail->setFrom('no-responder@iglesialab.com', $organizationName);
            $mail->addReplyTo($organizationEmail, $organizationName);
            $mail->addAddress($personEmail, $personName);

            $mail->Subject = 'Comprobante de Pago - '.$eventName;

            $mail->msgHTML(returnpaymentReciept($conn, $orderId));
            $mail->IsHTML(true);

            if (!$mail->send()) {
                echo 'Mailer Error: ' . $mail->ErrorInfo;
            } else {
                echo 'El comprobante ha sido enviado.';
            }

            //Enviamos código QR al cliente

            //Aparece aviso de venta exitosa

        }

    } else {
        echo "Se ha producido un error. Por favor valide su pago a través de un correo.";
        die();
    }

}

?>
