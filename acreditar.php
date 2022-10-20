<?php
session_start();
require('al-admin/core.php');
require('al-admin/functions.php');

$eventId = $_GET['eventId'];
$pageName = "Acreditar";
$theValue = "FALSE";

if (empty($_SESSION['userId'])) {
    header('Location: login');
}

if (!empty($eventId)) {
    $stmt = $conn->query("SELECT eventName FROM event WHERE eventId=" . $_GET['eventId']);
    while ($row = $stmt->fetch()) {
        $pageName = $row['eventName'];
        $theValue = "TRUE";
    }
} else {
    $theValue = "FALSE";
}

?>
<!DOCTYPE html>
<html lang="es">
    <?php include('al-includes/head.php'); ?>
        <body>
        <?php include('al-includes/preloader.php'); ?>
        <div id="main-wrapper">

            <?php include('al-includes/navbar.php'); ?>

            <div class="content-body">
                <div class="container-fluid">
                    <div class="row">
                        <?php
                            if ($theValue == "FALSE")
                            {
                                date_default_timezone_set('America/Santiago');
                                $today = date("Y-m-d");
                                $stmt = $conn->query("SELECT * FROM event WHERE eventFinalDate >= " . "'" . $today . "'");
                                while ($row = $stmt->fetch()) {
                                    echo "
                                        <div class='col-xl-6'>
                                            <a href='acreditar?eventId=" . $row['eventId'] . "'>
                                            <div class='card mb-3'>
                                                <img class='card-img-top img-fluid' src='https://eventos.iglesialab.com/al-uploads/events/covers/" . $row['eventImage'] . "' alt='" . $row['eventName'] . "'>
                                                <div class='card-header'>
                                                    <h5 class='card-title'>" . $row['eventName'] . "</h5>
                                                </div>
                                                <div class='card-body'>
                                                    <p class='card-text text-dark'>Comienza " . $row['eventDate'] . "</p>
                                                </div>
                                            </div>
                                            </a>
                                        </div>";
                                }
                            }
                        ?>
                    </div>
                    <?php if ($theValue == "TRUE") { ?>
                        <div class="d-flex flex-wrap mb-2 align-items-center justify-content-between">
                            <div class="mb-3 mr-3">
                                <h6 class="fs-16 text-black font-w600 mb-0">
                                    Entradas Totales</h6>
                                <span class="fs-14">Vendidas en este evento</span>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-xl-12">
                                <div class="tab-content">
                                    <div id="All" class="tab-pane active fade show">
                                        <div class="table-responsive">
                                            <table id="example2" class="table card-table display dataTablesCard">
                                                <thead>
                                                <tr>
                                                    <th>Nº Orden</th>
                                                    <th>Nombre y Apellido</th>
                                                    <th>Rut</th>
                                                    <th>Email</th>
                                                    <th>Nº Entradas</th>
                                                    <th></th>
                                                </tr>
                                                </thead>
                                                <tbody>
                                                    <?php displayTickets($conn, $eventId); ?>
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
        </div>

        <!--*** Scripts ***-->
        <?php include('al-includes/scripts-footer.php'); ?>
        <script src="./vendor/chart.js/Chart.bundle.min.js"></script>
        <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
        <script>
            (function ($) {
                var table = $('#example2').DataTable({
                    searching: false,
                    paging: true,
                    select: false,
                    //info: false,
                    lengthChange: false

                });
                var table = $('#example3').DataTable({
                    searching: false,
                    paging: true,
                    select: false,
                    //info: false,
                    lengthChange: false

                });
                var table = $('#example4').DataTable({
                    searching: false,
                    paging: true,
                    select: false,
                    //info: false,
                    lengthChange: false

                });
                var table = $('#example5').DataTable({
                    searching: false,
                    paging: true,
                    select: false,
                    //info: false,
                    lengthChange: false

                });
                $('#example tbody').on('click', 'tr', function () {
                    var data = table.row(this).data();

                });
            })(jQuery);
        </script>
    </body>
</html>