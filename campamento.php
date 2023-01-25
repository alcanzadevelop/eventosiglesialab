<?php

session_start();
require_once "vendor/autoload.php";

use GuzzleHttp\Client;

require('al-admin/core.php');
require('al-admin/functions.php');


?>
<!DOCTYPE html>
<html lang="es">
<?php include('al-includes/head.php');?>

<body>

<!--*** Preloader ***-->
<?php include('al-includes/preloader.php');?>

        <!--*** Content body start ***-->
        <div class="">
            <!-- row -->
            <div class="container-fluid">
                <?php getAttendeesByEventId($conn, 4); ?>
            </div>
        </div>
        <!--*** Content body end ***-->

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