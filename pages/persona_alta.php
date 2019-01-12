<?php
    session_start();
    $idSession  = $_SESSION['Sys00'];
    $wFecha     = date('Y/m/d');
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
                                PERSONAS NUEVAS DEL <?php echo $tFecha; ?>
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
                                                    <th style="text-align:center;"> NRO </th>
                                                    <th style="text-align:center;"> FECHA </th> 
                                                    <!--<th style="text-align:center;"> ID_PERSONA </th>-->
                                                    <th style="text-align:center;"> CODIGO </th>
                                                    <th style="text-align:center;"> CODIGO_UNICO </th>
                                                    <th style="text-align:center;"> PAIS DOC. </th>
                                                    <th style="text-align:center;"> RUC </th>
                                                    <!--<th style="text-align:center;"> PASAPORTE </th>-->
                                                    <th style="text-align:center;"> NACIONALIDAD </th>
                                                    <th style="text-align:center;"> PAÍS </th>
                                                    <th style="text-align:center;"> APELLIDO, NOMBRE </th>
                                                    <th style="text-align:center;"> SUCURSAL </th>
                                                    <th style="text-align:center;"> INGRESADO POR </th>
                                                </tr>
                                            </thead>
                                            <tbody>
<?php
    $item       = 0;
    $suc_array  = array(
        "CASA MATRIZ"               => "192.168.0.200:aliadocambios",
        "SUC. VILLA MORRA"          => "10.168.196.130:aliadocambios",
        "AGE. SAN LORENZO"          => "10.168.191.130:aliadocambios",
        "SUC. CIUDAD DEL ESTE"      => "10.168.192.138:aliadocambios",
        "AGE. JEBAI"                => "10.168.193.130:aliadocambios",
        "AGE. LAI LAI"              => "10.168.194.130:aliadocambios",
        "AGE. UNIAMERICA"           => "10.168.199.131:aliadocambios",
        "AGE. RUBIO ÑU"             => "10.168.195.130:aliadocambios",
        "AGE. KM4"                  => "10.168.190.130:aliadocambios",
        "SUC. SALTO DEL GUAIRA"     => "10.168.198.130:aliadocambios",
        "AGE. SALTO DEL GUAIRA"     => "10.168.197.130:aliadocambios",
        "SUC. ENCARNACION"          => "10.168.189.130:aliadocambios"
    );

    foreach($suc_array as $suc_key => $suc_ip) {
        $str_db         = $suc_ip;
        $str_user       = 'sysdba';
        $str_pass       = 'dorotea';
        $str_connect    = ibase_connect($str_db, $str_user, $str_pass) OR DIE("NO SE CONECTO AL SERVIDOR: ".ibase_errmsg());
        $wSQL00 = ibase_query("SELECT t1.FECHA, t1.ID_PERSONA, t1.CODIGO, t1.CODIGO_UNICO, t1.RUC, t1.PASAPORTE, t1.ID_NACIONALIDAD, t2.DESCRIPCION, t1.ID_PAIS, t3.DESCRIPCION, t1.RAZONSOCIAL, t1.INGRESADOPOR, t1.AUTORIZADOPOR, t1.ID_PAIS_DOCUMENTO, t4.DESCRIPCION
                                FROM PERSONAS t1
                                LEFT JOIN NACIONALIDADES t2 ON t2.ID_NACIONALIDAD = t1.ID_NACIONALIDAD
                                LEFT JOIN PAISES t3 ON t3.ID_PAIS = t1.ID_PAIS
                                LEFT JOIN PAIS_DOCUMENTO t4 ON t4.ID_PAIS_DOCUMENTO = t1.ID_PAIS_DOCUMENTO
                                WHERE t1.FECHA >= '$wFecha'
                                ORDER BY t1.FECHA", $str_connect);

        while ($row00 = ibase_fetch_row($wSQL00)) {
            $item       = $item + 1;
            $fecAlta    = substr($row00[0], 8, 2).'/'.substr($row00[0], 5, 2).'/'.substr($row00[0], 0, 4);
            $horAlta    = substr($row00[0], 11, 8);
?>
                                                <tr>
                                                    <td style="text-align:right;"> <?php echo $item; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $fecAlta.' '.$horAlta; ?> </td>
                                                    <!--<td style="text-align:left;"> <?php echo $row00[1]; ?> </td>-->
                                                    <td style="text-align:left;"> <?php echo $row00[2]; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $row00[3]; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $row00[14]; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $row00[4]; ?> </td>
                                                    <!--<td style="text-align:left;"> <?php echo $row00[5]; ?> </td>-->
                                                    <td style="text-align:left;"> <?php echo $row00[7]; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $row00[9]; ?> </td>
                                                    <td style="text-align:left; font-weight:bold;"> <?php echo $row00[10]; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $suc_key; ?> </td>
                                                    <td style="text-align:left;"> <?php echo $row00[11]; ?>  </td>
                                                </tr>
<?php
        }
        ibase_free_result($wSQL00);
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