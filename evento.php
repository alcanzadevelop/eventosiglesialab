<?php

session_start();
require_once "vendor/autoload.php";
use GuzzleHttp\Client;

require('al-admin/core.php');
require('al-admin/functions.php');

$eventId = $_GET['id'];

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
    $orderName = $_POST['orderName'];
    $orderRut = $_POST['orderRut'];
    $orderEmail = $_POST['orderEmail'];
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
<?php include('al-includes/head.php');?>
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

                        echo date('l', strtotime($eventDate)).", ";
                        echo date('d', strtotime($eventDate))." ";
                        echo date('M', strtotime($eventDate))." - ";
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
                        <b><p>Valor Individual:</b> <?php echo $eventValue; ?></p>
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
                    <p><?php echo $eventDescription; ?></p>
                </div>
                <div class="col-4">
                    <b><p>Fecha</p></b>
                    <p><?php echo date('l', strtotime($eventDate)).", ";
                        echo date('d', strtotime($eventDate))." ";
                        echo date('M', strtotime($eventDate))." - ";
                        echo date('Y', strtotime($eventDate)); ?></p>
                    <p><?php echo date('l', strtotime($eventFinalDate)).", ";
                        echo date('d', strtotime($eventFinalDate))." ";
                        echo date('M', strtotime($eventFinalDate))." - ";
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