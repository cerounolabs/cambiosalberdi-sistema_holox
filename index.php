<?php
  session_start();

  unset($_SESSION['Sys00']);
  unset($_SESSION['Sys01']);
  unset($_SESSION['Sys02']);
  unset($_SESSION['Sys03']);

  session_destroy();
?>

<!DOCTYPE html>
<html lang="es">

<head>
  <!-- Required meta tags -->
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
  <title>Sistema HOLOX</title>
  <!-- plugins:css -->
  <link rel="stylesheet" href="vendors/iconfonts/mdi/css/materialdesignicons.min.css">
  <link rel="stylesheet" href="vendors/css/vendor.bundle.base.css">
  <!-- endinject -->
  <!-- inject:css -->
  <link rel="stylesheet" href="css/style.css">
  <!-- endinject -->
  <link rel="shortcut icon" href="images/favicon.png" />
</head>

<body>
  <div class="container-scroller">
    <div class="container-fluid page-body-wrapper full-page-wrapper">
      <div class="content-wrapper d-flex align-items-center auth">
        <div class="row w-100">
          <div class="col-lg-4 mx-auto">
            <div class="auth-form-light text-left p-5">
              <h1 style="text-align:center;">Cambios Alberdi S.A.</h1>
              <h3 style="text-align:center;">Bienvenido al Sistema HOLOX</h3>
              <form class="pt-3" action="class/login.php" method="post">
                <div class="form-group">
                  <input type="text" id="userLogin" name="userLogin" class="form-control form-control-lg" style="text-transform:uppercase;" placeholder="Usuario">
                </div>
                <div class="form-group">
                  <input type="password" id="passLogin" name="passLogin" class="form-control form-control-lg" placeholder="Contrase&ntilde;a">
                </div>
                <div class="mt-3">
                  <input type="submit" class="btn btn-block btn-gradient-success btn-lg font-weight-medium auth-form-btn" value="INGRESAR">
                </div>
              </form>
            </div>
          </div>
        </div>
      </div>
      <!-- content-wrapper ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->
  <!-- plugins:js -->
  <script src=vendors/js/vendor.bundle.base.js"></script>
  <script src="vendors/js/vendor.bundle.addons.js"></script>
  <!-- endinject -->
  <!-- inject:js -->
  <script src="js/off-canvas.js"></script>
  <script src="js/misc.js"></script>
  <!-- endinject -->
</body>

</html>
