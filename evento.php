<?php

session_start();
require_once "vendor/autoload.php";
use GuzzleHttp\Client;

require('al-admin/core.php');
require('al-admin/functions.php');

//OBTENER ID A PARTIR DE SLUG!!!!

$eventId = getIdFromSlug($conn, $_GET['id']);

if($eventId==0 || empty($eventId)){
    header('Location: https://google.com');
}

if (validateIfEventExists($conn, $eventId)) {
    $organizationId = getEventInfoSingle($conn, "organizationId", $eventId);
    $organizationLogo = getOrganizationInfoSingle($conn, "organizationLogo", $organizationId);
    $organizationName = getOrganizationInfoSingle($conn, "organizationName", $organizationId);
    $organizationAffiliate = getOrganizationInfoSingle($conn, "organizationAffiliate", $organizationId);
    $eventName = getEventInfoSingle($conn, "eventName", $eventId);
    $eventDate = getEventInfoSingle($conn, "eventDate", $eventId);
    $eventFinalDate = getEventInfoSingle($conn, "eventFinalDate", $eventId);
    $eventValue = getEventInfoSingle($conn, "eventValue", $eventId);
    $eventImage = getEventInfoSingle($conn, "eventImage", $eventId);
    $eventDescription = getEventInfoSingle($conn, "eventDescription", $eventId);
    $eventCapacity = getEventInfoSingle($conn, "eventCapacity", $eventId);
    $eventAddress = getEventInfoSingle($conn, "eventAddress", $eventId);
    $eventIsSingleSale = getEventInfoSingle($conn, "eventIsSingleSale", $eventId);
} else {
    $eventId = 0;
}

if (!empty($_POST['orderTickets']) && !empty($_POST['orderName']) && !empty($_POST['orderRut']) && !empty($_POST['orderEmail'])) {

    $orderTickets = $_POST['orderTickets'];
    $orderName = sanitizeAndClean($_POST['orderName']);
    $orderRut = sanitizeAndCleanRut($_POST['orderRut']);
    $orderEmail = sanitizeAndClean($_POST['orderEmail']);
    $orderValue = $orderTickets * $eventValue;

    //Generamos person
    $personId = generatePerson($conn, $orderName, $orderRut, $orderEmail);
    if ($personId != 0) {
        //Generamos orden sin ticket
        $orderId = generateOrder($conn, $eventId, $personId, 0, $organizationId, $orderTickets, $orderValue);
        //Generamos Pago y enviamos
        if ($orderId != 0) {
            $client = new \GuzzleHttp\Client();
            $body = $client->request('POST', 'https://app.payku.cl/api/transaction', [
                'json' => [
                    'email' => 'finanzas@naturalmentesobrenatural.org',
                    'order' => $orderId,
                    'subject' => $orderName,
                    'amount' => $orderValue,
                    'payment' => 1,
                    'urlreturn' => 'https://eventos.iglesialab.com/api',
                    'urlnotify' => 'https://eventos.iglesialab.com/api',
                    'marketplace' => $organizationAffiliate
                ],
                'headers' => [
                    'Authorization' => 'Bearer tkpuaa19341fe10942f825f247c13843'
                ]
            ])->getBody();
            $response = json_decode($body);
            header('Location: '.$response->url);
            die();
        } else {
            echo "Se ha producido un error, por favor intenta nuevamente.";
        }
    } else {
        echo "Se ha producido un error, por favor intenta nuevamente.";
    }
}

?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title><?php echo $organizationName; ?> - <?php echo $eventName; ?></title>
    <meta name="description" content="<?php echo str_replace("<br/>","",$eventDescription); ?>">
    <meta name="og:image" content="al-uploads/events/covers/<?php echo $eventImage; ?>">
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link href="./vendor/bootstrap-select/dist/css/bootstrap-select.min.css" rel="stylesheet">
    <link href="./vendor/lightgallery/css/lightgallery.min.css" rel="stylesheet">
    <link href="./css/style.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
</head>
<style type="text/css">
    .row-header-event{
        padding: 5% 0%;
    }
    .row-bar-event{
        padding: 5% 0%;
        background-color: #fafafa;
    }
    .row-body-event{
        padding: 5% 0%;
    }
</style>
<body>

<!--*** Preloader ***-->
<?php include('al-includes/preloader.php');?>

