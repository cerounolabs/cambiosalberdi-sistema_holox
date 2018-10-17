<?php
  include '../incl/sucursal.php';
  include '../incl/function.php';

  $wFecha   = date('Y/m/d');
  $tFecha   = date('d/m/Y');
  $wHora    = date('H:i:s');
//  $IPClient = getRealIP();
//  $IPBoolean= getHabilitadoIP();
?>

<?php
  $estBoletaTit02 = '';
  $estBoletaAnu02 = 0;
  $estBoletaLiq02 = 0;
  $estBoletaPen02 = 0;

  $wSQL02         = ibase_query("SELECT ESTADO, COUNT(ESTADO)
                          FROM TRANSACCIONES
                          WHERE FECHATRANSACCION = '$wFecha' AND ID_TIPOOPERACION <> 6
                          GROUP BY ESTADO", $db);

  while ($row02 = ibase_fetch_row($wSQL02)) {
    switch ($row02[0]) {
      case 'A':
        $estBoletaAnu02 = $row02[1];
        break;

      case 'L':
        $estBoletaLiq02 = $row02[1];
        break;

      case 'P':
        $estBoletaPen02 = $row02[1];
        break;
    }
  }

  if (empty($estBoletaAnu02)) {
    $estBoletaAnu02 = 0;
  }

  if (empty($estBoletaLiq02)) {
    $estBoletaLiq02 = 0;
  }

  if (empty($estBoletaPen02)) {
    $estBoletaPen02 = 0;
  }

  $estBoletaTit02 = $estBoletaAnu02.', '. $estBoletaLiq02.', '.$estBoletaPen02;
  ibase_free_result($wSQL02);
?>

<?php
  $wSQL03 = ibase_query("SELECT ID_TIPOOPERACION, COUNT(*)
                          FROM TRANSACCIONES
                          WHERE FECHATRANSACCION = '$wFecha' AND ESTADO = 'L' AND ID_TIPOOPERACION IN (1, 2, 3, 4, 5)
                          GROUP BY ID_TIPOOPERACION", $db);

  while ($row03 = ibase_fetch_row($wSQL03)) {
    switch ($row03[0]) {
      case 1:
        $opeCompra    = $row03[1];
        break;

      case 2:
        $opeVenta     = $row03[1];
        break;

      case 3:
        $opeArbitraje = $row03[1];
        break;

      case 4:
        $opeAsiento   = $row03[1];
        break;

      case 5:
        $opeCanje     = $row03[1];
        break;
    }
  }

  if (empty($opeCompra)) {
    $opeCompra = 0;
  }

  if (empty($opeVenta)) {
    $opeVenta = 0;
  }

  if (empty($opeArbitraje)) {
    $opeArbitraje = 0;
  }

  if (empty($opeAsiento)) {
    $opeAsiento = 0;
  }

  if (empty($opeCanje)) {
    $opeCanje = 0;
  }

  $operTotal = $opeCompra.', '. $opeVenta.', '.$opeArbitraje.', '.$opeCanje.', '.$opeAsiento;
  ibase_free_result($wSQL03);
?>

<?php
  $banDolarBB04 = 1;
  $banRealBB04  = 1;
  $banPesoBB04  = 1;
  $banEuroBB04  = 1;

  $minDolarBB04 = 0;
  $minRealBB04  = 0;
  $minPesoBB04  = 0;
  $minEuroBB04  = 0;

  $maxDolarBB04 = 0;
  $maxRealBB04  = 0;
  $maxPesoBB04  = 0;
  $maxEuroBB04  = 0;

  $wSQL04     = ibase_query("SELECT TCCOMPRABB, TCVENTABB, ID_MONEDA, HORA
                              FROM HISTORICOSCOTIZACIONES
                              WHERE ID_TIPOCOTIZACION = 1 AND ID_MONEDA IN (3, 4, 5, 6) AND FECHA = '$wFecha'
                              ORDER BY HORA", $db);

  $wSQL04_1   = ibase_query("SELECT TCCOMPRABB, TCVENTABB, ID_MONEDA
                              FROM COTIZACIONESMONEDAS
                              WHERE ID_TIPOCOTIZACION = 1 AND ID_MONEDA IN (3, 4, 5, 6)
                              ORDER BY HORA", $db);

  while ($row04 = ibase_fetch_row($wSQL04)) {
    switch ($row04[2]) {
      case 3:
        if ($banDolarBB04 === 1) {
          $minDolarBB04     = $row04[0];
          $maxDolarBB04     = $row04[1];
          $hisDolarTitBB04  = '"'.substr($row04[3], 11, 8).'"';
          $hisDolarComBB04  = $row04[0];
          $hisDolarVenBB04  = $row04[1];
          $banDolarBB04     = 0;
        } else {
          if ($minDolarBB04 > $row04[0]) {
            $minDolarBB04 = $row04[0];
          }
            
          if ($maxDolarBB04 < $row04[1]) {
            $maxDolarBB04 = $row04[1];
          }

          $hisDolarTitBB04 = $hisDolarTitBB04.', "'.substr($row04[3], 11, 8).'"';
          $hisDolarComBB04 = $hisDolarComBB04.', '.$row04[0];
          $hisDolarVenBB04 = $hisDolarVenBB04.', '.$row04[1];
        }
        break;
        
      case 4:    
        if ($banRealBB04 === 1) {
          $minRealBB04    = $row04[0];
          $maxRealBB04    = $row04[1];
          $hisRealTitBB04 = '"'.substr($row04[3], 11, 8).'"';
          $hisRealComBB04 = $row04[0];
          $hisRealVenBB04 = $row04[1];
          $banRealBB04    = 0;
        } else {
          if ($minRealBB04 > $row04[0]) {
            $minRealBB04 = $row04[0];
          }
            
          if ($maxRealBB04 < $row04[1]) {
            $maxRealBB04 = $row04[1];
          }

          $hisRealTitBB04 = $hisRealTitBB04.', "'.substr($row04[3], 11, 8).'"';
          $hisRealComBB04 = $hisRealComBB04.', '.$row04[0];
          $hisRealVenBB04 = $hisRealVenBB04.', '.$row04[1];
        }
        break;

      case 5:    
        if ($banPesoBB04 === 1) {
          $minPesoBB04    = $row04[0];
          $maxPesoBB04    = $row04[1];
          $hisPesoTitBB04 = '"'.substr($row04[3], 11, 8).'"';
          $hisPesoComBB04 = $row04[0];
          $hisPesoVenBB04 = $row04[1];
          $banPesoBB04    = 0;
        } else {
          if ($minPesoBB04 > $row04[0]) {
            $minPesoBB04 = $row04[0];
          }
            
          if ($maxPesoBB04 < $row04[1]) {
            $maxPesoBB04 = $row04[1];
          }

          $hisPesoTitBB04 = $hisPesoTitBB04.', "'.substr($row04[3], 11, 8).'"';
          $hisPesoComBB04 = $hisPesoComBB04.', '.$row04[0];
          $hisPesoVenBB04 = $hisPesoVenBB04.', '.$row04[1];
        }
        break;

      case 6:    
        if ($banEuroBB04 === 1) {
          $minEuroBB04    = $row04[0];
          $maxEuroBB04    = $row04[1];
          $hisEuroTitBB04 = '"'.substr($row04[3], 11, 8).'"';
          $hisEuroComBB04 = $row04[0];
          $hisEuroVenBB04 = $row04[1];
          $banEuroBB04    = 0;
        } else {
          if ($minEuroBB04 > $row04[0]) {
            $minEuroBB04 = $row04[0];
          }
            
          if ($maxEuroBB04 < $row04[1]) {
            $maxEuroBB04 = $row04[1];
          }

          $hisEuroTitBB04 = $hisEuroTitBB04.', "'.substr($row04[3], 11, 8).'"';
          $hisEuroComBB04 = $hisEuroComBB04.', '.$row04[0];
          $hisEuroVenBB04 = $hisEuroVenBB04.', '.$row04[1];
        }
        break;
    }
  }

  while ($row04_1 = ibase_fetch_row($wSQL04_1)) {
    switch ($row04_1[2]) {
      case 3:
        if ($minDolarBB04 > $row04_1[0]) {
          $minDolarBB04 = $row04_1[0];
        }
          
        if ($maxDolar04 < $row04_1[1]) {
          $maxDolar04 = $row04_1[1];
        }

        $hisDolarTitBB04 = $hisDolarTitBB04.', "'.$wHora.'"';
        $hisDolarComBB04 = $hisDolarComBB04.', '.$row04_1[0];
        $hisDolarVenBB04 = $hisDolarVenBB04.', '.$row04_1[1];
        break;

      case 4:
        if ($minRealBB04 > $row04_1[0]) {
          $minRealBB04 = $row04_1[0];
        }
          
        if ($maxRealBB04 < $row04_1[1]) {
          $maxRealBB04 = $row04_1[1];
        }

        $hisRealTitBB04 = $hisRealTitBB04.', "'.$wHora.'"';
        $hisRealComBB04 = $hisRealComBB04.', '.$row04_1[0];
        $hisRealVenBB04 = $hisRealVenBB04.', '.$row04_1[1];
        break;

      case 5:
        if ($minPesoBB04 > $row04_1[0]) {
          $minPesoBB04 = $row04_1[0];
        }
          
        if ($maxPesoBB04 < $row04_1[1]) {
          $maxPesoBB04 = $row04_1[1];
        }

        $hisPesoTitBB04 = $hisPesoTitBB04.', "'.$wHora.'"';
        $hisPesoComBB04 = $hisPesoComBB04.', '.$row04_1[0];
        $hisPesoVenBB04 = $hisPesoVenBB04.', '.$row04_1[1];
        break;

      case 6:
        if ($minEuroBB04 > $row04_1[0]) {
          $minEuroBB04 = $row04_1[0];
        }
          
        if ($maxEuroBB04 < $row04_1[1]) {
          $maxEuroBB04 = $row04_1[1];
        }

        $hisEuroTitBB04 = $hisEuroTitBB04.', "'.$wHora.'"';
        $hisEuroComBB04 = $hisEuroComBB04.', '.$row04_1[0];
        $hisEuroVenBB04 = $hisEuroVenBB04.', '.$row04_1[1];
        break;
    }
  }

  ibase_free_result($wSQL04);
  ibase_free_result($wSQL04_1);
?>

<?php
  $BanDolarComp05 = 1;
  $BanRealComp05  = 1;
  $BanPesoComp05  = 1;
  $BanEuroComp05  = 1;

  $BanDolarVent05 = 1;
  $BanRealVent05  = 1;
  $BanPesoVent05  = 1;
  $BanEuroVent05  = 1;

  $ConDolarComp05 = 1;
  $ConRealComp05  = 1;
  $ConPesoComp05  = 1;
  $ConEuroComp05  = 1;

  $ConDolarVent05 = 1;
  $ConRealVent05  = 1;
  $ConPesoVent05  = 1;
  $ConEuroVent05  = 1;

  $minDolarComp05 = 0;
  $minRealComp05  = 0;
  $minPesoComp05  = 0;
  $minEuroComp05  = 0;

  $minDolarVent05 = 0;
  $minRealVent05  = 0;
  $minPesoVent05  = 0;
  $minEuroVent05  = 0;

  $maxDolarComp05 = 0;
  $maxRealComp05  = 0;
  $maxPesoComp05  = 0;
  $maxEuroComp05  = 0;

  $maxDolarVent05 = 0;
  $maxRealVent05  = 0;
  $maxPesoVent05  = 0;
  $maxEuroVent05  = 0;

  $wSQL05     = ibase_query("SELECT t1.ID_TRANSACCION, t1.HORA, t2.TCAMBIOOPERADO, t2.PIZARRA, t2.CODMONEDA, t1.ID_TIPOOPERACION
                            FROM TRANSACCIONES t1
                            INNER JOIN TRANSACCIONESDETALLES t2 ON t1.ID_TRANSACCION = t2.ID_TRANSACCION
                            WHERE t1.FECHATRANSACCION = '$wFecha' AND t1.ESTADO = 'L' AND t2.ID_TIPOESPECIE = 1 AND  t1.ID_TIPOOPERACION IN (1, 2) AND t2.CODMONEDA IN (1, 9)
                            ORDER BY  t1.ID_TRANSACCION", $db);

  while ($row05 = ibase_fetch_row($wSQL05)) {
    if ($row05[5] === 1) {
      switch ($row05[4]) {
        case 1:
          if ($BanDolarComp05 === 1) {
            $minDolarComp05         = number_format($row05[2], 0, '.', '');
            $maxDolarComp05         = number_format($row05[2], 0, '.', '');

            if ($minDolarComp05 > number_format($row05[3], 0, '.', '')) {
              $minDolarComp05 = number_format($row05[3], 0, '.', '');
            }

            if ($maxDolarComp05 < number_format($row05[3], 0, '.', '')) {
              $maxDolarComp05 = number_format($row05[3], 0, '.', '');
            }

            $charDolarCompTit05 = '"'.($row05[0]).'"';
            $charDolarCompOpe05 = number_format($row05[2], 2, '.', '');
            $charDolarCompPiz05 = number_format($row05[3], 2, '.', '');
            $BanDolarComp05     = 0;
          } else {
            if ($minDolarComp05 > number_format($row05[2], 0, '.', '')) {
              $minDolarComp05 = number_format($row05[2], 0, '.', '');
            }
    
            if ($minDolarComp05 > number_format($row05[3], 0, '.', '')) {
              $minDolarComp05 = number_format($row05[3], 0, '.', '');
            }
              
            if ($maxDolarComp05 < number_format($row05[2], 0, '.', '')) {
              $maxDolarComp05 = number_format($row05[2], 0, '.', '');
            }
    
            if ($maxDolarComp05 < number_format($row05[3], 0, '.', '')) {
              $maxDolarComp05 = number_format($row05[3], 0, '.', '');
            }

            $charDolarCompTit05 = $charDolarCompTit05.', "'.($row05[0]).'"';
            $charDolarCompOpe05 = $charDolarCompOpe05.', '.number_format($row05[2], 2, '.', '');
            $charDolarCompPiz05 = $charDolarCompPiz05.', '.number_format($row05[3], 2, '.', '');
          }
          break;

        case 9:
          if ($BanRealComp05 === 1) {
            $minRealComp05         = number_format($row05[2], 0, '.', '');
            $maxRealComp05         = number_format($row05[2], 0, '.', '');

            if ($minRealComp05 > number_format($row05[3], 0, '.', '')) {
              $minRealComp05 = number_format($row05[3], 0, '.', '');
            }

            if ($maxRealComp05 < number_format($row05[3], 0, '.', '')) {
              $maxRealComp05 = number_format($row05[3], 0, '.', '');
            }

            $charRealCompTit05 = '"'.($row05[0]).'"';
            $charRealCompOpe05 = number_format($row05[2], 2, '.', '');
            $charRealCompPiz05 = number_format($row05[3], 2, '.', '');
            $BanRealComp05     = 0;
          } else {
            if ($minRealComp05 > number_format($row05[2], 0, '.', '')) {
              $minRealComp05 = number_format($row05[2], 0, '.', '');
            }
    
            if ($minRealComp05 > number_format($row05[3], 0, '.', '')) {
              $minRealComp05 = number_format($row05[3], 0, '.', '');
            }
              
            if ($maxRealComp05 < number_format($row05[2], 0, '.', '')) {
              $maxRealComp05 = number_format($row05[2], 0, '.', '');
            }
    
            if ($maxRealComp05 < number_format($row05[3], 0, '.', '')) {
              $maxRealComp05 = number_format($row05[3], 0, '.', '');
            }

            $charRealCompTit05 = $charRealCompTit05.', "'.($row05[0]).'"';
            $charRealCompOpe05 = $charRealCompOpe05.', '.number_format($row05[2], 2, '.', '');
            $charRealCompPiz05 = $charRealCompPiz05.', '.number_format($row05[3], 2, '.', '');
          }
          break;
      }
    } else {
      switch ($row05[4]) {
        case 1:
          if ($BanDolarVent05 === 1) {
            $minDolarVent05         = number_format($row05[2], 0, '.', '');
            $maxDolarVent05         = number_format($row05[2], 0, '.', '');

            if ($minDolarVent05 > number_format($row05[3], 0, '.', '')) {
              $minDolarVent05 = number_format($row05[3], 0, '.', '');
            }

            if ($maxDolarVent05 < number_format($row05[3], 0, '.', '')) {
              $maxDolarVent05 = number_format($row05[3], 0, '.', '');
            }

            $charDolarVentTit05 = '"'.($row05[0]).'"';
            $charDolarVentOpe05 = number_format($row05[2], 2, '.', '');
            $charDolarVentPiz05 = number_format($row05[3], 2, '.', '');
            $BanDolarVent05     = 0;
          } else {
            if ($minDolarVent05 > number_format($row05[2], 0, '.', '')) {
              $minDolarVent05 = number_format($row05[2], 0, '.', '');
            }
    
            if ($minDolarVent05 > number_format($row05[3], 0, '.', '')) {
              $minDolarVent05 = number_format($row05[3], 0, '.', '');
            }
              
            if ($maxDolarVent05 < number_format($row05[2], 0, '.', '')) {
              $maxDolarVent05 = number_format($row05[2], 0, '.', '');
            }
    
            if ($maxDolarVent05 < number_format($row05[3], 0, '.', '')) {
              $maxDolarVent05 = number_format($row05[3], 0, '.', '');
            }

            $charDolarVentTit05 = $charDolarVentTit05.', "'.($row05[0]).'"';
            $charDolarVentOpe05 = $charDolarVentOpe05.', '.number_format($row05[2], 2, '.', '');
            $charDolarVentPiz05 = $charDolarVentPiz05.', '.number_format($row05[3], 2, '.', '');
          }
          break;

        case 9:
          if ($BanRealVent05 === 1) {
            $minRealVent05         = number_format($row05[2], 0, '.', '');
            $maxRealVent05         = number_format($row05[2], 0, '.', '');

            if ($minRealVent05 > number_format($row05[3], 0, '.', '')) {
              $minRealVent05 = number_format($row05[3], 0, '.', '');
            }

            if ($maxRealVent05 < number_format($row05[3], 0, '.', '')) {
              $maxRealVent05 = number_format($row05[3], 0, '.', '');
            }

            $charRealVentTit05 = '"'.($row05[0]).'"';
            $charRealVentOpe05 = number_format($row05[2], 2, '.', '');
            $charRealVentPiz05 = number_format($row05[3], 2, '.', '');
            $BanRealVent05     = 0;
          } else {
            if ($minRealVent05 > number_format($row05[2], 0, '.', '')) {
              $minRealVent05 = number_format($row05[2], 0, '.', '');
            }
    
            if ($minRealVent05 > number_format($row05[3], 0, '.', '')) {
              $minRealVent05 = number_format($row05[3], 0, '.', '');
            }
              
            if ($maxRealVent05 < number_format($row05[2], 0, '.', '')) {
              $maxRealVent05 = number_format($row05[2], 0, '.', '');
            }
    
            if ($maxRealVent05 < number_format($row05[3], 0, '.', '')) {
              $maxRealVent05 = number_format($row05[3], 0, '.', '');
            }

            $charRealVentTit05 = $charRealVentTit05.', "'.($row05[0]).'"';
            $charRealVentOpe05 = $charRealVentOpe05.', '.number_format($row05[2], 2, '.', '');
            $charRealVentPiz05 = $charRealVentPiz05.', '.number_format($row05[3], 2, '.', '');
          }
          break;
      }
    }
  }
  ibase_free_result($wSQL05);
?>

<?php
  $banDolarxReal06  = 1;
  $banDolarxPeso06  = 1;
  $banDolarxEuro06  = 1;

  $minDolarxReal06  = 0;
  $minDolarxPeso06  = 0;
  $minDolarxEuro06  = 0;

  $maxDolarxReal06  = 0;
  $maxDolarxPeso06  = 0;
  $maxDolarxEuro06  = 0;

  $wSQL06           = ibase_query("SELECT t4.ID_MONEDA, t4.DESCRIPCION, t2.ID_MONEDA, t2.DESCRIPCION, t1.PARIDAD_C, t1.PARIDAD_V, t1.HORA
                                    FROM HISTORICOS_PARIDADES t1
                                    INNER JOIN MONEDAS t2 ON t1.ID_MONEDA = t2.ID_MONEDA
                                    INNER JOIN COTIZACIONESMONEDAS t3 ON t1.ID_COTIZACIONMONEDA = t3.ID_COTIZACIONMONEDA
                                    INNER JOIN MONEDAS t4 ON t3.ID_MONEDA = t4.ID_MONEDA
                                    WHERE ((t4.ID_MONEDA = 6 AND t2.ID_MONEDA = 3) OR (t4.ID_MONEDA = 3 AND t2.ID_MONEDA = 4) OR (t4.ID_MONEDA = 3 AND t2.ID_MONEDA = 5)) AND t1.FECHA = '$wFecha'", $db);

  $wSQL06_1         = ibase_query("SELECT t4.ID_MONEDA, t4.DESCRIPCION, t2.ID_MONEDA, t2.DESCRIPCION, t1.PARIDAD_C, t1.PARIDAD_V
                                    FROM PARIDAD t1
                                    INNER JOIN MONEDAS t2 ON t1.ID_MONEDA = t2.ID_MONEDA
                                    INNER JOIN COTIZACIONESMONEDAS t3 ON t1.ID_COTIZACIONMONEDA = t3.ID_COTIZACIONMONEDA
                                    INNER JOIN MONEDAS t4 ON t3.ID_MONEDA = t4.ID_MONEDA
                                    WHERE ((t4.ID_MONEDA = 6 AND t2.ID_MONEDA = 3) OR (t4.ID_MONEDA = 3 AND t2.ID_MONEDA = 4) OR (t4.ID_MONEDA = 3 AND t2.ID_MONEDA = 5))", $db);

  while ($row06 = ibase_fetch_row($wSQL06)) {
    if ($row06[0] === 3 && $row06[2] === 4) {
      if ($banDolarxReal06 === 1) {
        $minDolarxReal06      = number_format($row06[4], 4, '.', '');
        $maxDolarxReal06      = number_format($row06[5], 4, '.', '');
        $hisDolarxRealTitBB06 = '"'.substr($row06[6], 11, 8).'"';
        $hisDolarxRealComBB06 = number_format($row06[4], 4, '.', '');
        $hisDolarxRealVenBB06 = number_format($row06[5], 4, '.', '');
        $banDolarxReal06      = 0;
      } else {
        if ($minDolarxReal06 > number_format($row06[4], 4, '.', '')) {
          $minDolarxReal06 = number_format($row06[4], 4, '.', '');
        }
          
        if ($maxDolarxReal06 < number_format($row06[5], 4, '.', '')) {
          $maxDolarxReal06 = number_format($row06[5], 4, '.', '');
        }

        $hisDolarxRealTitBB06 = $hisDolarxRealTitBB06.', "'.substr($row06[6], 11, 8).'"';
        $hisDolarxRealComBB06 = $hisDolarxRealComBB06.', '.number_format($row06[4], 4, '.', '');
        $hisDolarxRealVenBB06 = $hisDolarxRealVenBB06.', '.number_format($row06[5], 4, '.', '');
      }
    }
        
    if ($row06[0] === 3 && $row06[2] === 5) {  
      if ($banDolarxPeso06 === 1) {
        $minDolarxPeso06      = number_format($row06[4], 4, '.', '');
        $maxDolarxPeso06      = number_format($row06[5], 4, '.', '');
        $hisDolarxPesoTitBB06 = '"'.substr($row06[6], 11, 8).'"';
        $hisDolarxPesoComBB06 = number_format($row06[4], 4, '.', '');
        $hisDolarxPesoVenBB06 = number_format($row06[5], 4, '.', '');
        $banDolarxPeso06      = 0;
      } else {
        if ($minDolarxPeso06 > number_format($row06[4], 4, '.', '')) {
          $minDolarxPeso06 = number_format($row06[4], 4, '.', '');
        }
            
        if ($maxDolarxPeso06 < number_format($row06[5], 4, '.', '')) {
          $maxDolarxPeso06 = number_format($row06[5], 4, '.', '');
        }

        $hisDolarxPesoTitBB06 = $hisDolarxPesoTitBB06.', "'.substr($row06[6], 11, 8).'"';
        $hisDolarxPesoComBB06 = $hisDolarxPesoComBB06.', '.number_format($row06[4], 4, '.', '');
        $hisDolarxPesoVenBB06 = $hisDolarxPesoVenBB06.', '.number_format($row06[5], 4, '.', '');
      }
    }

    if ($row06[0] === 6 && $row06[2] === 3) { 
      if ($banDolarxEuro06 === 1) {
        $minDolarxEuro06      = number_format($row06[5], 4, '.', '');
        $maxDolarxEuro06      = number_format($row06[4], 4, '.', '');
        $hisDolarxEuroTitBB06 = '"'.substr($row06[6], 11, 8).'"';
        $hisDolarxEuroComBB06 = number_format($row06[5], 4, '.', '');
        $hisDolarxEuroVenBB06 = number_format($row06[4], 4, '.', '');
        $banDolarxEuro06      = 0;
      } else {
        if ($minDolarxEuro06 > number_format($row06[5], 4, '.', '')) {
          $minDolarxEuro06 = number_format($row06[5], 4, '.', '');
        }
          
        if ($maxDolarxEuro06 < number_format($row06[4], 4, '.', '')) {
          $maxDolarxEuro06 = number_format($row06[4], 4, '.', '');
        }

        $hisDolarxEuroTitBB06 = $hisDolarxEuroTitBB06.', "'.substr($row06[6], 11, 8).'"';
        $hisDolarxEuroComBB06 = $hisDolarxEuroComBB06.', '.number_format($row06[5], 4, '.', '');
        $hisDolarxEuroVenBB06 = $hisDolarxEuroVenBB06.', '.number_format($row06[4], 4, '.', '');
      }
    }
  }

  while ($row06_1 = ibase_fetch_row($wSQL06_1)) {
    if ($row06_1[0] === 3 && $row06_1[2] === 4) {
      if ($minDolarxReal06 > number_format($row06_1[4], 4, '.', '')) {
        $minDolarxReal06 = number_format($row06_1[4], 4, '.', '');
      }
        
      if ($maxDolarxReal06 < number_format($row06_1[5], 4, '.', '')) {
        $maxDolarxReal06 = number_format($row06_1[5], 4, '.', '');
      }

      $hisDolarxRealTitBB06 = $hisDolarxRealTitBB06.', "'.$wHora.'"';
      $hisDolarxRealComBB06 = $hisDolarxRealComBB06.', '.number_format($row06_1[4], 4, '.', '');
      $hisDolarxRealVenBB06 = $hisDolarxRealVenBB06.', '.number_format($row06_1[5], 4, '.', '');
    }

    if ($row06_1[0] === 3 && $row06_1[2] === 5) {  
      if ($minDolarxPeso06 > number_format($row06_1[4], 4, '.', '')) {
        $minDolarxPeso06 = number_format($row06_1[4], 4, '.', '');
      }
        
      if ($maxDolarxPeso06 < number_format($row06_1[5], 4, '.', '')) {
        $maxDolarxPeso06 = number_format($row06_1[5], 4, '.', '');
      }

      $hisDolarxPesoTitBB06 = $hisDolarxPesoTitBB06.', "'.$wHora.'"';
      $hisDolarxPesoComBB06 = $hisDolarxPesoComBB06.', '.number_format($row06_1[4], 4, '.', '');
      $hisDolarxPesoVenBB06 = $hisDolarxPesoVenBB06.', '.number_format($row06_1[5], 4, '.', '');
    }

    if ($row06_1[0] === 6 && $row06_1[2] === 3) { 
      if ($minDolarxEuro06 > number_format($row06_1[5], 4, '.', '')) {
        $minDolarxEuro06 = number_format($row06_1[5], 4, '.', '');
      }
        
      if ($maxDolarxEuro06 < number_format($row06_1[4], 4, '.', '')) {
        $maxDolarxEuro06 = number_format($row06_1[4], 4, '.', '');
      }

      $hisDolarxEuroTitBB06 = $hisDolarxEuroTitBB06.', "'.$wHora.'"';
      $hisDolarxEuroComBB06 = $hisDolarxEuroComBB06.', '.number_format($row06_1[5], 4, '.', '');
      $hisDolarxEuroVenBB06 = $hisDolarxEuroVenBB06.', '.number_format($row06_1[4], 4, '.', '');
    }
  }
  ibase_free_result($wSQL06_1);
  ibase_free_result($wSQL06);
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
                  <h4 class="card-title">Hist&oacute;rico D&oacute;lar <?php echo $tFecha; ?></h4>
                  <canvas id="historicoDolar" style="height:250px"></canvas>
                </div>
              </div>
            </div>
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Hist&oacute;rico Real <?php echo $tFecha; ?></h4>
                  <canvas id="historicoReal" style="height:250px"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Hist&oacute;rico Peso <?php echo $tFecha; ?></h4>
                  <canvas id="historicoPeso" style="height:250px"></canvas>
                </div>
              </div>
            </div>
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Hist&oacute;rico Euro <?php echo $tFecha; ?></h4>
                  <canvas id="historicoEuro" style="height:250px"></canvas>
                </div>
              </div>
            </div>
          </div>

          <div class="row">
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Hist&oacute;rico D&oacute;lar x Real <?php echo $tFecha; ?></h4>
                  <canvas id="historicoDolarxReal" style="height:250px"></canvas>
                </div>
              </div>
            </div>
            <div class="col-lg-6 grid-margin stretch-card">
              <div class="card">
                <div class="card-body">
                  <h4 class="card-title">Hist&oacute;rico D&oacute;lar x Peso <?php echo $tFecha; ?></h4>
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
          labels: [<?php echo $charDolarCompTit05; ?>],
          datasets: [{
              label: 'Operación',
              data: [<?php echo $charDolarCompOpe05; ?>],
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
              data: [<?php echo $charDolarCompPiz05; ?>],
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
                min: <?php echo ($minDolarComp05 - 10); ?>,
                max: <?php echo ($maxDolarComp05 + 10); ?>
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
          labels: [<?php echo $charDolarVentTit05; ?>],
          datasets: [{
              label: 'Operación',
              data: [<?php echo $charDolarVentOpe05; ?>],
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
              data: [<?php echo $charDolarVentPiz05; ?>],
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
                min: <?php echo ($minDolarVent05 - 10); ?>,
                max: <?php echo ($maxDolarVent05 + 10); ?>
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
          labels: [<?php echo $charRealCompTit05; ?>],
          datasets: [{
              label: 'Operación',
              data: [<?php echo $charRealCompOpe05; ?>],
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
              data: [<?php echo $charRealCompPiz05; ?>],
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
                min: <?php echo ($minRealComp05 - 10); ?>,
                max: <?php echo ($maxRealComp05 + 10); ?>
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
          labels: [<?php echo $charRealVentTit05; ?>],
          datasets: [{
              label: 'Operación',
              data: [<?php echo $charRealVentOpe05; ?>],
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
              data: [<?php echo $charRealVentPiz05; ?>],
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
                min: <?php echo ($minRealVent05 - 10); ?>,
                max: <?php echo ($maxRealVent05 + 10); ?>
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
      });
    </script>
  </body>
</html>

<?php
  ibase_close($db);
?>