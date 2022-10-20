<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<?php require('al-admin/core.php');

    $eventId=$_GET['eventId'];

    function getTransactionDate($conn, $ticketId)
    {
        $stmt = $conn->query("SELECT transactionDate FROM transaction WHERE ticketId=" . $ticketId);
        $transaction = "";
        while ($row = $stmt->fetch()) {
            $transaction = $row['transactionDate'];
        }
        return $transaction;
    }

    function getNumberSold($conn, $eventId)
    {
        $sql = "SELECT count(*) FROM `ticket` WHERE eventId=" . $eventId . " AND ticketState='ACCREDITED'";
        $result = $conn->prepare($sql);
        $result->execute([$bar]);
        $number_of_rows = $result->fetchColumn();
        echo $number_of_rows;
    }

    $pageName="Acreditados";
    $theValue="FALSE";
    if(!empty($_GET['eventId'])) {
        $stmt = $conn->query("SELECT eventName FROM event WHERE eventId=" . $_GET['eventId']);
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
                <!-- Add Order -->
                <div class="modal fade" id="addOrderModalside">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <h5 class="modal-title">Add Event</h5>
                                <button type="button" class="close" data-dismiss="modal"><span>&times;</span>
                                </button>
                            </div>
                            <div class="modal-body">
                                <form>
                                    <div class="form-group">
                                        <label class="text-black font-w500">Event Name</label>
                                        <input type="text" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="text-black font-w500">Event Date</label>
                                        <input type="date" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <label class="text-black font-w500">Description</label>
                                        <input type="text" class="form-control">
                                    </div>
                                    <div class="form-group">
                                        <button type="button" class="btn btn-primary">Create</button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row">
                <?php if ($theValue=="FALSE") {
                    date_default_timezone_set('America/Santiago');
                    $today = date("Y-m-d");
                    $stmt = $conn->query("SELECT * FROM event");
                    while ($row = $stmt->fetch()) {
                        if(empty($row['eventName'])){
                            $image="./images/card/1.png";
                        }
                        else{
                            $image="https://eventos.iglesialab.com/al-uploads/events/covers/".$row['eventImage'];
                        }
                        echo "
                        
                            <div class='col-xl-6'>
                                <a href='acreditados?eventId=" . $row['eventId'] . "'>
                                <div class='card mb-3'>
                                    <img class='card-img-top img-fluid' src='".$image."' alt='" . $row['eventName'] . "'>
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

                    ?>

                <?php } ?>
                </div>
                <?php if ($theValue=="TRUE") { ?>
                    <div class="d-flex flex-wrap mb-2 align-items-center justify-content-between">
                        <div class="mb-3 mr-3">
                            <h6 class="fs-16 text-black font-w600 mb-0"><?php getNumberSold($conn, $_GET['eventId']); ?>
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
                                                <th>Nº Orden</th>
                                                <th>Nombre</th>
                                                <th>Rut</th>
                                                <th>Email</th>
                                                <th>Estado</th>
                                            </tr>
                                            </thead>
                                            <tbody>
                                            <?php



                                            $stmt = $conn->query("SELECT * FROM ticket WHERE eventId=" . $_GET['eventId'] . " AND ticketState='ACCREDITED'");
                                            while ($row = $stmt->fetch()) {
                                                echo "<tr>";
                                                echo "<td>#00" . $row['personId'] . "</td>";
                                                echo "<td>" . getTransactionDate($conn, $row['ticketId']) . "</td>";
                                                $stmtx = $conn->query("SELECT * FROM person WHERE personId=" . $row['personId']);
                                                while ($rowx = $stmtx->fetch()) {
                                                    echo "<td><span class='text-nowrap'>" . $rowx['personName'] . "</span></td>";
                                                    echo "<td>" . $rowx['personRut'] . "</td>";
                                                    echo "<td>" . $rowx['personEmail'] . "</td>";
                                                }
                                                echo "<td>ACREDITADO(A)";
                                                echo "</td>";
                                                echo "</tr>";
                                            }

                                            ?>
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