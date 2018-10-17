<?php
    function getRealIP() {
        if (isset($_SERVER["HTTP_CLIENT_IP"])){
            return $_SERVER["HTTP_CLIENT_IP"];
        }elseif (isset($_SERVER["HTTP_X_FORWARDED_FOR"])){
            return $_SERVER["HTTP_X_FORWARDED_FOR"];
        }elseif (isset($_SERVER["HTTP_X_FORWARDED"])){
            return $_SERVER["HTTP_X_FORWARDED"];
        }elseif (isset($_SERVER["HTTP_FORWARDED_FOR"])){
            return $_SERVER["HTTP_FORWARDED_FOR"];
        }elseif (isset($_SERVER["HTTP_FORWARDED"])){
            return $_SERVER["HTTP_FORWARDED"];
        }else{
            return $_SERVER["REMOTE_ADDR"];
        }
    }

    function getHabilitadoIP($ip) {
        $habilitado     = FALSE;
        $IPHabilitado   = array(
            '10.168.196.227' => array(
                'suc_matriz'            => 'SI',
                'suc_ciudaddeleste'     => 'SI',
                'suc_saltodelguaira'    => 'SI',
                'suc_villamorra'        => 'SI',
                'suc_encarnacion'       => 'SI',
                'age_rubionu'           => 'SI',
                'age_jebai'             => 'SI',
                'age_lailai'            => 'SI',
                'age_saltodelguaira'    => 'SI',
                'age_uniamerica'        => 'SI',
                'age_sanlorenzo'        => 'SI',
                'age_km4'               => 'SI',
                'persona'               => 'Christian Eduardo Zelaya Sosa',
                'usuario'               => 'czelaya',
                'mail'                  => 'czelaya@cambiosalberdi.com',
                'sucursal'              => 'villamorra'
            )
        );
        return $habilitado;
    }
?>