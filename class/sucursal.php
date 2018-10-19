<?php
    $codSucursal = $_GET['suc'];
    $nomSucursal = '';
    switch ($codSucursal) {
        case 1:
            $db          = ibase_connect('192.168.0.200:aliadocambios','sysdba','dorotea');
            $nomSucursal = 'CASA MATRIZ';
            break;
        
        case 2:
            $db          = ibase_connect('10.168.196.130:aliadocambios','sysdba','dorotea');
            $nomSucursal = 'SUCURSAL VILLA MORRA';
            break;
    
        case 3:
            $db          = ibase_connect('10.168.191.130:aliadocambios','sysdba','dorotea');
            $nomSucursal = 'AGENCIA SAN LORENZO';
            break;

        case 4:
            $db          = ibase_connect('10.168.192.138:aliadocambios','sysdba','dorotea');
            $nomSucursal = 'SUCURSAL 1 CIUDAD DEL ESTE';
            break;

        case 5:
            $db          = ibase_connect('10.168.193.130:aliadocambios','sysdba','dorotea');
            $nomSucursal = 'AGENCIA JEBAI';
            break;

        case 6:
            $db          = ibase_connect('10.168.194.130:aliadocambios','sysdba','dorotea');
            $nomSucursal = 'AGENCIA LAI LAI';
            break;

        case 7:
            $db          = ibase_connect('10.168.199.131:aliadocambios','sysdba','dorotea');
            $nomSucursal = 'AGENCIA UNIAMERICA';
            break;

        case 8:
            $db          = ibase_connect('10.168.195.130:aliadocambios','sysdba','dorotea');
            $nomSucursal = 'AGENCIA RUBIO ÑU';
            break;

        case 9:
            $db          = ibase_connect('10.168.190.130:aliadocambios','sysdba','dorotea');
            $nomSucursal = 'AGENCIA KM4';
            break;

        case 10:
            $db          = ibase_connect('10.168.198.130:aliadocambios','sysdba','dorotea');
            $nomSucursal = 'SUCURSAL SALTO DEL GUAIRA';
            break;

        case 11:
            $db          = ibase_connect('10.168.197.130:aliadocambios','sysdba','dorotea');
            $nomSucursal = 'AGENCIA SALTO DEL GUAIRA';
            break;
        
        case 12:
            $db          = ibase_connect('10.168.189.130:aliadocambios','sysdba','dorotea');
            $nomSucursal = 'SUCURSAL ENCARNACIÓN';
            break;
    }
?>