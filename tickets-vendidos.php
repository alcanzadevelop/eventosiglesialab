<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">

<?php

    require('al-admin/core.php');
    require('al-admin/functions.php');

    $eventId=$_GET['eventId'];
    $pageName="Tickets Vendidos";
    $theValue="FALSE";

    if(!empty($_GET['eventId'])) {
        $stmt = $conn->query("SELECT * FROM event WHERE eventId=" . $_GET['eventId']);
        while ($row = $stmt->fetch()) {
            $pageName = $row['eventName'];
            $theValue="TRUE";
        }
    }
    else
    {
        $theValue="FALSE";
    }

?>

<?php include('al-includes/head.php');?>

<body>

<!--*** Preloader ***-->
<?php include('al-includes/preloader.php');?>

    <!--*** Main wrapper start ***-->
    <div id="main-wrapper">

        <?php include('al-includes/navbar.php');?>

        <!--*** Content body start ***-->
        <div class="content-body">
            <!-- row -->
            <div class="container-fluid">
                <div class="row"><!-- Lista de Eventos -->
                    <?php
                    if ($theValue == "FALSE") {
                        date_default_timezone_set('America/Santiago');
                        $today = date("Y-m-d");
                        $stmt = $conn->query("SELECT * FROM event WHERE eventFinalDate >= " . "'" . $today . "'");
                        while ($row = $stmt->fetch()) {
                            getEventInfoSingle($conn, "eventImage", $eventId);
                            echo "
                            
                                <div class='col-xl-6'>
                                    <a href='tickets-vendidos?eventId=" . $row['eventId'] . "'>
                                    <div class='card mb-3'>
                                        <img class='card-img-top img-fluid' src='/al-uploads/events/covers/" . getEventInfoSingle($conn, "eventImage", $row['eventId']) . "' alt='" . $row['eventName'] . "'>
                                        <div class='card-header'>
                                            <h5 class='card-title'>" . $row['eventName'] . "</h5>
                                        </div>
                                        <div class='card-body'>
                                            <p class='card-text text-dark'>Comienza el " . $row['eventDate'] . "</p>
                                        </div>
                                    </div>
                                    </a>
                                </div>";
                        }
                    }
                    ?>
                </div>
                <?php if ($theValue=="TRUE") { ?>
                    <div class="d-flex flex-wrap mb-2 align-items-center justify-content-between">
                        <div class="mb-3 mr-3">
                            <h6 class="fs-16 text-black font-w600 mb-0">
                        </div>
                    </div>
                    <div class="row my-5"><!-- Lista de Entradas -->
                        <div class="col-xl align-center">
                            <h6>Entradas Vendidas</h6>
                            <h2><?php echo number_format(getSoldTickets($conn, $eventId),0,",","."); ?></h2>
                        </div>
                        <div class="col-xl">
                            <h6>Ingresos Generales</h6>
                            <h2>$<?php echo number_format(getSoldAmount($conn, $eventId),0,",","."); ?></h2>
                        </div>
                        <div class="col-xl">
                            <h6>Ingresos Totales</h6>
                            <h2>$<?php echo number_format(calculateIncomeWithDiscount(getSoldAmount($conn, $eventId)),0,",","."); ?></h2>
                        </div>
                    </div>
                    <div class="row"><!-- Lista de Entradas -->
                        <div class="col-xl-12">
                            <div class="tab-content">
                                <div id="All" class="tab-pane active fade show">
                                    <div class="table-responsive">
                                        <table id="example2" class="table card-table display dataTablesCard">
                                            <thead>
                                            <tr>
                                                <th>Nº Orden</th>
                                                <th>Nombre</th>
                                                <th>Rut</th>
                                                <th>Email</th>
                                                <th>Entradas</th>
                                                <th>Pago</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php getReportEvent($conn, $eventId); ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php } ?>
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