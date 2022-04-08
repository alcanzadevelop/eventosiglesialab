<?php session_start(); ?>
<!DOCTYPE html>
<html lang="es">
<?php require('al-admin/core.php'); ?>
<?php
if (empty($_SESSION['userId'])) {
    header('Location: login.php');
}
$pageName = "Mi Perfil";
$message = " ";
if (!empty($_POST['op']) && !empty($_POST['np'])) {
    $pass = md5($_POST['op']);
    $stmt = $conn->query("SELECT * FROM `user` WHERE userPassword='" . $pass . "' AND userId=" . $_SESSION['userId']);
    $theUser = "";
    while ($row = $stmt->fetch()) {
        $theUser = $row['userId'];
        if (!empty($theUser)) {
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $sql = "UPDATE user SET userPassword='" . md5($_POST['np']) . "' WHERE userId=" . $_SESSION['userId'];
            $stmt = $conn->prepare($sql);
            $stmt->execute();
            header("Location: app-profile.php?res=1");
        }
    }
    if (empty($theUser)) {
        $message = "<div class='alert alert-danger' role='alert'>Tu contraseña no coincide, intenta nuevamente.</div>";
    } else {
        $message = "<div class='alert alert-success' role='alert'>Tu contraseña ha sido cambiada exitosamente.</div>";
    }
}

if ($_GET['res'] == 1) {
    $message = "<div class='alert alert-success' role='alert'>Tu contraseña ha sido cambiada exitosamente.</div>";
}
?>
<?php include('al-includes/head.php'); ?>

<body>

<!--*** Preloader ***-->
<?php include('al-includes/preloader.php'); ?>

<!--*** Main wrapper start ***-->
<div id="main-wrapper">

    <?php include('al-includes/navbar.php'); ?>

    <!--*** Content body start ***-->
    <div class="content-body">
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
            <!-- row -->
            <div class="row">
                <div class="col-xl-12">
                    <div class="card">
                        <div class="card-body">
                            <div class="profile-tab">
                                <div class="custom-tab-1">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item">
                                            <a href="#profile-settings" data-toggle="tab" class="nav-link">Configuración</a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">
                                        <div id="profile-settings" class="tab-pane fade active show">
                                            <div class="pt-3">
                                                <div class="settings-form">
                                                    <h4 class="text-primary">Configuración de la Cuenta</h4>
                                                    <?php echo $message; ?>
                                                    <form name="fromUp" action="" method="POST">
                                                        <div class="form-row">
                                                            <div class="form-group col-md-6">
                                                                <label>Contraseña Antigua</label>
                                                                <input name="op" type="password"
                                                                       placeholder="Contraseña Antigua"
                                                                       class="form-control">
                                                            </div>
                                                            <div class="form-group col-md-6">
                                                                <label>Contraseña Nueva</label>
                                                                <input name="np" type="password"
                                                                       placeholder="Contraseña Nueva"
                                                                       class="form-control">
                                                            </div>
                                                        </div>
                                                        <button class="btn btn-primary" type="submit">Actualizar
                                                        </button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                <!-- Modal -->
                                <div class="modal fade" id="replyModal">
                                    <div class="modal-dialog modal-dialog-centered" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <h5 class="modal-title">Post Reply</h5>
                                                <button type="button" class="close" data-dismiss="modal">
                                                    <span>&times;</span></button>
                                            </div>
                                            <div class="modal-body">
                                                <form>
                                                    <textarea class="form-control" rows="4">Message</textarea>
                                                </form>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-danger light" data-dismiss="modal">
                                                    Close
                                                </button>
                                                <button type="button" class="btn btn-primary">Reply</button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!--**********************************
        Content body end
    ***********************************-->


</div>
<!--**********************************
    Main wrapper end
***********************************-->

<!--removeIf(production)-->

<!--**********************************
    Scripts
***********************************-->
<!-- Required vendors -->
<?php include('al-includes/scripts-footer.php'); ?>
<script src="./vendor/global/global.min.js"></script>
<script src="./vendor/bootstrap-select/dist/js/bootstrap-select.min.js"></script>
<script src="./js/custom.min.js"></script>
<script src="./js/deznav-init.js"></script>
<script src="./vendor/lightgallery/js/lightgallery-all.min.js"></script>
<script>
    $('#lightgallery').lightGallery({
        thumbnail: true,
    });
</script>

</body>

</html>