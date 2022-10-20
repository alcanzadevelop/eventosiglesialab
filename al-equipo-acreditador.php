<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<?php

    require('al-admin/core.php');
    $pageName="Equipo Acreditador";

?>
<?php include('al-includes/head.php');?>

<body>
    <!--*** Preloader ***-->
    <?php include('al-includes/preloader.php'); ?>

    <!--*** Main wrapper start ***-->
    <div id="main-wrapper">

        <?php include('al-includes/navbar.php'); ?>

        <!--*** Content body start ***-->
        <div class="content-body">
            <!-- row -->
            <div class="container-fluid">
                <div class="d-flex flex-wrap mb-2 align-items-center justify-content-between">
                    <div class="mb-3 mr-3">
                        <h6 class="fs-16 text-black font-w600 mb-0">
                            Personas Acreditadas</h6>
                        <span class="fs-14">En este evento</span>
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
                                            <th>Nº Usuario</th>
                                            <th>Nombre</th>
                                            <th>Apellido</th>
                                            <th>Email</th>
                                            <th>Organización</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        <?php

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

                                        displayTeam($conn);

                                        ?>
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>

    <!--*** Scripts ***-->
    <?php include('al-includes/scripts-footer.php'); ?>
    <script src="./vendor/chart.js/Chart.bundle.min.js"></script>
    <script src="./vendor/datatables/js/jquery.dataTables.min.js"></script>
</body>

</html>