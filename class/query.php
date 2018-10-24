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
    /*-------------------------------------------------------------------------------------------------------------------------------------*/

    $opeCompra    = 0;
    $opeVenta     = 0;
    $opeArbitraje = 0;
    $opeAsiento   = 0;
    $opeCanje     = 0;

    $wSQL03       = ibase_query("SELECT ID_TIPOOPERACION, COUNT(*)
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
/*-------------------------------------------------------------------------------------------------------------------------------------*/

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

    $wSQL04       = ibase_query("SELECT TCCOMPRABB, TCVENTABB, ID_MONEDA, HORA
                                    FROM HISTORICOSCOTIZACIONES
                                    WHERE ID_TIPOCOTIZACION = 1 AND ID_MONEDA IN (3, 4, 5, 6) AND FECHA = '$wFecha'
                                    ORDER BY HORA", $db);

    $wSQL04_1     = ibase_query("SELECT TCCOMPRABB, TCVENTABB, ID_MONEDA
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
/*-------------------------------------------------------------------------------------------------------------------------------------*/

    $banDolarComBB05 = 1;
    $banRealComBB05  = 1;
    $banPesoComBB05  = 1;
    $banEuroComBB05  = 1;

    $banDolarVenBB05 = 1;
    $banRealVenBB05  = 1;
    $banPesoVenBB05  = 1;
    $banEuroVenBB05  = 1;

    $minDolarComBB05 = 0;
    $minRealComBB05  = 0;
    $minPesoComBB05  = 0;
    $minEuroComBB05  = 0;

    $minDolarVenBB05 = 0;
    $minRealVenBB05  = 0;
    $minPesoVenBB05  = 0;
    $minEuroVenBB05  = 0;

    $maxDolarComBB05 = 0;
    $maxRealComBB05  = 0;
    $maxPesoComBB05  = 0;
    $maxEuroComBB05  = 0;

    $maxDolarVenBB05 = 0;
    $maxRealVenBB05  = 0;
    $maxPesoVenBB05  = 0;
    $maxEuroVenBB05  = 0;

    $wSQL05          = ibase_query("SELECT t1.ID_TRANSACCION, t1.HORA, t2.TCAMBIOOPERADO, t2.PIZARRA, t2.CODMONEDA, t1.ID_TIPOOPERACION
                                        FROM TRANSACCIONES t1
                                        INNER JOIN TRANSACCIONESDETALLES t2 ON t1.ID_TRANSACCION = t2.ID_TRANSACCION
                                        WHERE t1.FECHATRANSACCION = '$wFecha' AND t1.ESTADO = 'L' AND  t1.ID_TIPOOPERACION IN (1, 2) AND t2.ID_TIPOESPECIE = 1  AND t2.CODMONEDA IN (1, 9)
                                        ORDER BY  t1.ID_TRANSACCION", $db);

    while ($row05 = ibase_fetch_row($wSQL05)) {
        if ($row05[5] === 1) {
            switch ($row05[4]) {
                case 1:
                if ($banDolarComBB05 === 1) {
                    $minDolarComBB05  = number_format($row05[2], 0, '.', '');
                    $maxDolarComBB05  = number_format($row05[2], 0, '.', '');

                    if ($minDolarComBB05 > number_format($row05[3], 0, '.', '')) {
                        $minDolarComBB05 = number_format($row05[3], 0, '.', '');
                    }

                    if ($maxDolarComBB05 < number_format($row05[3], 0, '.', '')) {
                        $maxDolarComBB05 = number_format($row05[3], 0, '.', '');
                    }

                    $opeDolarComBBTit05 = '"'.($row05[0]).'"';
                    $opeDolarComBBOpe05 = number_format($row05[2], 2, '.', '');
                    $opeDolarComBBPiz05 = number_format($row05[3], 2, '.', '');
                    $banDolarComBB05    = 0;
                } else {
                    if ($minDolarComBB05 > number_format($row05[2], 0, '.', '')) {
                        $minDolarComBB05 = number_format($row05[2], 0, '.', '');
                    }
            
                    if ($minDolarComBB05 > number_format($row05[3], 0, '.', '')) {
                        $minDolarComBB05 = number_format($row05[3], 0, '.', '');
                    }
                    
                    if ($maxDolarComBB05 < number_format($row05[2], 0, '.', '')) {
                        $maxDolarComBB05 = number_format($row05[2], 0, '.', '');
                    }
            
                    if ($maxDolarComBB05 < number_format($row05[3], 0, '.', '')) {
                        $maxDolarComBB05 = number_format($row05[3], 0, '.', '');
                    }

                    $opeDolarComBBTit05 = $opeDolarComBBTit05.', "'.($row05[0]).'"';
                    $opeDolarComBBOpe05 = $opeDolarComBBOpe05.', '.number_format($row05[2], 2, '.', '');
                    $opeDolarComBBPiz05 = $opeDolarComBBPiz05.', '.number_format($row05[3], 2, '.', '');
                }
                break;

                case 9:
                if ($banRealComBB05 === 1) {
                    $minRealComBB05 = number_format($row05[2], 0, '.', '');
                    $maxRealComBB05 = number_format($row05[2], 0, '.', '');

                    if ($minRealComBB05 > number_format($row05[3], 0, '.', '')) {
                        $minRealComBB05 = number_format($row05[3], 0, '.', '');
                    }

                    if ($maxRealComBB05 < number_format($row05[3], 0, '.', '')) {
                        $maxRealComBB05 = number_format($row05[3], 0, '.', '');
                    }

                    $opeRealComBBTit05 = '"'.($row05[0]).'"';
                    $opeRealComBBOpe05 = number_format($row05[2], 2, '.', '');
                    $opeRealComBBPiz05 = number_format($row05[3], 2, '.', '');
                    $banRealComBB05    = 0;
                } else {
                    if ($minRealComBB05 > number_format($row05[2], 0, '.', '')) {
                        $minRealComBB05 = number_format($row05[2], 0, '.', '');
                    }
            
                    if ($minRealComBB05 > number_format($row05[3], 0, '.', '')) {
                        $minRealComBB05 = number_format($row05[3], 0, '.', '');
                    }
                    
                    if ($maxRealComBB05 < number_format($row05[2], 0, '.', '')) {
                        $maxRealComBB05 = number_format($row05[2], 0, '.', '');
                    }
            
                    if ($maxRealComBB05 < number_format($row05[3], 0, '.', '')) {
                        $maxRealComBB05 = number_format($row05[3], 0, '.', '');
                    }

                    $opeRealComBBTit05 = $opeRealComBBTit05.', "'.($row05[0]).'"';
                    $opeRealComBBOpe05 = $opeRealComBBOpe05.', '.number_format($row05[2], 2, '.', '');
                    $opeRealComBBPiz05 = $opeRealComBBPiz05.', '.number_format($row05[3], 2, '.', '');
                }
                break;
            }
        }

        if ($row05[5] === 2) {
            switch ($row05[4]) {
                case 1:
                if ($banDolarVenBB05 === 1) {
                    $minDolarVenBB05  = number_format($row05[2], 0, '.', '');
                    $maxDolarVenBB05  = number_format($row05[2], 0, '.', '');

                    if ($minDolarVenBB05 > number_format($row05[3], 0, '.', '')) {
                        $minDolarVenBB05 = number_format($row05[3], 0, '.', '');
                    }

                    if ($maxDolarVenBB05 < number_format($row05[3], 0, '.', '')) {
                        $maxDolarVenBB05 = number_format($row05[3], 0, '.', '');
                    }

                    $opeDolarVenBBTit05 = '"'.($row05[0]).'"';
                    $opeDolarVenBBOpe05 = number_format($row05[2], 2, '.', '');
                    $opeDolarVenBBPiz05 = number_format($row05[3], 2, '.', '');
                    $banDolarVenBB05    = 0;
                } else {
                    if ($minDolarVenBB05 > number_format($row05[2], 0, '.', '')) {
                        $minDolarVenBB05 = number_format($row05[2], 0, '.', '');
                    }
            
                    if ($minDolarVenBB05 > number_format($row05[3], 0, '.', '')) {
                        $minDolarVenBB05 = number_format($row05[3], 0, '.', '');
                    }
                    
                    if ($maxDolarVenBB05 < number_format($row05[2], 0, '.', '')) {
                        $maxDolarVenBB05 = number_format($row05[2], 0, '.', '');
                    }
            
                    if ($maxDolarVenBB05 < number_format($row05[3], 0, '.', '')) {
                        $maxDolarVenBB05 = number_format($row05[3], 0, '.', '');
                    }

                    $opeDolarVenBBTit05 = $opeDolarVenBBTit05.', "'.($row05[0]).'"';
                    $opeDolarVenBBOpe05 = $opeDolarVenBBOpe05.', '.number_format($row05[2], 2, '.', '');
                    $opeDolarVenBBPiz05 = $opeDolarVenBBPiz05.', '.number_format($row05[3], 2, '.', '');
                }
                break;

                case 9:
                if ($banRealVenBB05 === 1) {
                    $minRealVenBB05 = number_format($row05[2], 0, '.', '');
                    $maxRealVenBB05 = number_format($row05[2], 0, '.', '');

                    if ($minRealVenBB05 > number_format($row05[3], 0, '.', '')) {
                        $minRealVenBB05 = number_format($row05[3], 0, '.', '');
                    }

                    if ($maxRealVenBB05 < number_format($row05[3], 0, '.', '')) {
                        $maxRealVenBB05 = number_format($row05[3], 0, '.', '');
                    }

                    $opeRealVenBBTit05 = '"'.($row05[0]).'"';
                    $opeRealVenBBOpe05 = number_format($row05[2], 2, '.', '');
                    $opeRealVenBBPiz05 = number_format($row05[3], 2, '.', '');
                    $banRealVenBB05    = 0;
                } else {
                    if ($minRealVenBB05 > number_format($row05[2], 0, '.', '')) {
                        $minRealVenBB05 = number_format($row05[2], 0, '.', '');
                    }
            
                    if ($minRealVenBB05 > number_format($row05[3], 0, '.', '')) {
                        $minRealVenBB05 = number_format($row05[3], 0, '.', '');
                    }
                    
                    if ($maxRealVenBB05 < number_format($row05[2], 0, '.', '')) {
                        $maxRealVenBB05 = number_format($row05[2], 0, '.', '');
                    }
            
                    if ($maxRealVenBB05 < number_format($row05[3], 0, '.', '')) {
                        $maxRealVenBB05 = number_format($row05[3], 0, '.', '');
                    }

                    $opeRealVenBBTit05 = $opeRealVenBBTit05.', "'.($row05[0]).'"';
                    $opeRealVenBBOpe05 = $opeRealVenBBOpe05.', '.number_format($row05[2], 2, '.', '');
                    $opeRealVenBBPiz05 = $opeRealVenBBPiz05.', '.number_format($row05[3], 2, '.', '');
                }
                break;
            }
        }
    }

    ibase_free_result($wSQL05);
/*-------------------------------------------------------------------------------------------------------------------------------------*/

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
                                        WHERE ((t4.ID_MONEDA = 6 AND t2.ID_MONEDA = 3) OR (t4.ID_MONEDA = 3 AND t2.ID_MONEDA = 4) OR (t4.ID_MONEDA = 3 AND t2.ID_MONEDA = 5)) AND t1.FECHA >= '$wFecha' AND t1.FECHA < '$wFecha2'", $db);

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

    ibase_free_result($wSQL06);
    ibase_free_result($wSQL06_1);
/*-------------------------------------------------------------------------------------------------------------------------------------*/

    $banRealxDolarComBB07  = 1;
    $banPesoxDolarComBB07  = 1;
    $banEuroxDolarComBB07  = 1;

    $banRealxDolarVenBB07  = 1;
    $banPesoxDolarVenBB07  = 1;
    $banEuroxDolarVenBB07  = 1;

    $minRealxDolarComBB07  = 0;
    $minPesoxDolarComBB07  = 0;
    $minEuroxDolarComBB07  = 0;

    $minRealxDolarVenBB07  = 0;
    $minPesoxDolarVenBB07  = 0;
    $minEuroxDolarVenBB07  = 0;

    $maxRealxDolarComBB07  = 0;
    $maxPesoxDolarComBB07  = 0;
    $maxEuroxDolarComBB07  = 0;

    $maxRealxDolarVenBB07  = 0;
    $maxPesoxDolarVenBB07  = 0;
    $maxEuroxDolarVenBB07  = 0;

    $wSQL07                = ibase_query("SELECT t1.ID_TRANSACCION, t1.HORA, t2.PARIDAD, t2.PIZARRA_PARIDAD, t2.CODMONEDA, t1.ID_TIPOOPERACION
                                            FROM TRANSACCIONES t1
                                            INNER JOIN TRANSACCIONESDETALLES t2 ON t1.ID_TRANSACCION = t2.ID_TRANSACCION
                                            INNER JOIN TRANSACCIONESDETALLES t3 ON t1.ID_TRANSACCION = t3.ID_TRANSACCION
                                            WHERE t1.FECHATRANSACCION = '$wFecha' AND t1.ESTADO = 'L' AND t1.ID_TIPOOPERACION = 3 AND t2.ID_TIPOESPECIE = 1 AND ((t2.CODMONEDA = 1 AND t2.OP = 'C' AND t3.CODMONEDA = 9 AND t3.OP = 'V') OR (t2.CODMONEDA = 9 AND t2.OP = 'C' AND t3.CODMONEDA = 1 AND t3.OP = 'V'))
                                            ORDER BY  t1.ID_TRANSACCION", $db);
                                            
    while ($row07 = ibase_fetch_row($wSQL07)) {
        if ($row07[5] === 3) {
            switch ($row07[4]) {
                case 1:
                    if ($banRealxDolarVenBB07 === 1) {
                        $minRealxDolarVenBB07  = number_format($row07[2], 5, '.', '');
                        $maxRealxDolarVenBB07  = number_format($row07[2], 5, '.', '');

                        if ($minRealxDolarVenBB07 > number_format($row07[3], 5, '.', '')) {
                            $minRealxDolarVenBB07 = number_format($row07[3], 5, '.', '');
                        }

                        if ($maxRealxDolarVenBB07 < number_format($row07[3], 5, '.', '')) {
                            $maxRealxDolarVenBB07 = number_format($row07[3], 5, '.', '');
                        }

                        $opeRealxDolarVenBBTit07 = '"'.($row07[0]).'"';
                        $opeRealxDolarVenBBOpe07 = number_format($row07[2], 5, '.', '');
                        $opeRealxDolarVenBBPiz07 = number_format($row07[3], 5, '.', '');
                        $banRealxDolarVenBB07    = 0;
                    } else {
                        if ($minRealxDolarVenBB07 > number_format($row07[2], 5, '.', '')) {
                            $minRealxDolarVenBB07 = number_format($row07[2], 5, '.', '');
                        }
                
                        if ($minRealxDolarVenBB07 > number_format($row07[3], 5, '.', '')) {
                            $minRealxDolarVenBB07 = number_format($row07[3], 5, '.', '');
                        }
                        
                        if ($maxRealxDolarVenBB07 < number_format($row07[2], 5, '.', '')) {
                            $maxRealxDolarVenBB07 = number_format($row07[2], 5, '.', '');
                        }
                
                        if ($maxRealxDolarVenBB07 < number_format($row07[3], 5, '.', '')) {
                            $maxRealxDolarVenBB07 = number_format($row07[3], 5, '.', '');
                        }

                        $opeRealxDolarVenBBTit07 = $opeRealxDolarVenBBTit07.', "'.($row07[0]).'"';
                        $opeRealxDolarVenBBOpe07 = $opeRealxDolarVenBBOpe07.', '.number_format($row07[2], 5, '.', '');
                        $opeRealxDolarVenBBPiz07 = $opeRealxDolarVenBBPiz07.', '.number_format($row07[3], 5, '.', '');
                    }
                    break;

                case 9:
                    if ($banRealxDolarComBB07 === 1) {
                        $minRealxDolarComBB07 = number_format($row07[2], 5, '.', '');
                        $maxRealxDolarComBB07 = number_format($row07[2], 5, '.', '');

                        if ($minRealxDolarComBB07 > number_format($row07[3], 5, '.', '')) {
                            $minRealxDolarComBB07 = number_format($row07[3], 5, '.', '');
                        }

                        if ($maxRealxDolarComBB07 < number_format($row07[3], 5, '.', '')) {
                            $maxRealxDolarComBB07 = number_format($row07[3], 5, '.', '');
                        }

                        $opeRealxDolarComBBTit07 = '"'.($row07[0]).'"';
                        $opeRealxDolarComBBOpe07 = number_format($row07[2], 5, '.', '');
                        $opeRealxDolarComBBPiz07 = number_format($row07[3], 5, '.', '');
                        $banRealxDolarComBB07    = 0;
                    } else {
                        if ($minRealxDolarComBB07 > number_format($row07[2], 5, '.', '')) {
                            $minRealxDolarComBB07 = number_format($row07[2], 5, '.', '');
                        }
                
                        if ($minRealxDolarComBB07 > number_format($row07[3], 5, '.', '')) {
                            $minRealxDolarComBB07 = number_format($row07[3], 5, '.', '');
                        }
                        
                        if ($maxRealxDolarComBB07 < number_format($row07[2], 5, '.', '')) {
                            $maxRealxDolarComBB07 = number_format($row07[2], 5, '.', '');
                        }
                
                        if ($maxRealxDolarComBB07 < number_format($row07[3], 5, '.', '')) {
                            $maxRealxDolarComBB07 = number_format($row07[3], 5, '.', '');
                        }

                        $opeRealxDolarComBBTit07 = $opeRealxDolarComBBTit07.', "'.($row07[0]).'"';
                        $opeRealxDolarComBBOpe07 = $opeRealxDolarComBBOpe07.', '.number_format($row07[2], 5, '.', '');
                        $opeRealxDolarComBBPiz07 = $opeRealxDolarComBBPiz07.', '.number_format($row07[3], 5, '.', '');
                    }
                    break;
            }
        }
    }
    ibase_free_result($wSQL07);
/*-------------------------------------------------------------------------------------------------------------------------------------*/
?>
