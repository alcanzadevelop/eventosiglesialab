<?php

    session_start();
    include 'al-admin/core.php';
    require('al-admin/functions.php');

    $message = "";

    if (!empty($_SESSION['userId'])) {
        header('Location: home');
    }

    if (!empty($_POST['email']) && !empty($_POST['password'])) {
        $email=filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
        $pass=md5($_POST['password']);
        if (login($conn, $email, $pass)) {
            if ($_SESSION['userState'] == "ACREDITADOR") {
                header('Location: acreditar');
            } else {
                header('Location: home');
            }
        } else {
            if (empty($_SESSION['userId'])) {
                $message = "<h4 class='text-center mb-4 text-white'>Ha ocurrido un error. Prueba nuevamente.</h4>";
            }
        }
    }

?>
<!DOCTYPE html>
<html lang="es" class="h-100">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <title>Eventos - Iglesia Lab</title>
    <!-- Favicon icon -->
    <link rel="icon" type="image/png" sizes="16x16" href="./images/favicon.png">
    <link href="./css/style.css" rel="stylesheet">
	<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@100;200;300;400;500;600;700;800;900&family=Roboto:wght@100;300;400;500;700;900&display=swap" rel="stylesheet">
</head>

<body class="h-100">
    <div class="authincation h-100">
        <div class="container h-100">
            <div class="row justify-content-center h-100 align-items-center">
                <div class="col-md-6">
                    <div class="authincation-content">
                        <div class="row no-gutters">
                            <div class="col-xl-12">
                                <div class="auth-form">
									<div class="text-center mb-3">
										<a href="index.html"><img src="images/logo-full.png" alt=""></a>
									</div>
                                    <h4 class="text-center mb-4 text-white">Ingresa a tu cuenta</h4>
                                    <?php echo $message; ?>
                                    <form action="" method="POST" name="login">
                                        <div class="form-group">
                                            <label class="mb-1 text-white"><strong>Email</strong></label>
                                            <input name="email" type="email" class="form-control" placeholder="hola@ejemplo.com" required>
                                        </div>
                                        <div class="form-group">
                                            <label class="mb-1 text-white"><strong>Contraseña</strong></label>
                                            <input name="password" type="password" class="form-control" placeholder="Contraseña" required>
                                        </div>
                                        <div class="form-row d-flex justify-content-between mt-4 mb-2">
                                            <div class="form-group">
                                               <div class="custom-control custom-checkbox ml-1 text-white">
													<input type="checkbox" class="custom-control-input" id="basic_checkbox_1">
													<label class="custom-control-label" for="basic_checkbox_1">Recordarme</label>
												</div>
                                            </div>
                                        </div>
                                        <div class="text-center">
                                            <button type="submit" class="btn bg-white text-primary btn-block">Ingresar</button>
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
    <!--**********************************
        Scripts
    ***********************************-->
    <!-- Required vendors -->
    <script src="./vendor/global/global.min.js"></script>
    <script src="./js/custom.min.js"></script>
    <script src="./js/deznav-init.js"></script>

</body>

</html>