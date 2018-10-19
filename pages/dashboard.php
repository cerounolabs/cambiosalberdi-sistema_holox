<?php
  session_start();
  $idSession  = $_SESSION['Sys00'];
  $wFecha     = date('Y/m/d');
  $tFecha     = date('d/m/Y');
  $wHora      = date('H:i:s');

  if (!isset($idSession) || ($wHora > date('18:00:00'))) {
    unset($idSession);
    header('Location: ../class/logout.php');
  }

  include '../class/sucursal.php';
  include '../class/function.php';
  include '../class/query.php';
?>

<!DOCTYPE html>
<html lang="es">
  <head>
<?php
  include '../incl/head.php';
?>

  </head>
  <body>
    <div class="container-scroller">
      <div class="container-fluid page-body-wrapper">
<?php
  include '../incl/menu.php';
?>

      <!-- partial -->
      <div class="main-panel">
        <div class="content-wrapper">
          <div class="page-header">
            <h1 class="page-title" style="font-size:2.19rem !important;">
              <span class="page-title-icon bg-gradient-primary text-white mr-2">
                <i class="mdi mdi-home"></i>                 
              </span>
<?php
  echo $nomSucursal;
?>
              
            </h1>
          </div>

          <div class="row">
<?php
  $wSQL01	= ibase_query("SELECT sc.DESCRIPCION, sc.ANTERIOR, sc.COMPRA, sc.VENTA, sc.ACTUAL, sc.ID_COTIZACIONMONEDA
                          FROM SALDOSCONTABLES sc
                          WHERE sc.FECHAULTIMOMOVIMIENTO = '$wFecha' AND sc.MOSTRAR = 'S'
                          ORDER BY 1", $db);

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
                  <img src="../images/dashboard/circle.svg" class="card-img-absolute" alt="../..circle-image"/>
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
  ibase_free_result($wSQL01);
?>
          </div>

          <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Cantidad Boleta x Tipo Transacci&oacute;n <?php echo $tFecha; ?></h4>
                  <canvas id="boletaTipoTransaccion" style="height:230px"></canvas>
                </div>
              </div>
            </div>
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Boleta x Estado <?php echo $tFecha; ?></h4>
                  <canvas id="boletaEstado" style="height:250px"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Cotizaci&oacute;n D&oacute;lar <?php echo $tFecha; ?></h4>
                  <canvas id="historicoDolar" style="height:250px"></canvas>
                </div>
              </div>
            </div>
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Cotizaci&oacute;n Real <?php echo $tFecha; ?></h4>
                  <canvas id="historicoReal" style="height:250px"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Cotizaci&oacute;n Peso <?php echo $tFecha; ?></h4>
                  <canvas id="historicoPeso" style="height:250px"></canvas>
                </div>
              </div>
            </div>
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Cotizaci&oacute;n Euro <?php echo $tFecha; ?></h4>
                  <canvas id="historicoEuro" style="height:250px"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Cotizaci&oacute;n D&oacute;lar x Real <?php echo $tFecha; ?></h4>
                  <canvas id="historicoDolarxReal" style="height:250px"></canvas>
                </div>
              </div>
            </div>
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Cotizaci&oacute;n D&oacute;lar x Peso <?php echo $tFecha; ?></h4>
                  <canvas id="historicoDolarxPeso" style="height:250px"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Operaci&oacute;n Compra D&oacute;lar BB <?php echo $tFecha; ?></h4>
                  <canvas id="operacionDolarCompra" style="height:250px"></canvas>
                </div>
              </div>
            </div>
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Operaci&oacute;n Venta D&oacute;lar BB <?php echo $tFecha; ?></h4>
                  <canvas id="operacionDolarVenta" style="height:250px"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Operaci&oacute;n Compra Real BB <?php echo $tFecha; ?></h4>
                  <canvas id="operacionRealCompra" style="height:250px"></canvas>
                </div>
              </div>
            </div>
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Operaci&oacute;n Venta Real BB <?php echo $tFecha; ?></h4>
                  <canvas id="operacionRealVenta" style="height:250px"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Operaci&oacute;n Real x D&oacute;lar BB <?php echo $tFecha; ?></h4>
                  <canvas id="operacionRealxDolarCompra" style="height:250px"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-12 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Operaci&oacute;n D&oacute;lar x Real BB <?php echo $tFecha; ?></h4>
                  <canvas id="operacionRealxDolarVenta" style="height:250px"></canvas>
                </div>
              </div>
            </div>
          </div>

        </div>
        <!-- content-wrapper ends -->
      </div>
      <!-- main-panel ends -->
    </div>
    <!-- page-body-wrapper ends -->
  </div>
  <!-- container-scroller -->

<?php
  include '../incl/footer.php'; 
?>
    <script>
      $(function() {
        'use strict';
/*-----------------------------------------------------------------*/
        var dataBoletaTipoTransaccion = {
          labels: ["Compra", "Venta", "Arbitraje", "Canje", "Asiento"],
          datasets: [{
            label: '# Total ',
            data: [<?php echo $operTotal; ?>],
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)',
              'rgba(75, 192, 192, 0.2)',
              'rgba(153, 102, 255, 0.2)'
            ],
            borderColor: [
              'rgba(255, 99, 132, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)',
              'rgba(75, 192, 192, 1)',
              'rgba(153, 102, 255, 1)'
            ],
            borderWidth: 1,
            fill: false
          }]
        };

        var optionsBoletaTipoTransaccion = {
          scales: {
            yAxes: [{
              ticks: {
                beginAtZero: true
              }
            }]
          },
          legend: {
            display: false
          },
          elements: {
            point: {
              radius: 0
            }
          }
        };

        if ($("#boletaTipoTransaccion").length) {
          var barChartCanvas = $("#boletaTipoTransaccion").get(0).getContext("2d");
          var barChart = new Chart(barChartCanvas, {
            type: 'bar',
            data: dataBoletaTipoTransaccion,
            options: optionsBoletaTipoTransaccion
          });
        }
/*-----------------------------------------------------------------*/
        var dataBoletaEstado = {
          datasets: [{
            data: [<?php echo $estBoletaTit02; ?>],
            backgroundColor: [
              'rgba(255, 99, 132, 0.2)',
              'rgba(54, 162, 235, 0.2)',
              'rgba(255, 206, 86, 0.2)'
            ],
            borderColor: [
              'rgba(255, 99, 132, 1)',
              'rgba(54, 162, 235, 1)',
              'rgba(255, 206, 86, 1)'
            ],
          }],
          labels: [
            'Anulados: <?php echo $estBoletaAnu02; ?>',
            'Liquidados: <?php echo $estBoletaLiq02; ?>',
            'Pendientes: <?php echo $estBoletaPen02; ?>',
          ]
        };

        var optionsBoletaEstado = {
          responsive: true,
          animation: {
            animateScale: true,
            animateRotate: true
          }
        };

        if ($("#boletaEstado").length) {
          var pieChartCanvas = $("#boletaEstado").get(0).getContext("2d");
          var pieChart = new Chart(pieChartCanvas, {
            type: 'pie',
            data: dataBoletaEstado,
            options: optionsBoletaEstado
          });
        }
/*-----------------------------------------------------------------*/
        var dataHistoricoDolar = {
          labels: [<?php echo $hisDolarTitBB04; ?>],
          datasets: [{
              label: 'Compra BB',
              data: [<?php echo $hisDolarComBB04; ?>],
              backgroundColor: [
                'rgba(255, 99, 132, 0.5)'
              ],
              borderColor: [
                'rgba(255, 99, 132, 0.5)'
              ],
              borderWidth: 1,
              fill: true
            },
            {
              label: 'Venta BB',
              data: [<?php echo $hisDolarVenBB04; ?>],
              backgroundColor: [
                'rgba(54, 162, 235, 0.2)'
              ],
              borderColor: [
                'rgba(54, 162, 235, 1)'
              ],
              borderWidth: 1,
              fill: true
            }
          ]
        };

        var optionsHistoricoDolar = {
          plugins: {
            filler: {
              propagate: true
            }
          },
          scales: {
            xAxes: [{
              gridLines: {
                display: true
              }
            }],
            yAxes: [{
              gridLines: {
                display: true
              },
              ticks: {
                min: <?php echo ($minDolarBB04 - 20); ?>,
                max: <?php echo ($maxDolarBB04 + 20); ?>
              }
            }]
          }
        };

        if ($("#historicoDolar").length) {
          var areaChartCanvas = $("#historicoDolar").get(0).getContext("2d");
          var areaChart = new Chart(areaChartCanvas, {
            type: 'line',
            data: dataHistoricoDolar,
            options: optionsHistoricoDolar
          });
        }
/*-----------------------------------------------------------------*/
        var dataHistoricoReal = {
          labels: [<?php echo $hisRealTitBB04; ?>],
          datasets: [{
              label: 'Compra BB',
              data: [<?php echo $hisRealComBB04; ?>],
              backgroundColor: [
                'rgba(255, 99, 132, 0.5)'
              ],
              borderColor: [
                'rgba(255, 99, 132, 0.5)'
              ],
              borderWidth: 1,
              fill: true
            },
            {
              label: 'Venta BB',
              data: [<?php echo $hisRealVenBB04; ?>],
              backgroundColor: [
                'rgba(54, 162, 235, 0.2)'
              ],
              borderColor: [
                'rgba(54, 162, 235, 1)'
              ],
              borderWidth: 1,
              fill: true
            }
          ]
        };

        var optionsHistoricoReal = {
          plugins: {
            filler: {
              propagate: true
            }
          },
          scales: {
            xAxes: [{
              gridLines: {
                display: true
              }
            }],
            yAxes: [{
              gridLines: {
                display: true
              },
              ticks: {
                min: <?php echo ($minRealBB04 - 20); ?>,
                max: <?php echo ($maxRealBB04 + 20); ?>
              }
            }]
          }
        };

        if ($("#historicoReal").length) {
          var areaChartCanvas = $("#historicoReal").get(0).getContext("2d");
          var areaChart = new Chart(areaChartCanvas, {
            type: 'line',
            data: dataHistoricoReal,
            options: optionsHistoricoReal
          });
        }
/*-----------------------------------------------------------------*/
        var dataHistoricoPeso = {
          labels: [<?php echo $hisPesoTitBB04; ?>],
          datasets: [{
              label: 'Compra BB',
              data: [<?php echo $hisPesoComBB04; ?>],
              borderColor: [
                'rgba(255, 99, 132, 0.5)'
              ],
              backgroundColor: [
                'rgba(255, 99, 132, 0.5)'
              ],
              borderWidth: 1,
              fill: true
            },
            {
              label: 'Venta BB',
              data: [<?php echo $hisPesoVenBB04; ?>],
              backgroundColor: [
                'rgba(54, 162, 235, 0.2)'
              ],
              borderColor: [
                'rgba(54, 162, 235, 1)'
              ],
              borderWidth: 1,
              fill: true
            }
          ]
        };

        var optionsHistoricoPeso = {
          plugins: {
            filler: {
              propagate: true
            }
          },
          scales: {
            xAxes: [{
              gridLines: {
                display: true
              }
            }],
            yAxes: [{
              gridLines: {
                display: true
              },
              ticks: {
                min: <?php echo ($minPesoBB04 - 20); ?>,
                max: <?php echo ($maxPesoBB04 + 20); ?>
              }
            }]
          }
        };

        if ($("#historicoPeso").length) {
          var areaChartCanvas = $("#historicoPeso").get(0).getContext("2d");
          var areaChart = new Chart(areaChartCanvas, {
            type: 'line',
            data: dataHistoricoPeso,
            options: optionsHistoricoPeso
          });
        }
/*-----------------------------------------------------------------*/
        var dataHistoricoEuro = {
          labels: [<?php echo $hisEuroTitBB04; ?>],
          datasets: [{
              label: 'Compra BB',
              data: [<?php echo $hisEuroComBB04; ?>],
              backgroundColor: [
                'rgba(255, 99, 132, 0.5)'
              ],
              borderColor: [
                'rgba(255, 99, 132, 0.5)'
              ],
              borderWidth: 1,
              fill: true
            },
            {
              label: 'Venta BB',
              data: [<?php echo $hisEuroVenBB04; ?>],
              backgroundColor: [
                'rgba(54, 162, 235, 0.2)'
            ],
              borderColor: [
                'rgba(54, 162, 235, 1)'
            ],
              borderWidth: 1,
              fill: true
            }
          ]
        };

        var optionsHistoricoEuro = {
          plugins: {
            filler: {
              propagate: true
            }
          },
          scales: {
            xAxes: [{
              gridLines: {
                display: true
              }
            }],
            yAxes: [{
              gridLines: {
                display: true
              },
              ticks: {
                min: <?php echo ($minEuroBB04 - 20); ?>,
                max: <?php echo ($maxEuroBB04 + 20); ?>
              }
            }]
          }
        };

        if ($("#historicoEuro").length) {
          var areaChartCanvas = $("#historicoEuro").get(0).getContext("2d");
          var areaChart = new Chart(areaChartCanvas, {
            type: 'line',
            data: dataHistoricoEuro,
            options: optionsHistoricoEuro
          });
        }
/*-----------------------------------------------------------------*/
        var dataHistoricoDolarxReal = {
          labels: [<?php echo $hisDolarxRealTitBB06; ?>],
          datasets: [{
              label: 'Compra BB',
              data: [<?php echo $hisDolarxRealComBB06; ?>],
              backgroundColor: [
                'rgba(255, 99, 132, 0.5)'
            ],
              borderColor: [
                'rgba(255, 99, 132, 0.5)'
            ],
              borderWidth: 1,
              fill: true
            },
            {
              label: 'Venta BB',
              data: [<?php echo $hisDolarxRealVenBB06; ?>],
              backgroundColor: [
                'rgba(54, 162, 235, 0.2)'
            ],
              borderColor: [
                'rgba(54, 162, 235, 1)'
            ],
              borderWidth: 1,
              fill: true
            }
          ]
        };

        var optionsHistoricoDolarxReal = {
          plugins: {
            filler: {
              propagate: true
            }
          },
          scales: {
            xAxes: [{
              gridLines: {
                display: true
              }
            }],
            yAxes: [{
              gridLines: {
                display: true
              },
              ticks: {
                min: <?php echo ($minDolarxReal06 - 0.050); ?>,
                max: <?php echo ($maxDolarxReal06 + 0.050); ?>
              }
            }]
          }
        };

        if ($("#historicoDolarxReal").length) {
          var areaChartCanvas = $("#historicoDolarxReal").get(0).getContext("2d");
          var areaChart = new Chart(areaChartCanvas, {
            type: 'line',
            data: dataHistoricoDolarxReal,
            options: optionsHistoricoDolarxReal
          });
        }
/*-----------------------------------------------------------------*/
        var dataHistoricoDolarxPeso = {
          labels: [<?php echo $hisDolarxPesoTitBB06; ?>],
          datasets: [{
              label: 'Compra BB',
              data: [<?php echo $hisDolarxPesoComBB06; ?>],
              backgroundColor: [
                'rgba(255, 99, 132, 0.5)'
            ],
              borderColor: [
                'rgba(255, 99, 132, 0.5)'
            ],
              borderWidth: 1,
              fill: true
            },
            {
              label: 'Venta BB',
              data: [<?php echo $hisDolarxPesoVenBB06; ?>],
              backgroundColor: [
                'rgba(54, 162, 235, 0.2)'
            ],
              borderColor: [
                'rgba(54, 162, 235, 1)'
            ],
              borderWidth: 1,
              fill: true
            }
          ]
        };

        var optionsHistoricoDolarxPeso = {
          plugins: {
            filler: {
              propagate: true
            }
          },
          scales: {
            xAxes: [{
              gridLines: {
                display: true
              }
            }],
            yAxes: [{
              gridLines: {
                display: true
              },
              ticks: {
                min: <?php echo ($minDolarxPeso06 - 3); ?>,
                max: <?php echo ($maxDolarxPeso06 + 3); ?>
              }
            }]
          }
        };

        if ($("#historicoDolarxPeso").length) {
          var areaChartCanvas = $("#historicoDolarxPeso").get(0).getContext("2d");
          var areaChart = new Chart(areaChartCanvas, {
            type: 'line',
            data: dataHistoricoDolarxPeso,
            options: optionsHistoricoDolarxPeso
          });
        }
/*-----------------------------------------------------------------*/
        var dataHistoricoDolarxEuro = {
          labels: [<?php echo $hisDolarxEuroTitBB06; ?>],
          datasets: [{
              label: 'Compra BB',
              data: [<?php echo $hisDolarxEuroComBB06; ?>],
              backgroundColor: [
                'rgba(255, 99, 132, 0.5)'
            ],
              borderColor: [
                'rgba(255, 99, 132, 0.5)'
            ],
              borderWidth: 1,
              fill: true
            },
            {
              label: 'Venta BB',
              data: [<?php echo $hisDolarxEuroVenBB06; ?>],
              backgroundColor: [
                'rgba(54, 162, 235, 0.2)'
            ],
              borderColor: [
                'rgba(54, 162, 235, 1)'
            ],
              borderWidth: 1,
              fill: true
            }
          ]
        };

        var optionsHistoricoDolarxEuro = {
          plugins: {
            filler: {
              propagate: true
            }
          },
          scales: {
            xAxes: [{
              gridLines: {
                display: true
              }
            }],
            yAxes: [{
              gridLines: {
                display: true
              },
              ticks: {
                min: <?php echo ($minDolarxEuro06 - 0.050); ?>,
                max: <?php echo ($maxDolarxEuro06 + 0.050); ?>
              }
            }]
          }
        };

        if ($("#historicoDolarxEuro").length) {
          var areaChartCanvas = $("#historicoDolarxEuro").get(0).getContext("2d");
          var areaChart = new Chart(areaChartCanvas, {
            type: 'line',
            data: dataHistoricoDolarxEuro,
            options: optionsHistoricoDolarxEuro
          });
        }
/*-----------------------------------------------------------------*/
        var dataOperacionDolarComp = {
          labels: [<?php echo $opeDolarComBBTit05; ?>],
          datasets: [{
              label: 'Operación',
              data: [<?php echo $opeDolarComBBOpe05; ?>],
              backgroundColor: [
                'rgba(255, 99, 132, 0.2)'
              ],
              borderColor: [
                'rgba(255,99,132,1)'
              ],
              borderWidth: 2,
              fill: false
            },
            {
              label: 'Pizarra',
              data: [<?php echo $opeDolarComBBPiz05; ?>],
              backgroundColor: [
                'rgba(54, 162, 235, 0.2)'
              ],
              borderColor: [
                'rgba(54, 162, 235, 1)'
              ],
              borderWidth: 2,
              fill: false
            }
          ]
        };

        var optionsOperacionDolarComp = {
          plugins: {
            filler: {
              propagate: true
            }
          },
          scales: {
            xAxes: [{
              gridLines: {
                display: true
              }
            }],
            yAxes: [{
              gridLines: {
                display: true
              },
              ticks: {
                min: <?php echo ($minDolarComBB05 - 10); ?>,
                max: <?php echo ($maxDolarComBB05 + 10); ?>
              }
            }]
          }
        };

        if ($("#operacionDolarCompra").length) {
          var multiLineCanvas = $("#operacionDolarCompra").get(0).getContext("2d");
          var lineChart = new Chart(multiLineCanvas, {
            type: 'line',
            data: dataOperacionDolarComp,
            options: optionsOperacionDolarComp
          });
        }
/*-----------------------------------------------------------------*/
        var dataOperacionDolarVent = {
          labels: [<?php echo $opeDolarVenBBTit05; ?>],
          datasets: [{
              label: 'Operación',
              data: [<?php echo $opeDolarVenBBOpe05; ?>],
              backgroundColor: [
                'rgba(255, 99, 132, 0.2)'
              ],
              borderColor: [
                'rgba(255,99,132,1)'
              ],
              borderWidth: 2,
              fill: false
            },
            {
              label: 'Pizarra',
              data: [<?php echo $opeDolarVenBBPiz05; ?>],
              backgroundColor: [
                'rgba(54, 162, 235, 0.2)'
              ],
              borderColor: [
                'rgba(54, 162, 235, 1)'
              ],
              borderWidth: 2,
              fill: false
            }
          ]
        };

        var optionsOperacionDolarVent = {
          plugins: {
            filler: {
              propagate: true
            }
          },
          scales: {
            xAxes: [{
              gridLines: {
                display: true
              }
            }],
            yAxes: [{
              gridLines: {
                display: true
              },
              ticks: {
                min: <?php echo ($minDolarVenBB05 - 10); ?>,
                max: <?php echo ($maxDolarVenBB05 + 10); ?>
              }
            }]
          }
        };

        if ($("#operacionDolarVenta").length) {
          var multiLineCanvas = $("#operacionDolarVenta").get(0).getContext("2d");
          var lineChart = new Chart(multiLineCanvas, {
            type: 'line',
            data: dataOperacionDolarVent,
            options: optionsOperacionDolarVent
          });
        }
/*-----------------------------------------------------------------*/
        var dataOperacionRealComp = {
          labels: [<?php echo $opeRealComBBTit05; ?>],
          datasets: [{
              label: 'Operación',
              data: [<?php echo $opeRealComBBOpe05; ?>],
              backgroundColor: [
                'rgba(255, 99, 132, 0.2)'
              ],
              borderColor: [
                'rgba(255,99,132,1)'
              ],
              borderWidth: 2,
              fill: false
            },
            {
              label: 'Pizarra',
              data: [<?php echo $opeRealComBBPiz05; ?>],
              backgroundColor: [
                'rgba(54, 162, 235, 0.2)'
              ],
              borderColor: [
                'rgba(54, 162, 235, 1)'
              ],
              borderWidth: 2,
              fill: false
            }
          ]
        };

        var optionsOperacionRealComp = {
          plugins: {
            filler: {
              propagate: true
            }
          },
          scales: {
            xAxes: [{
              gridLines: {
                display: true
              }
            }],
            yAxes: [{
              gridLines: {
                display: true
              },
              ticks: {
                min: <?php echo ($minRealComBB05 - 10); ?>,
                max: <?php echo ($maxRealComBB05 + 10); ?>
              }
            }]
          }
        };

        if ($("#operacionRealCompra").length) {
          var multiLineCanvas = $("#operacionRealCompra").get(0).getContext("2d");
          var lineChart = new Chart(multiLineCanvas, {
            type: 'line',
            data: dataOperacionRealComp,
            options: optionsOperacionRealComp
          });
        }
/*-----------------------------------------------------------------*/
        var dataOperacionRealVent = {
          labels: [<?php echo $opeRealVenBBTit05; ?>],
          datasets: [{
              label: 'Operación',
              data: [<?php echo $opeRealVenBBOpe05; ?>],
              backgroundColor: [
                'rgba(255, 99, 132, 0.2)'
              ],
              borderColor: [
                'rgba(255,99,132,1)'
              ],
              borderWidth: 2,
              fill: false
            },
            {
              label: 'Pizarra',
              data: [<?php echo $opeRealVenBBPiz05; ?>],
              backgroundColor: [
                'rgba(54, 162, 235, 0.2)'
              ],
              borderColor: [
                'rgba(54, 162, 235, 1)'
              ],
              borderWidth: 2,
              fill: false
            }
          ]
        };

        var optionsOperacionRealVent = {
          plugins: {
            filler: {
              propagate: true
            }
          },
          scales: {
            xAxes: [{
              gridLines: {
                display: true
              }
            }],
            yAxes: [{
              gridLines: {
                display: true
              },
              ticks: {
                min: <?php echo ($minRealVenBB05 - 10); ?>,
                max: <?php echo ($maxRealVenBB05 + 10); ?>
              }
            }]
          }
        };

        if ($("#operacionRealVenta").length) {
          var multiLineCanvas = $("#operacionRealVenta").get(0).getContext("2d");
          var lineChart = new Chart(multiLineCanvas, {
            type: 'line',
            data: dataOperacionRealVent,
            options: optionsOperacionRealVent
          });
        }
/*-----------------------------------------------------------------*/
        var dataOperacionRealxDolarComp = {
          labels: [<?php echo $opeRealxDolarComBBTit07; ?>],
          datasets: [{
              label: 'Operación',
              data: [<?php echo $opeRealxDolarComBBOpe07; ?>],
              backgroundColor: [
                'rgba(255, 99, 132, 0.2)'
              ],
              borderColor: [
                'rgba(255,99,132,1)'
              ],
              borderWidth: 2,
              fill: false
            },
            {
              label: 'Pizarra',
              data: [<?php echo $opeRealxDolarComBBPiz07; ?>],
              backgroundColor: [
                'rgba(54, 162, 235, 0.2)'
              ],
              borderColor: [
                'rgba(54, 162, 235, 1)'
              ],
              borderWidth: 2,
              fill: false
            }
          ]
        };

        var optionsOperacionRealxDolarComp = {
          plugins: {
            filler: {
              propagate: true
            }
          },
          scales: {
            xAxes: [{
              gridLines: {
                display: true
              }
            }],
            yAxes: [{
              gridLines: {
                display: true
              },
              ticks: {
                min: <?php echo ($minRealxDolarComBB07 - 0.00100); ?>,
                max: <?php echo ($maxRealxDolarComBB07 + 0.00100); ?>
              }
            }]
          }
        };

        if ($("#operacionRealxDolarCompra").length) {
          var multiLineCanvas = $("#operacionRealxDolarCompra").get(0).getContext("2d");
          var lineChart = new Chart(multiLineCanvas, {
            type: 'line',
            data: dataOperacionRealxDolarComp,
            options: optionsOperacionRealxDolarComp
          });
        }
/*-----------------------------------------------------------------*/
        var dataOperacionRealxDolarVent = {
          labels: [<?php echo $opeRealxDolarVenBBTit07; ?>],
          datasets: [{
              label: 'Operación',
              data: [<?php echo $opeRealxDolarVenBBOpe07; ?>],
              backgroundColor: [
                'rgba(255, 99, 132, 0.2)'
              ],
              borderColor: [
                'rgba(255,99,132,1)'
              ],
              borderWidth: 2,
              fill: false
            },
            {
              label: 'Pizarra',
              data: [<?php echo $opeRealxDolarVenBBPiz07; ?>],
              backgroundColor: [
                'rgba(54, 162, 235, 0.2)'
              ],
              borderColor: [
                'rgba(54, 162, 235, 1)'
              ],
              borderWidth: 2,
              fill: false
            }
          ]
        };

        var optionsOperacionRealxDolarVent = {
          plugins: {
            filler: {
              propagate: true
            }
          },
          scales: {
            xAxes: [{
              gridLines: {
                display: true
              }
            }],
            yAxes: [{
              gridLines: {
                display: true
              },
              ticks: {
                min: <?php echo ($minRealxDolarVenBB07 - 0.00100); ?>,
                max: <?php echo ($maxRealxDolarVenBB07 + 0.00100); ?>
              }
            }]
          }
        };

        if ($("#operacionRealxDolarVenta").length) {
          var multiLineCanvas = $("#operacionRealxDolarVenta").get(0).getContext("2d");
          var lineChart = new Chart(multiLineCanvas, {
            type: 'line',
            data: dataOperacionRealxDolarVent,
            options: optionsOperacionRealxDolarVent
          });
        }
/*-----------------------------------------------------------------*/
      });
    </script>
  </body>
</html>

<?php
  ibase_close($db);
?>