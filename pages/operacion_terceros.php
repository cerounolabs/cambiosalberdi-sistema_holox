<?php
    session_start();
    $idSession  = $_SESSION['Sys00'];
    $tFecha     = date('d/m/Y');
    $wHora      = date('H:i:s');

    if (!isset($idSession) || ($wHora > date('19:00:00'))) {
        unset($idSession);
        header('Location: ../class/logout.php');
    }
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
                                OPERACI&Oacute;N DE TERCEROS
                            </h1>
                        </div>

                        <div class="row">
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <form class="forms-sample">
                                            <div class="form-group">
                                                <label for="fecConsulta"> FECHA </label>
                                                <input type="date" class="form-control" style="text-transform:uppercase;" id="fecConsulta" name="fecConsulta" placeholder="FECHA">
                                            </div>

                                            <a type="button" class="btn btn-gradient-primary btn-fw" style="float:right; margin-bottom: .75rem; background-color: rgba(172, 50, 228, 0.9); background-image: linear-gradient(to right, #da8cff, #9a55ff);" onclick="buscaOperacion()"> Consultar </a>
                                        </form>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <a type="button" class="btn btn-gradient-primary btn-fw" style="float:right; margin-bottom: .75rem; background-color: rgba(172, 50, 228, 0.9); background-image: linear-gradient(to right, #da8cff, #9a55ff);" href="#" onclick="exportToExcel('exTable')"><i class="mdi mdi-file-excel "></i> Exportar a Excell</a>
                                        <table id="exTable" class="table table-striped" border="1">
                                            <thead>
                                                <tr valign="middle">
                                                    <th style="text-align:center;"> ORDEN </th>
                                                    <th style="text-align:center;"> FECHA </th>
                                                    <th style="text-align:center;"> SUCURSAL </th>
                                                    <th style="text-align:center;"> BOLETA </th>
                                                    <th style="text-align:center;"> IMPORTE ME</th>
                                                    <th style="text-align:center;"> IMPORTE MN </th>
                                                    <th style="text-align:center;"> CODIGO </th>
                                                    <th style="text-align:center;"> DESCRIPCION </th>
                                                    <th style="text-align:center;"> OBSERVACION </th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
    if (isset($_GET['fecha'])) {
        $wFecha     = $_GET['fecha'];
        $item       = 0;
        $suc_array  = array(
            "CASA MATRIZ"                   => "192.168.0.200:aliadocambios",
            "SUCURSAL VILLA MORRA"          => "10.168.196.130:aliadocambios",
            "AGENCIA SAN LORENZO"           => "10.168.191.130:aliadocambios",
            "SUCURSAL CIUDAD DEL ESTE"      => "10.168.192.138:aliadocambios",
            "AGENCIA JEBAI"                 => "10.168.193.130:aliadocambios",
            "AGENCIA LAI LAI"               => "10.168.194.130:aliadocambios",
            "AGENCIA UNIAMERICA"            => "10.168.199.131:aliadocambios",
            "AGENCIA RUBIO ÑU"              => "10.168.195.130:aliadocambios",
            "AGENCIA KM4"                   => "10.168.190.130:aliadocambios",
            "SUCURSAL SALTO DEL GUAIRA"     => "10.168.198.130:aliadocambios",
            "AGENCIA SALTO DEL GUAIRA"      => "10.168.197.130:aliadocambios",
            "SUCURSAL ENCARNACION"          => "10.168.189.130:aliadocambios"
        );

        foreach($suc_array as $suc_key => $suc_ip) {
            $str_db         = $suc_ip;
            $str_user       = 'sysdba';
            $str_pass       = 'dorotea';
            $str_connect    = ibase_connect($str_db, $str_user, $str_pass) OR DIE("NO SE CONECTO AL SERVIDOR: ".ibase_errmsg());
            $wSQL00         = ibase_query("SELECT T.ID_SUCURSALAGENCIA, T.ID_TRANSACCION, T.FECHATRANSACCION, TD.IMPORTEME, TD.IMPORTEMN, SC.CODIGO, SC.DESCRIPCION, TD.OBSERVACIONES
                                            FROM TRANSACCIONES T
                                            LEFT JOIN TRANSACCIONESDETALLES TD ON (TD.ID_TRANSACCION = T.ID_TRANSACCION)
                                            LEFT JOIN SALDOSCONTABLES SC ON (SC.ID_SALDOCONTABLE = TD.ID_SALDOCONTABLE_CUENTAOP)
                                            WHERE T.ID_TIPOOPERACION <> '99' AND T.ESTADO = 'L' AND CAST(T.FECHATRANSACCION AS DATE) = '$wFecha' AND TD.ID_TIPOESPECIE = 6", $str_connect);
                                            
            $wSQL01         = ibase_query("SELECT T.ID_SUCURSALAGENCIA, T.ID_TRANSACCION, T.FECHATRANSACCION, TD.IMPORTEME, TD.IMPORTEMN, SC.CODIGO, SC.DESCRIPCION, TD.OBSERVACIONES
                                            FROM TRANSACCIONES T
                                            LEFT JOIN TRANSACCIONESDETALLES TD ON (TD.ID_TRANSACCION = T.ID_TRANSACCION)
                                            LEFT JOIN SALDOSCONTABLES SC ON (SC.ID_SALDOCONTABLE = TD.ID_SALDOCONTABLE_CUENTAOP)
                                            WHERE T.ID_TIPOOPERACION <> '99' AND T.ESTADO = 'L' AND CAST(T.FECHATRANSACCION AS DATE) = '$wFecha' AND TD.OBSERVACIONES containing 'MTC' AND SC.DESCRIPCION containing 'WEST'", $str_connect);
                                            
            $wSQL02         = ibase_query("SELECT T.ID_SUCURSALAGENCIA, T.ID_TRANSACCION, T.FECHATRANSACCION, TD.IMPORTEME, TD.IMPORTEMN, SC.CODIGO, SC.DESCRIPCION, TD.OBSERVACIONES
                                            FROM TRANSACCIONES T
                                            LEFT JOIN TRANSACCIONESDETALLES TD ON(TD.ID_TRANSACCION = T.ID_TRANSACCION)
                                            LEFT JOIN SALDOSCONTABLES SC ON (SC.ID_SALDOCONTABLE = TD.ID_SALDOCONTABLE_CUENTAOP)
                                            WHERE T.ID_TIPOOPERACION <> '99' AND T.ESTADO = 'L' AND  CAST(T.FECHATRANSACCION AS DATE) = '$wFecha' AND  SC.DESCRIPCION containing 'PRONET' AND  td.OP containing 'V' AND EXISTS (SELECT 1 FROM transaccionesdetalles TD2 WHERE TD2.ID_TRANSACCION = T.ID_TRANSACCION AND TD2.ID_TIPOESPECIE = 1 AND TD2.ID_TRANSACCIONDETALLE <> TD.ID_TRANSACCIONDETALLE)", $str_connect);
                                            
            $wSQL03         = ibase_query("SELECT T.ID_SUCURSALAGENCIA, T.ID_TRANSACCION, T.FECHATRANSACCION, TD.IMPORTEME, TD.IMPORTEMN, SC.CODIGO, SC.DESCRIPCION, TD.OBSERVACIONES
                                            FROM TRANSACCIONES T
                                            LEFT JOIN TRANSACCIONESDETALLES TD ON(TD.ID_TRANSACCION = T.ID_TRANSACCION)
                                            LEFT JOIN SALDOSCONTABLES SC ON (SC.ID_SALDOCONTABLE = TD.ID_SALDOCONTABLE_CUENTAOP)
                                            WHERE T.ID_TIPOOPERACION <> '99' AND T.ESTADO = 'L' AND CAST(T.FECHATRANSACCION AS DATE) = '$wFecha' AND  SC.DESCRIPCION containing 'NETEL' AND  td.OP containing 'V' AND EXISTS (SELECT 1 FROM transaccionesdetalles TD2 WHERE TD2.ID_TRANSACCION = T.ID_TRANSACCION AND TD2.ID_TIPOESPECIE = 1 AND TD2.ID_TRANSACCIONDETALLE <> TD.ID_TRANSACCIONDETALLE)", $str_connect);

            while ($row00 = ibase_fetch_row($wSQL00)) {
                $item       = $item + 1;
                $fecAlta    = substr($row00[2], 8, 2).'/'.substr($row00[2], 5, 2).'/'.substr($row00[2], 0, 4);
?>
                                                <tr>
                                                    <td style="text-align:right;">  <?php echo $item; ?> </td>
                                                    <td style="text-align:left;">   <?php echo $fecAlta; ?> </td>
                                                    <td style="text-align:left;">   <?php echo $suc_key; ?> </td>
                                                    <td style="text-align:left;">   <?php echo $row00[1]; ?> </td>
                                                    <td style="text-align:right;">  <?php echo number_format($row00[3], 2, ',', '.'); ?> </td>
                                                    <td style="text-align:right;">  <?php echo number_format($row00[4], 0, ',', '.'); ?> </td>
                                                    <td style="text-align:left;">   <?php echo $row00[5]; ?> </td>                                                    
                                                    <td style="text-align:left;">   <?php echo $row00[6]; ?>  </td>
                                                    <td style="text-align:left;">   <?php echo $row00[7]; ?>  </td>
                                                </tr>
<?php
            }
            ibase_free_result($wSQL00);

            while ($row01 = ibase_fetch_row($wSQL01)) {
                $item       = $item + 1;
                $fecAlta    = substr($row01[2], 8, 2).'/'.substr($row01[2], 5, 2).'/'.substr($row01[2], 0, 4);
?>
                                                <tr>
                                                    <td style="text-align:right;">  <?php echo $item; ?> </td>
                                                    <td style="text-align:left;">   <?php echo $fecAlta; ?> </td>
                                                    <td style="text-align:left;">   <?php echo $suc_key; ?> </td>
                                                    <td style="text-align:left;">   <?php echo $row01[1]; ?> </td>
                                                    <td style="text-align:right;">  <?php echo number_format($row01[3], 2, ',', '.'); ?> </td>
                                                    <td style="text-align:right;">  <?php echo number_format($row01[4], 0, ',', '.'); ?> </td>
                                                    <td style="text-align:left;">   <?php echo $row01[5]; ?> </td>                                                    
                                                    <td style="text-align:left;">   <?php echo $row01[6]; ?>  </td>
                                                    <td style="text-align:left;">   <?php echo $row01[7]; ?>  </td>
                                                </tr>
<?php
            }
            ibase_free_result($wSQL01);

            while ($row02 = ibase_fetch_row($wSQL02)) {
                $item       = $item + 1;
                $fecAlta    = substr($row02[2], 8, 2).'/'.substr($row02[2], 5, 2).'/'.substr($row02[2], 0, 4);
?>
                                                <tr>
                                                    <td style="text-align:right;">  <?php echo $item; ?> </td>
                                                    <td style="text-align:left;">   <?php echo $fecAlta; ?> </td>
                                                    <td style="text-align:left;">   <?php echo $suc_key; ?> </td>
                                                    <td style="text-align:left;">   <?php echo $row02[1]; ?> </td>
                                                    <td style="text-align:right;">  <?php echo number_format($row02[3], 2, ',', '.'); ?> </td>
                                                    <td style="text-align:right;">  <?php echo number_format($row02[4], 0, ',', '.'); ?> </td>
                                                    <td style="text-align:left;">   <?php echo $row02[5]; ?> </td>                                                    
                                                    <td style="text-align:left;">   <?php echo $row02[6]; ?>  </td>
                                                    <td style="text-align:left;">   <?php echo $row02[7]; ?>  </td>
                                                </tr>
<?php
            }
            ibase_free_result($wSQL02);

            while ($row03 = ibase_fetch_row($wSQL03)) {
                $item       = $item + 1;
                $fecAlta    = substr($row03[2], 8, 2).'/'.substr($row03[2], 5, 2).'/'.substr($row03[2], 0, 4);
?>
                                                <tr>
                                                    <td style="text-align:right;">  <?php echo $item; ?> </td>
                                                    <td style="text-align:left;">   <?php echo $fecAlta; ?> </td>
                                                    <td style="text-align:left;">   <?php echo $suc_key; ?> </td>
                                                    <td style="text-align:left;">   <?php echo $row03[1]; ?> </td>
                                                    <td style="text-align:right;">  <?php echo number_format($row03[3], 2, ',', '.'); ?> </td>
                                                    <td style="text-align:right;">  <?php echo number_format($row03[4], 0, ',', '.'); ?> </td>
                                                    <td style="text-align:left;">   <?php echo $row03[5]; ?> </td>                                                    
                                                    <td style="text-align:left;">   <?php echo $row03[6]; ?>  </td>
                                                    <td style="text-align:left;">   <?php echo $row03[7]; ?>  </td>
                                                </tr>
<?php
            }
            ibase_free_result($wSQL03);
?>
                                                <tr valign="middle">
                                                    <td colspan="9" style="text-align:center;">  </td>
                                                </tr>
<?php
        }
    }
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

        <?php  include '../incl/footer.php'; ?>
        <script>
            function buscaOperacion() {
                var fecConsulta = document.getElementById("fecConsulta").value;
                var urlGET      = "";

                if (fecConsulta !== null && fecConsulta !== '') {
                    urlGET = "http://10.168.196.152/sistema/pages/operacion_terceros.php?fecha=" + fecConsulta;
                } else {
                    urlGET = "http://10.168.196.152/sistema/pages/operacion_terceros.php";
                }
                
                window.location.href    = urlGET;
            }

            function exportToExcel(tableID){
                var tab_text    ="<table border='2px'><tr bgcolor='#87AFC6' style='height: 75px; text-align: center; width: 250px'>";
                var textRange   = 0; 
                var j           = 0;
                tab             = document.getElementById(tableID);

                for(j = 0 ; j < tab.rows.length ; j++) {
                    tab_text = tab_text;
                    tab_text = tab_text+tab.rows[j].innerHTML.toUpperCase() + "</tr>";
                }

                tab_text    = tab_text + "</table>";
                tab_text    = tab_text.replace(/<A[^>]*>|<\/A>/g, "");
                tab_text    = tab_text.replace(/<img[^>]*>/gi,"");
                tab_text    = tab_text.replace(/<input[^>]*>|<\/input>/gi, "");
                
                var ua      = window.navigator.userAgent;
                var msie    = ua.indexOf("MSIE ");
                
                if (msie > 0 || !!navigator.userAgent.match(/Trident.*rv\:11\./)) {
                    txtArea1.document.open("txt/html","replace");
                    txtArea1.document.write('sep=,\r\n' + tab_text);
                    txtArea1.document.close();
                    txtArea1.focus();
                    sa = txtArea1.document.execCommand("SaveAs",true,"sudhir123.txt");
                }
                else {
                    sa = window.open('data:application/vnd.ms-excel,' + encodeURIComponent(tab_text));
                }
                
                return (sa);
            }

            $(document).keypress(function (e) {
                if (e.which == 13) {
                    buscaOperacion();
                }
            });
        </script>
    </body>
</html>

<?php
  ibase_close($str_connect);
?>