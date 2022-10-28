<?php

session_start();
require_once "vendor/autoload.php";

require('al-admin/core.php');
require('al-admin/functions.php');

if (empty($_SESSION['userId'])) {
    header('Location: login.php');
}

?>
<!DOCTYPE html>
<html lang="es">
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
            <!-- Events -->
            <div class="row">
                <div class="col-xl-12 col-xxl-12">
                    <div class="row">

                        <div class="col-xl-12">
                            <h4 class="fs-20">Reportes</h4>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Events Lists -->
            <div class="row">
                <div class="col">
                    <table class='table'>
                        <thead>
                        <tr>
                            <th scope='col'>ID</th>
                            <th scope='col'>Nombre</th>
                            <th scope='col'>Reporte</th>
                        </tr>
                        </thead>
                        <?php listEventsOrganizationForReport($conn, $_SESSION['organizationId']); ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--*** Content body end ***-->

</div>
<!--*** Main wrapper end ***-->

<!--*** Scripts ***-->
<script src="./vendor/global/global.min.js"></script>
<script src="./vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="./vendor/chart.js/Chart.bundle.min.js"></script>
<script src="./js/custom.min.js"></script>
<script src="./js/deznav-init.js"></script>
<script src="./vendor/owl-carousel/owl.carousel.js"></script>

<!-- Chart piety plugin files -->
<script src="./vendor/peity/jquery.peity.min.js"></script>

<!-- Apex Chart -->
<script src="./vendor/apexchart/apexchart.js"></script>

<!-- Dashboard 1 -->
<script src="./js/dashboard/dashboard-1.js"></script>

</body>

</html>