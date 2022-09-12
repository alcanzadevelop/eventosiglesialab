<?php

    session_start();
    include 'al-admin/core.php';
    require('al-admin/functions.php');
    $message = "";

    if (!empty($_POST['name']) && !empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['userOrganization'])) {
        $name=filter_var($_POST['name'], FILTER_SANITIZE_STRING);
        $lastName=filter_var($_POST['lastName'], FILTER_SANITIZE_STRING);
        $email=filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $pass=md5($_POST['password']);
        $userOrganization=filter_var($_POST['userOrganization'], FILTER_SANITIZE_STRING);

        if(createCreditor($conn, $name, $lastName, $email, $pass, $userOrganization, $_SESSION['organizationId'])){
            $message = "<h4 class='text-center mb-4 text-white'>El usuario ha sido creado exitosamente.</h4>";
        }
        else{
            $message = "<h4 class='text-center mb-4 text-white'>Ha ocurrido un error. El usuario ya existe en tu organización.</h4>";
        }

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
                <div class="page-titles">
					<ol class="breadcrumb">
						<li class="breadcrumb-item"><a href="javascript:void(0)">Acreditación</a></li>
						<li class="breadcrumb-item active"><a href="javascript:void(0)">Equipo</a></li>
					</ol>
                </div>
                <!-- row -->
                <div class="row">
                    <div class="col-lg-12">
                        <div class="authincation-content" style="margin: 0px 100px 0px 100px !important; ">
	                        <div class="row no-gutters">
	                            <div class="col-xl-12">
	                                <div class="auth-form">
	                                    <h4 class="text-center mb-4 text-white">Registra a un nuevo acreditador</h4>
                                        <?php echo $message; ?>
	                                    <form name="newCreditor" method="POST" action="">
	                                        <div class="form-group">
	                                            <label class="mb-1 text-white"><strong>Nombre</strong></label>
	                                            <input type="text" name="name" class="form-control" placeholder="Nombre" required>
	                                        </div>
                                            <div class="form-group">
	                                            <label class="mb-1 text-white"><strong>Apellido</strong></label>
	                                            <input type="text" name="lastName" class="form-control" placeholder="Apellido" required>
	                                        </div>
	                                        <div class="form-group">
	                                            <label class="mb-1 text-white"><strong>Email</strong></label>
	                                            <input type="email" name="email" class="form-control" placeholder="hola@iglesialab.com" required>
	                                        </div>
	                                        <div class="form-group">
	                                            <label class="mb-1 text-white"><strong>Contraseña</strong></label>
	                                            <input type="password" name="password" class="form-control" value="Contraseña" required>
	                                        </div>
	                                        <div class="form-group">
	                                            <label class="mb-1 text-white"><strong>Organización | Área</strong></label>
	                                            <input type="text" name="userOrganization" class="form-control" placeholder="Organización o Área" required>
	                                        </div>
	                                        <div class="text-center mt-4">
	                                            <button type="submit" class="btn bg-white text-primary btn-block">Registrar</button>
	                                        </div>
	                                    </form>
	                                </div>
	                            </div>
	                        </div>
	                    </div>
                    </div>
                </div>
            </div>
        </div>
        <!--*** Content body end ***-->
        
    </div>
    <!--*** Main wrapper end ***-->
        
    <!--*** Scripts ***-->
    <?php include('al-includes/scripts-footer.php'); ?>
	
</body>
</html>