<!--*** Main wrapper start ***-->
<div id="main-wrapper" style="background-color: #fff !important;">

    <!--*** Content body start ***-->
    <nav class="navbar bg-light">
        <div class="container-fluid">
            <div class="navbar-brand">
                <img src="<?php echo $organizationLogo; ?>" alt="<?php echo $organizationName; ?>" height="64" class="d-inline-block align-text-top">
            </div>
        </div>
    </nav>
    <div class="container">
        <div class="row row-header-event">
            <div class="col-7">
                <h1><?php echo $eventName; ?></h1>
                <h3><?php

                    setlocale(LC_ALL, 'spanish');

                    echo ucfirst(iconv('ISO-8859-2', 'UTF-8', strftime("%A", strtotime($eventDate)))).", ";
                    echo date('d', strtotime($eventDate))." ";
                    echo ucfirst(iconv('ISO-8859-2', 'UTF-8', strftime("%b", strtotime($eventDate))))." - ";
                    echo date('Y', strtotime($eventDate));

                    ?></h3>
            </div>
            <div class="col-5">
                <img src="al-uploads/events/covers/<?php echo $eventImage; ?>" width="100%">
            </div>
        </div>
        <div class="row text-center row-bar-event">
            <form class="row" action="" method="post" style="width: 100%;">
                <div class="col-4">
                    <b><p>Valor Individual: <br/><label style='font-size: 1.5rem;'>$<?php echo number_format($eventValue,0,",","."); ?></label></p></b>
                    <?php if ($eventIsSingleSale == 0) { ?>
                        <div class="mb-3">
                            <label for="ticketsEvent" class="form-label">Selecciona la cantidad de entradas.</label>
                            <select name="orderTickets" class="form-select form-select-sm" required>
                                <option value="" disabled selected hidden>Selecciona la cantidad</option>
                                <?php
                                #!!!! NOTA! - Validar cantidad disponible
                                $i = 1;
                                do {
                                    echo "<option value='" . $i . "'>" . $i . "</option>";
                                    $i++;
                                } while ($i <= 10)
                                ?>
                            </select>
                        </div>
                    <?php } ?>
                </div>
                <div class="col-4">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Nombre y Apellido</label>
                        <input name="orderName" type="text" class="form-control" id="exampleInputEmail1"
                               aria-describedby="emailHelp" required>
                    </div>
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">R.U.T</label>
                        <input name="orderRut" type="text" class="form-control" id="exampleInputEmail1"
                               aria-describedby="emailHelp" required>
                    </div>
                </div>
                <div class="col-4">
                    <div class="mb-3">
                        <label for="exampleInputEmail1" class="form-label">Email</label>
                        <input name="orderEmail" type="email" class="form-control" id="exampleInputEmail1"
                               aria-describedby="emailHelp" required>
                    </div>
                    <div class="mb-3">
                        <br/>
                        <button type="submit" class="btn btn-secondary">Registrarme</button>
                    </div>
                </div>
            </form>
        </div>
        <div class="row row-body-event">
            <div class="col-8">
                <b><p>Detalles</p></b>
                <p><b><?php echo $eventName."<br/>"; ?></b><br/>

                    <?php echo $eventDescription; ?></p>
            </div>
            <div class="col-4">
                <b><p>Fecha</p></b>
                <p><?php
                    setlocale(LC_ALL, 'spanish');
                    echo ucfirst(iconv('ISO-8859-2', 'UTF-8', strftime("%A", strtotime($eventDate)))).", ";
                    echo date('d', strtotime($eventDate))." ";
                    echo ucfirst(iconv('ISO-8859-2', 'UTF-8', strftime("%b", strtotime($eventDate))))." - ";
                    echo date('Y', strtotime($eventDate)); ?></p>
                <p><?php echo ucfirst(iconv('ISO-8859-2', 'UTF-8', strftime("%A", strtotime($eventFinalDate)))).", ";
                    echo date('d', strtotime($eventFinalDate))." ";
                    echo ucfirst(iconv('ISO-8859-2', 'UTF-8', strftime("%b", strtotime($eventFinalDate))))." - ";
                    echo date('Y', strtotime($eventFinalDate)); ?></p>
                <b><p>Lugar</p></b>
                <p><?php echo $eventAddress; ?></p>
            </div>
        </div>
    </div>
    <!--*** Content body end ***-->

</div>
<!--*** Main wrapper end ***-->

<!--*** Scripts ***-->
<?php include('al-includes/scripts-footer.php'); ?>
<script src="./vendor/chart.js/Chart.bundle.min.js"></script>
<script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
<script>
    (function($) {
        var table = $('#example2').DataTable({
            searching: false,
            paging:true,
            select: false,
            //info: false,
            lengthChange:false

        });
        var table = $('#example3').DataTable({
            searching: false,
            paging:true,
            select: false,
            //info: false,
            lengthChange:false

        });
        var table = $('#example4').DataTable({
            searching: false,
            paging:true,
            select: false,
            //info: false,
            lengthChange:false

        });
        var table = $('#example5').DataTable({
            searching: false,
            paging:true,
            select: false,
            //info: false,
            lengthChange:false

        });
        $('#example tbody').on('click', 'tr', function () {
            var data = table.row( this ).data();

        });
    })(jQuery);
</script>
</body>

</html>