<?php
  include 'incl/sucursal.php';
  $wFecha = date('Y/m/d');
?>
<!DOCTYPE html>
<html lang="es">
  <head>
<?php
  include 'incl/head.php';
?>

  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper">
<?php
  include 'incl/menu.php';
?>

      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="row">
<?php
  $wSQL01	= ibase_query("SELECT sc.DESCRIPCION, sc.ANTERIOR, sc.COMPRA, sc.VENTA, sc.ACTUAL, sc.ID_COTIZACIONMONEDA
                          FROM SALDOSCONTABLES sc
                          WHERE sc.FECHAULTIMOMOVIMIENTO = '$wFecha' AND sc.MOSTRAR = 'S'
                          ORDER BY 1");
  while ($row01 = ibase_fetch_row($wSQL01)) {
    switch ($row01[5]) {
      case 1:
        $titEstilo = 'card bg-gradient-info card-img-holder text-white';
        $titSigno  = '$';
        break;
      case 2:
        $titEstilo = 'card bg-gradient-primary card-img-holder text-white';
        $titSigno  = 'R$';
        break;
      case 7:
        $titEstilo = 'card bg-gradient-danger card-img-holder text-white';
        $titSigno  = '₲';
        break;
      case 10:
        $titEstilo = 'card bg-gradient-success card-img-holder text-white';
        $titSigno  = 'P$';
        break;
    }

?>
            <div class="col-md-3 stretch-card grid-margin">
              <div class="<?php echo $titEstilo; ?>">
                <div class="card-body" style="padding: 1.25rem !important;">
                  <img src="images/dashboard/circle.svg" class="card-img-absolute" alt="circle-image"/>
                  <h3 class="font-weight-normal mb-3">TOTAL <?php echo $row01[0]; ?></h3>
                  <h1 class="mb-5" style="margin-bottom: 0rem !important;">
                    ANT:&nbsp;&nbsp;<?php echo $titSigno; ?> <?php echo number_format($row01[1], 0, '', '.'); ?>
                    <br/><br/>
                    COM:&nbsp;<?php echo $titSigno; ?> <?php echo number_format($row01[2], 0, '', '.'); ?>
                    <br/><br/>
                    VEN:&nbsp;&nbsp;<?php echo $titSigno; ?> <?php echo number_format($row01[3], 0, '', '.'); ?>
                    <br/><br/>
                    SAL:&nbsp;&nbsp;&nbsp;<?php echo $titSigno; ?> <?php echo number_format($row01[4], 0, '', '.'); ?>
                  </h1>
                </div>
              </div>
            </div>
<?php
  }
?>
          </div>
        </div>
        <!-- content-wrapper ends -->
        <!-- partial:partials/_footer.html -->
        <footer class="footer">
          <div class="d-sm-flex justify-content-center justify-content-sm-between">
            <span class="text-muted text-center text-sm-left d-block d-sm-inline-block">Copyright © 2017 <a href="https://www.bootstrapdash.com/" target="_blank">Bootstrap Dash</a>. All rights reserved.</span>
            <span class="float-none float-sm-right d-block mt-1 mt-sm-0 text-center">Hand-crafted & made with <i class="mdi mdi-heart text-danger"></i></span>
          </div>
        </footer>
        <!-- partial -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

<?php
  include 'incl/footer.php';
?>
</body>

</html>
