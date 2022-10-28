<?php

    require('al-admin/core.php');
    header('Content-Type: text/csv;');
    header('Content-Disposition: attachment; filename=data.csv; charset=utf-8');
    $output = fopen('php://output', 'w');
    fputcsv($output, array('Id', 'Nº Ticket', 'Nº de Entradas', 'Valor Pagado'));
    $stmt = $conn->query("SELECT * FROM `order` WHERE eventId=".$_GET['id']." AND ticketId!=0 ORDER BY `orderId`");
    while ($row = $stmt->fetch())
    {
        fputcsv($output, [$row['orderId'], $row['ticketId'], $row['orderTickets'], $row['orderValue']]);
    }
    fclose($output);

?>