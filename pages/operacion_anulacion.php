<?php
    session_start();
    $idSession  = $_SESSION['Sys00'];
    $wFecha     = date('Y/m/d');
    $tFecha     = date('d/m/Y');
    $wHora      = date('H:i:s');
/*
    $wFecha     = '2019/11/07';
    $tFecha     = '07/11/2019';
*/
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
                                OPERACIONES ANULADAS
                            </h1>
                        </div>
                        
                        <div class="row">
                            <div class="col-lg-12 grid-margin stretch-card">
                                <div class="card">
                                    <div class="card-body">
                                        <a type="button" class="btn btn-gradient-primary btn-fw" style="float:right; margin-bottom: .75rem; background-color: rgba(172, 50, 228, 0.9); background-image: linear-gradient(to right, #da8cff, #9a55ff);" href="#" onclick="exportToExcel('exTable')"><i class="mdi mdi-file-excel "></i> Exportar a Excell</a>
                                        <table id="exTable" class="table table-striped" border="1">
                                            <thead>
                                                <tr valign="middle">
                                                    <th colspan="7" style="text-align:center;"> DETALLE OPERACION </th>
                                                    <th colspan="2" style="text-align:center;"> ENTRADA</th>
                                                    <th colspan="2" style="text-align:center;"> SALIDA</th>
                                                    <!--<th rowspan="2" style="text-align:center; vertical-align:middle !important;"> IMPORTE DOLARIZADO </th>-->
                                                    <th colspan="2" style="text-align:center;"> COTIZACION </th>
                                                    <th colspan="2" style="text-align:center;"> DIFERENCIA</th>
                                                    <th colspan="2" style="text-align:center;"> USUARIO </th>
                                                </tr>
                                                <tr valign="middle">
                                                    <th style="text-align:center;"> ORDEN </th>
                                                    <th style="text-align:center;"> FECHA </th>
                                                    <th style="text-align:center;"> HORA </th>
                                                    <th style="text-align:center;"> SUCURSAL </th>
                                                    <th style="text-align:center;"> FACTURA </th>
                                                    <th style="text-align:center;"> N&Uacute;MERO </th>
                                                    <th style="text-align:center;"> TIPO </th>
                                                    <th style="text-align:center;"> MONEDA </th>
                                                    <th style="text-align:center;"> IMPORTE </th>
                                                    <th style="text-align:center;"> MONEDA </th>
                                                    <th style="text-align:center;"> IMPORTE </th>
                                                    <th style="text-align:center;"> MEJORA </th>
                                                    <th style="text-align:center;"> PIZARRA </th>
                                                    <th style="text-align:center;"> PUNTOS </th>
                                                    <th style="text-align:center;"> GUARANIES </th>
                                                    <th style="text-align:center;"> HECHO </th>
                                                    <th style="text-align:center;"> AUTORIZADO </th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
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
        $wSQL00         = ibase_query("SELECT t1.FECHATRANSACCION, t1.HORA, t1.NRO, t1.ID_TIPOOPERACION, t2.CODMONEDA, t4.DESCRIPCION, t2.ID_TIPOESPECIE, t2.IMPORTEME, t2.IMPORTEMN, t2.IMPORTEMD, t2.TCAMBIOOPERADO, t2.PIZARRA, t5.ID_USUARIO, t5.DESCRIPCION, t1.ID_USUARIO_MODIFICA, t6.DESCRIPCION, t7.CODIGO_UNICO
                                        FROM TRANSACCIONES t1
                                        INNER JOIN TRANSACCIONESDETALLES t2 ON t1.ID_TRANSACCION = t2.ID_TRANSACCION
                                        INNER JOIN MONEDAS t4 ON t2.CODMONEDA = t4.CODIGO
                                        INNER JOIN USUARIOS t5 ON t1.ID_USUARIO_INSERTA = t5.ID_USUARIO
                                        INNER JOIN USUARIOS t6 ON t1.ID_USUARIO_MODIFICA = t6.ID_USUARIO
                                        INNER JOIN PERSONAS t7 ON t1.ID_PERSONA = t7.ID_PERSONA
                                        WHERE t1.FECHATRANSACCION = '$wFecha' AND t1.ESTADO = 'A' AND  t1.ID_TIPOOPERACION IN (1, 2, 4)  AND t2.ID_TIPOESPECIE <> 6
                                        ORDER BY  t1.ID_TIPOOPERACION", $str_connect);
        
        $wSQL01         = ibase_query("SELECT t1.FECHATRANSACCION, t1.HORA, t1.NRO, t1.ID_TIPOOPERACION, t2.ID_TIPOESPECIE, t4.CODIGO, t4.DESCRIPCION, t2.IMPORTEME, t2.IMPORTEMN, t2.IMPORTEMD, t7.ID_TIPOESPECIE, t8.CODIGO, t8.DESCRIPCION, t7.IMPORTEME, t7.IMPORTEMN, t7.IMPORTEMD, t2.PARIDAD, t2.PIZARRA_PARIDAD, t5.ID_USUARIO, t5.DESCRIPCION, t1.ID_USUARIO_MODIFICA, t6.DESCRIPCION, t9.CODIGO_UNICO
                                        FROM TRANSACCIONES t1
                                        INNER JOIN TRANSACCIONESDETALLES t2 ON t1.ID_TRANSACCION = t2.ID_TRANSACCION
                                        INNER JOIN MONEDAS t4 ON t2.CODMONEDA = t4.CODIGO
                                        INNER JOIN USUARIOS t5 ON t1.ID_USUARIO_INSERTA = t5.ID_USUARIO
                                        INNER JOIN USUARIOS t6 ON t1.ID_USUARIO_MODIFICA = t6.ID_USUARIO
                                        INNER JOIN TRANSACCIONESDETALLES t7 ON t1.ID_TRANSACCION = t7.ID_TRANSACCION
                                        INNER JOIN MONEDAS t8 ON t7.CODMONEDA = t8.CODIGO
                                        INNER JOIN PERSONAS t9 ON t1.ID_PERSONA = t9.ID_PERSONA
                                        WHERE t1.FECHATRANSACCION = '$wFecha' AND t1.ESTADO = 'A' AND  t1.ID_TIPOOPERACION = 3 AND t2.ID_TIPOESPECIE <> 6 AND t2.OP = 'C' AND t7.ID_TIPOESPECIE <> 6 AND t7.OP = 'V'
                                        ORDER BY  t1.ID_TIPOOPERACION", $str_connect);

        $wSQL02         = ibase_query("SELECT t1.FECHATRANSACCION, t1.HORA, t1.NRO, t1.ID_TIPOOPERACION, t2.CODMONEDA, t4.DESCRIPCION, t2.ID_TIPOESPECIE, t2.IMPORTEME, t2.IMPORTEMN, t2.IMPORTEMD, t2.TCAMBIOOPERADO, t2.PIZARRA, t5.ID_USUARIO, t5.DESCRIPCION, t1.ID_USUARIO_MODIFICA, t6.DESCRIPCION, t7.CODIGO_UNICO
                                        FROM TRANSACCIONES t1
                                        INNER JOIN TRANSACCIONESDETALLES t2 ON t1.ID_TRANSACCION = t2.ID_TRANSACCION
                                        INNER JOIN MONEDAS t4 ON t2.CODMONEDA = t4.CODIGO
                                        INNER JOIN USUARIOS t5 ON t1.ID_USUARIO_INSERTA = t5.ID_USUARIO
                                        INNER JOIN USUARIOS t6 ON t1.ID_USUARIO_MODIFICA = t6.ID_USUARIO
                                        INNER JOIN PERSONAS t7 ON t1.ID_PERSONA = t7.ID_PERSONA
                                        WHERE t1.FECHATRANSACCION = '$wFecha' AND t1.ESTADO = 'A' AND t2.ID_TIPOESPECIE = 6
                                        ORDER BY  t1.ID_TIPOOPERACION", $str_connect);

        while ($row00 = ibase_fetch_row($wSQL00)) {
            $item      = $item + 1;
            $difMejora = 0;
            $impMejora = 0;
            
            switch ($row00[3]) {
                case 1:
                    $tipOper   = 'COMPRA';
                    $difMejora = $row00[10] - $row00[11];
                    $monEntNom = $row00[5];
                    $monEntImp = number_format($row00[7], 2, ',', '.');
                    $monSalNom = 'GUARANIES';
                    $monSalImp = number_format($row00[8], 0, ',', '.');
                    break;
                
                case 2:
                    $tipOper   = 'VENTA';
                    $difMejora = $row00[11] - $row00[10];
                    $monEntNom = 'GUARANIES';
                    $monEntImp = number_format($row00[8], 0, ',', '.');
                    $monSalNom = $row00[5];
                    $monSalImp = number_format($row00[7], 2, ',', '.');
                    break;
            }

            $impMejora = $row00[9] * $difMejora;

            if ($difMejora > 0) {
                $estMejora = 'text-align:right; background-color:rgba(254, 204, 204, 0.5); color:#990000;';
            } else {
                $estMejora = 'text-align:right; background-color:rgba(204, 255, 204, 0.5); color:#00944C;';
                $difMejora = $difMejora * -1;
                $impMejora = $impMejora * -1;
            }

            if ($row00[16] == '3006') {
                $codPers   = 'NO';
            } else {
                $codPers   = 'SI';
            }
?>
                                                <tr>
                                                    <td style="text-align:right;"> <?php echo $item; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $tFecha; ?> </td>
                                                    <td style="text-align:left;"> <?php echo substr($row00[1], 11, 8); ?> </td>
                                                    <td style="text-align:left;"> <?php echo $suc_key; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $codPers; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $row00[2]; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $tipOper; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $monEntNom; ?> </td>
                                                    <td style="text-align:right;"> <?php echo $monEntImp; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $monSalNom; ?> </td>
                                                    <td style="text-align:right;"> <?php echo $monSalImp; ?> </td>
                                                    <!--<td style="text-align:right;"> <?php //echo number_format($row00[9], 5, ',', '.'); ?> </td>-->
                                                    <td style="text-align:right;"> <?php echo number_format($row00[10], 5, ',', '.'); ?> </td>
                                                    <td style="text-align:right;"> <?php echo number_format($row00[11], 5, ',', '.'); ?> </td>
                                                    <td style="<?php echo $estMejora; ?>"> <?php echo number_format($difMejora, 5, ',', '.'); ?> </td>
                                                    <td style="<?php echo $estMejora; ?>"> <?php echo number_format($impMejora, 5, ',', '.'); ?> </td>
                                                    <td style="text-align:left;"> <?php echo $row00[13]; ?>  </td>
                                                    <td style="text-align:left;"> <?php echo $row00[15]; ?>  </td>
                                                </tr>
<?php
        }

        while ($row01 = ibase_fetch_row($wSQL01)) {
            $item      = $item + 1;
            $difMejora = 0;
            $impMejora = 0;
            $tipOper   = 'ARBITRAJE';
            $difMejora = number_format($row01[16], 5, '.', '') - number_format($row01[17], 5, '.', '');
            $monEntNom = $row01[6];
            $monEntImp = number_format($row01[7], 2, ',', '.');
            $monSalNom = $row01[12];
            $monSalImp = number_format($row01[13], 2, ',', '.');

            if ($difMejora < number_format(0, 5, ',', '.')) {
                $estMejora = 'text-align:right; background-color:rgba(254, 204, 204, 0.5); color:#990000;';
                $difMejora = $difMejora * -1;
            } else {
                $estMejora = 'text-align:right; background-color:rgba(204, 255, 204, 0.5); color:#00944C;';
            }

            if ($row00[22] == '3006') {
                $codPers   = 'SI';
            } else {
                $codPers   = 'NO';
            }
?>
                                                <tr>
                                                    <td style="text-align:right;"> <?php echo $item; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $tFecha; ?> </td>
                                                    <td style="text-align:left;"> <?php echo substr($row01[1], 11, 8); ?> </td>
                                                    <td style="text-align:left;"> <?php echo $suc_key; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $codPers; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $row01[2]; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $tipOper; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $monEntNom; ?> </td>
                                                    <td style="text-align:right;"> <?php echo $monEntImp; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $monSalNom; ?> </td>
                                                    <td style="text-align:right;"> <?php echo $monSalImp; ?> </td>
                                                    <!--<td style="text-align:right;"> <?php //echo number_format($row00[9], 5, ',', '.'); ?> </td>-->
                                                    <td style="text-align:right;"> <?php echo number_format($row01[16], 5, ',', '.'); ?> </td>
                                                    <td style="text-align:right;"> <?php echo number_format($row01[17], 5, ',', '.'); ?> </td>
                                                    <td style="<?php echo $estMejora; ?>"> <?php echo number_format($difMejora, 5, ',', '.'); ?> </td>
                                                    <td style="<?php echo $estMejora; ?>"> <?php echo number_format($impMejora, 5, ',', '.'); ?> </td>
                                                    <td style="text-align:left;"> <?php echo $row01[19]; ?>  </td>
                                                    <td style="text-align:left;"> <?php echo $row01[21]; ?>  </td>
                                                </tr>
<?php
        }

        while ($row02 = ibase_fetch_row($wSQL02)) {
            $item      = $item + 1;
            $difMejora = 0;
            $impMejora = 0;
            switch ($row02[3]) {
                case 1:
                    $tipOper   = 'TRANSFER';
                    $difMejora = $row02[10] - $row02[11];
                    $monEntNom = $row02[5];
                    $monEntImp = number_format($row02[7], 2, ',', '.');
                    $monSalNom = 'GUARANIES';
                    $monSalImp = number_format($row02[8], 0, ',', '.');
                    break;
                
                case 2:
                    $tipOper   = 'TRANSFER';
                    $difMejora = $row02[11] - $row02[10];
                    $monEntNom = 'GUARANIES';
                    $monEntImp = number_format($row02[8], 0, ',', '.');
                    $monSalNom = $row02[5];
                    $monSalImp = number_format($row02[7], 2, ',', '.');
                    break;
            }

            $impMejora = $row02[9] * $difMejora;

            if ($difMejora > 0) {
                $estMejora = 'text-align:right; background-color:rgba(254, 204, 204, 0.5); color:#990000;';
            } else {
                $estMejora = 'text-align:right; background-color:rgba(204, 255, 204, 0.5); color:#00944C;';
                $difMejora = $difMejora * -1;
                $impMejora = $impMejora * -1;
            }

            if ($row00[16] == '3006') {
                $codPers   = 'SI';
            } else {
                $codPers   = 'NO';
            }
?>
                                                <tr>
                                                    <td style="text-align:right;"> <?php echo $item; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $tFecha; ?> </td>
                                                    <td style="text-align:left;"> <?php echo substr($row02[1], 11, 8); ?> </td>
                                                    <td style="text-align:left;"> <?php echo $suc_key; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $codPers; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $row02[2]; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $tipOper; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $monEntNom; ?> </td>
                                                    <td style="text-align:right;"> <?php echo $monEntImp; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $monSalNom; ?> </td>
                                                    <td style="text-align:right;"> <?php echo $monSalImp; ?> </td>
                                                    <!--<td style="text-align:right;"> <?php //echo number_format($row00[9], 5, ',', '.'); ?> </td>-->
                                                    <td style="text-align:right;"> <?php echo number_format($row02[10], 5, ',', '.'); ?> </td>
                                                    <td style="text-align:right;"> <?php echo number_format($row02[11], 5, ',', '.'); ?> </td>
                                                    <td style="<?php echo $estMejora; ?>"> <?php echo number_format($difMejora, 5, ',', '.'); ?> </td>
                                                    <td style="<?php echo $estMejora; ?>"> <?php echo number_format($impMejora, 5, ',', '.'); ?> </td>
                                                    <td style="text-align:left;"> <?php echo $row02[13]; ?>  </td>
                                                    <td style="text-align:left;"> <?php echo $row02[15]; ?>  </td>
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
        </script>
    </body>
</html>

<?php
  ibase_close($str_connect);
?>