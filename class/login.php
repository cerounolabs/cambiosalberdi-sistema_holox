<?php
    session_start();
    $user00 = strtoupper($_POST['userLogin']);
    $pass00 = $_POST['passLogin'];

    $suc_array  = array(
        "suc_matriz"            => "192.168.0.200:aliadocambios",
        "suc_villamorra"        => "10.168.196.130:aliadocambios",
        "age_sanlorenzo"        => "10.168.191.130:aliadocambios",
        "suc_ciudaddeleste"     => "10.168.196.130:aliadocambios",
        "age_jebai"             => "10.168.193.130:aliadocambios",
        "age_lailai"            => "10.168.194.130:aliadocambios",
        "age_uniamerica"        => "10.168.199.131:aliadocambios",
        "age_rubionu"           => "10.168.195.130:aliadocambios",
        "age_km4"               => "10.168.190.130:aliadocambios",
        "suc_saltodelguaira"    => "10.168.198.130:aliadocambios",
        "age_saltodelguaira"    => "10.168.197.130:aliadocambios",
        "suc_encarnacion"       => "10.168.189.130:aliadocambios"
    );

    foreach($suc_array as $suc_key => $suc_ip) {
        $str_db         = $suc_ip;
        $str_user       = 'sysdba';
        $str_pass       = 'dorotea';
        $str_connect    = ibase_connect($str_db, $str_user, $str_pass) OR DIE("NO SE CONECTO AL SERVIDOR: ".ibase_errmsg());
        $wSQL00         = ibase_query("SELECT ID_USUARIO, LOGIN, CLAVE, ESTADO, SYSHOLOX
                                        FROM USUARIOS
                                        WHERE LOGIN = '$user00' AND CLAVE = '$pass00' AND ESTADO = 'A'", $str_connect);

        while ($row00 = ibase_fetch_row($wSQL00)) {
            if ($row00[4] === 'S') {
                $acceso00          = TRUE;
                $_SESSION['Sys00'] = session_id();
                $_SESSION['Sys01'] = $row00[0];
                $_SESSION['Sys02'] = $row00[1];
                $_SESSION['Sys03'] = $acceso00;
                break;
            } else {
                $acceso00          = FALSE;
            }
        }

        if ($acceso00 === TRUE) {
            break;
        }
    }

    if ($acceso00 === TRUE) {
        header('Location: ../pages/dashboard.php?suc=1');
    } else {
        header('Location: ../class/logout.php');
    }

    ibase_free_result($wSQL00);
?>