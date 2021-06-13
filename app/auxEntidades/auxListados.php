<?php

require_once './models/UserLogs.php';
require_once './models/Ticket.php';
require_once './models/Usuario.php';
require_once './models/Producto.php';

use App\Models\Pedido;
use App\Models\UserLogs as UserLogs;
use App\Models\Ticket as Ticket;
use App\Models\Usuario as Usuario;
use App\Models\Producto as Producto;

use Illuminate\Database\Capsule\Manager as Capsule;

class auxListados
{

    //--------------------------------------------------------------//
    //Personas:

    public static function loginEnSistema($fechas)
    {
        switch (count($fechas)) {
            case 0:
                $logs = UserLogs::all()->where('accion', "=", "Login");
                echo "entre en 0";
                break;
            case 2:
                $fecha1 = $fechas['fecha1'];
                $fecha2 = $fechas['fecha2'];

                $logs = Capsule::select("SELECT * FROM `userLogs` WHERE accion = 'Login' AND hora_accion BETWEEN {$fecha1} AND {$fecha2}");
                break;
            default:
                echo "ERROR, solo puede enviar 0 fechas o 2 fechas.";
                break;
        }

        //$arrayDiaHoraLogin = Capsule::table('userLogs')

        return $logs;
    }


    public static function cantidadOperaciones($fechas)
    {
        //Bar - cerveza - cocina - mozo
        switch (count($fechas)) {
            case 0:
                $cantBar = Capsule::select("SELECT idLog, id_usuario FROM `userLogs` INNER JOIN usuario ON userLogs.id_usuario = usuario.idUsuario WHERE usuario.empleo = 'Bartender'");
                $cantCerveceria = Capsule::select("SELECT idLog, id_usuario FROM `userLogs` INNER JOIN usuario ON userLogs.id_usuario = usuario.idUsuario WHERE usuario.empleo = 'Cervezero'");
                $cantCocina = Capsule::select("SELECT idLog, id_usuario FROM `userLogs` INNER JOIN usuario ON userLogs.id_usuario = usuario.idUsuario WHERE usuario.empleo = 'Cocinero'");
                break;
            case 2:
                $fecha1 = $fechas['fecha1'];
                $fecha2 = $fechas['fecha2'];

                $cantBar = Capsule::select("SELECT idLog, id_usuario FROM `userLogs` INNER JOIN usuario ON userLogs.id_usuario = usuario.idUsuario WHERE usuario.empleo = 'Bartender'AND hora_accion BETWEEN {$fecha1} AND {$fecha2}");
                $cantCerveceria = Capsule::select("SELECT idLog, id_usuario FROM `userLogs` INNER JOIN usuario ON userLogs.id_usuario = usuario.idUsuario WHERE usuario.empleo = 'Cervezero'AND hora_accion BETWEEN {$fecha1} AND {$fecha2}");
                $cantCocina = Capsule::select("SELECT idLog, id_usuario FROM `userLogs` INNER JOIN usuario ON userLogs.id_usuario = usuario.idUsuario WHERE usuario.empleo = 'Cocinero'AND hora_accion BETWEEN {$fecha1} AND {$fecha2}");
                break;
            default:
                echo "ERROR, solo puede enviar 0 fechas o 2 fechas.";
                break;
        }


        echo "Acciones del Bar: ", count($cantBar), "<br/>";
        echo "Acciones de la barra Chopera: ", count($cantCerveceria), "<br/>";
        echo "Acciones de la Cocina: ", count($cantCocina), "<br/>";
        echo "Acciones del Candy Bar: ", count($cantCocina), "<br/>";
    }

    public static function cantidadOperacionesPorEmpleado($fechas)
    {

        $usuarios = Usuario::all();

        $idsUsuarios = array();

        foreach ($usuarios as $user) {
            if ($user->empleo != "Cliente") {
                array_push($idsUsuarios, $user->idUsuario);
            }
        }
        switch (count($fechas)) {
            case 0:
                for ($i = 0; $i < count($idsUsuarios); $i++) {
                    $cantBar = Capsule::select("SELECT idLog, id_usuario FROM `userLogs` INNER JOIN usuario ON userLogs.id_usuario = usuario.idUsuario WHERE usuario.empleo = 'Bartender' AND id_usuario = {$idsUsuarios[$i]}");
                    $cantCerveceria = Capsule::select("SELECT idLog, id_usuario FROM `userLogs` INNER JOIN usuario ON userLogs.id_usuario = usuario.idUsuario WHERE usuario.empleo = 'Cervezero' AND id_usuario = {$idsUsuarios[$i]}");
                    $cantCocina = Capsule::select("SELECT idLog, id_usuario FROM `userLogs` INNER JOIN usuario ON userLogs.id_usuario = usuario.idUsuario WHERE usuario.empleo = 'Cocinero' AND id_usuario = {$idsUsuarios[$i]}");

                    echo "Cantidad de operaciones del usuario: ", $usuarios[$i]->mail, "<br/>";
                    echo "Acciones del Bar: ", count($cantBar), "<br/>";
                    echo "Acciones de la barra Chopera: ", count($cantCerveceria), "<br/>";
                    echo "Acciones de la Cocina: ", count($cantCocina), "<br/>";
                    echo "Acciones del Candy Bar: ", count($cantCocina), "<br/>", "<br/>";
                    echo "----------------------------------------------------", "<br/>";
                }
                break;
            case 2:
                $fecha1 = $fechas['fecha1'];
                $fecha2 = $fechas['fecha2'];

                for ($i = 0; $i < count($idsUsuarios); $i++) {
                    $cantBar = Capsule::select("SELECT idLog, id_usuario FROM `userLogs` INNER JOIN usuario ON userLogs.id_usuario = usuario.idUsuario WHERE usuario.empleo = 'Bartender' AND id_usuario = {$idsUsuarios[$i]} AND hora_accion BETWEEN {$fecha1} AND {$fecha2}");
                    $cantCerveceria = Capsule::select("SELECT idLog, id_usuario FROM `userLogs` INNER JOIN usuario ON userLogs.id_usuario = usuario.idUsuario WHERE usuario.empleo = 'Cervezero' AND id_usuario = {$idsUsuarios[$i]} AND hora_accion BETWEEN {$fecha1} AND {$fecha2}");
                    $cantCocina = Capsule::select("SELECT idLog, id_usuario FROM `userLogs` INNER JOIN usuario ON userLogs.id_usuario = usuario.idUsuario WHERE usuario.empleo = 'Cocinero' AND id_usuario = {$idsUsuarios[$i]} AND hora_accion BETWEEN {$fecha1} AND {$fecha2}");

                    echo "Entre las fechas {$fecha1} - {$fecha2}", "<br/>";
                    echo "Cantidad de operaciones del usuario: ", $usuarios[$i]->mail, "<br/>";
                    echo "Acciones del Bar: ", count($cantBar), "<br/>";
                    echo "Acciones de la barra Chopera: ", count($cantCerveceria), "<br/>";
                    echo "Acciones de la Cocina: ", count($cantCocina), "<br/>";
                    echo "Acciones del Candy Bar: ", count($cantCocina), "<br/>", "<br/>";
                    echo "----------------------------------------------------", "<br/>";
                }
                break;
            default:
                echo "ERROR, solo puede enviar 0 fechas o 2 fechas.";
                break;
        }
    }

    //--------------------------------------------------------------//
    //Pedidos:

    public static function masVendido($fechas)
    {

        $ventas = array();

        $productos = Producto::all();
        $idProductos = array();

        foreach ($productos as $prods) {
            array_push($idProductos, $prods->idProducto);
        }


        switch (count($fechas)) {
            case 0:
                for ($i = 0; $i < count($idProductos); $i++) {
                    $auxIds = $idProductos[$i];
                    $auxCantVenta = Capsule::select("SELECT sum(cantidad) AS cantidad FROM `pedidos` WHERE id_producto = {$auxIds}");
                    array_push($ventas, $auxCantVenta);
                }
                break;
            case 2:
                $fecha1 = $fechas['fecha1'];
                $fecha2 = $fechas['fecha2'];

                for ($i = 0; $i < count($idProductos); $i++) {
                    $auxIds = $idProductos[$i];
                    $auxCantVenta = Capsule::select("SELECT sum(cantidad) FROM `pedidos` WHERE id_producto = {$auxIds} AND fecha_hora_de_ingreso BETWEEN {$fecha1} AND {$fecha2}");
                    array_push($ventas, $auxCantVenta);
                }
                break;
        }

        $max = max($ventas);
        $index = -1;
        for ($i = 0; $i < count($ventas); $i++) {
            if ($ventas[$i] == $max) {
                $index = $i;
            }
        }

        $productMasVendido = $productos[$index]->nombre;

        $string = "El producto mas vendido es: {$productMasVendido}";
        echo $string;
        echo json_encode($max);
    }

    public static function menosVendido($fechas)
    {
        $ventas = array();

        $productos = Producto::all();
        $idProductos = array();

        foreach ($productos as $prods) {
            array_push($idProductos, $prods->idProducto);
        }


        switch (count($fechas)) {
            case 0:
                for ($i = 0; $i < count($idProductos); $i++) {
                    $auxIds = $idProductos[$i];
                    $auxCantVenta = Capsule::select("SELECT sum(cantidad) AS cantidad FROM `pedidos` WHERE id_producto = {$auxIds}");
                    array_push($ventas, $auxCantVenta);
                }
                break;
            case 2:
                $fecha1 = $fechas['fecha1'];
                $fecha2 = $fechas['fecha2'];

                for ($i = 0; $i < count($idProductos); $i++) {
                    $auxIds = $idProductos[$i];
                    $auxCantVenta = Capsule::select("SELECT sum(cantidad) FROM `pedidos` WHERE id_producto = {$auxIds} AND fecha_hora_de_ingreso BETWEEN {$fecha1} AND {$fecha2}");
                    array_push($ventas, $auxCantVenta);
                }
                break;
        }

        $max = min($ventas);
        $index = -1;
        for ($i = 0; $i < count($ventas); $i++) {
            if ($ventas[$i] == $max) {
                $index = $i;
            }
        }

        $productMasVendido = $productos[$index]->nombre;

        $string = "El producto menos vendido es: {$productMasVendido}";
        echo $string;
        echo json_encode($max);
    }

    public static function cancelados($fechas)
    {



        switch (count($fechas)) {
            case 0:
                $cancelados = Pedido::all()->where("fecha_eliminacion", "IS NOT", "null");
                break;
            case 2:
                $fecha1 = $fechas['fecha1'];
                $fecha2 = $fechas['fecha2'];
                $cancelados = Capsule::select("SELECT * FROM `pedidos` WHERE fecha_eliminacion IS NOT null AND fecha_hora_de_ingreso BETWEEN {$fecha1} AND {$fecha2}");
                break;
        }

        return $cancelados;
        
    }

    //--------------------------------------------------------------//
    //Mesas:
    public static function masUsada($fechas){
        switch(count($fechas)){
            case 0:
                $masUsada = Capsule::select("select id_mesa, COUNT(id_mesa) AS veces_usada from pedidos GROUP BY id_mesa ORDER BY COUNT(veces_usada) DESC");
                break;
            case 2;
            $fecha1 = $fechas['fecha1'];
            $fecha2 = $fechas['fecha2'];
                $masUsada= Capsule::select("select id_mesa, COUNT(id_mesa) AS veces_usada from pedidos WHERE fecha_hora_de_ingreso BETWEEN {$fecha1} AND {$fecha2} GROUP BY id_mesa ORDER BY COUNT(id_mesa) DESC");
                break;
        }

        return $masUsada;
    }

    public static function menosUsada($fechas){
        switch(count($fechas)){
            case 0:
                $menosUsada = Capsule::select("select id_mesa, COUNT(id_mesa) AS veces_usada from pedidos GROUP BY id_mesa ORDER BY COUNT(veces_usada) ASC");
                break;
            case 2;
            $fecha1 = $fechas['fecha1'];
            $fecha2 = $fechas['fecha2'];
                $menosUsada= Capsule::select("select id_mesa, COUNT(id_mesa) AS veces_usada from pedidos WHERE fecha_hora_de_ingreso BETWEEN {$fecha1} AND {$fecha2} GROUP BY id_mesa ORDER BY COUNT(id_mesa) ASC");
                break;
        }

        return $menosUsada;
    }

    public static function masFacturo($fechas){
        switch(count($fechas)){
            case 0:
                $masFacturo = Capsule::select("select idMesa, SUM(total) AS totalFacturado from tickets GROUP BY idMesa ORDER BY totalFacturado DESC");
                break;
            case 2;
            $fecha1 = $fechas['fecha1'];
            $fecha2 = $fechas['fecha2'];
                $masFacturo= Capsule::select("select idMesa, SUM(total) AS totalFacturado from tickets WHERE fecha_hora_salida BETWEEN {$fecha1} AND {$fecha2} GROUP BY idMesa ORDER BY totalFacturado DESC");
                break;
        }

        return $masFacturo[0];
    }

    public static function menosFacturo($fechas){
        switch(count($fechas)){
            case 0:
                $menosFacturo = Capsule::select("select idMesa, SUM(total) AS totalFacturado from tickets GROUP BY idMesa ORDER BY totalFacturado ASC");
                break;
            case 2;
            $fecha1 = $fechas['fecha1'];
            $fecha2 = $fechas['fecha2'];
                $menosFacturo= Capsule::select("select idMesa, SUM(total) AS totalFacturado from tickets WHERE fecha_hora_salida BETWEEN {$fecha1} AND {$fecha2} GROUP BY idMesa ORDER BY totalFacturado ASC");
                break;
        }

        return $menosFacturo[0];
    }

    public static function facturaMayorImporte($fechas){

        $facturasIguales = array();

        switch(count($fechas)){
            case 0:
                $facturaMayorImporte = Capsule::select("select idMesa, SUM(total) AS totalFacturado from tickets GROUP BY idMesa ORDER BY totalFacturado ASC");
                break;
            case 2;
            $fecha1 = $fechas['fecha1'];
            $fecha2 = $fechas['fecha2'];
                $facturaMayorImporte= Capsule::select("select idMesa, SUM(total) AS totalFacturado from tickets WHERE fecha_hora_salida BETWEEN {$fecha1} AND {$fecha2} GROUP BY idMesa ORDER BY totalFacturado ASC");
                break;
        }


        $valores = array();
        foreach($facturaMayorImporte as $fact){
            array_push($valores,$fact->totalFacturado);
        }

        $maximo = max($valores);

        for($i=0; $i<count($facturaMayorImporte);$i++){
            if($facturaMayorImporte[$i]->totalFacturado != $maximo){  
                unset($facturaMayorImporte[$i]); 
            }
        }
        return $facturaMayorImporte;
    }

    public static function facturaMenorImporte($fechas){

        $facturasIguales = array();

        switch(count($fechas)){
            case 0:
                $facturaMenorImporte = Capsule::select("select idMesa, SUM(total) AS totalFacturado from tickets GROUP BY idMesa ORDER BY totalFacturado ASC");
                break;
            case 2;
            $fecha1 = $fechas['fecha1'];
            $fecha2 = $fechas['fecha2'];
                $facturaMenorImporte= Capsule::select("select idMesa, SUM(total) AS totalFacturado from tickets WHERE fecha_hora_salida BETWEEN {$fecha1} AND {$fecha2} GROUP BY idMesa ORDER BY totalFacturado ASC");
                break;
        }


        $valores = array();
        foreach($facturaMenorImporte as $fact){
            array_push($valores,$fact->totalFacturado);
        }

        $maximo = min($valores);

        for($i=0; $i<count($facturaMenorImporte);$i++){
            if($facturaMenorImporte[$i]->totalFacturado != $maximo){  
                unset($facturaMenorImporte[$i]); 
            }
        }
        return $facturaMenorImporte;
    }

    public static function facturoEntreFechas($fechas){
        switch(count($fechas)){
            case 0:
                echo "ERROR. Debe ingresar dos fechas";
                break;
            case 2;
            $fecha1 = $fechas['fecha1'];
            $fecha2 = $fechas['fecha2'];
                $facturaEntreFechas= Capsule::select("select idMesa, SUM(total) AS totalFacturado from tickets WHERE fecha_hora_salida BETWEEN {$fecha1} AND {$fecha2} GROUP BY idMesa");
                break;
        }

        return $facturaEntreFechas;
    }

    public static function mejoresComentarios($fechas){

        $facturasIguales = array();

        switch(count($fechas)){
            case 0:
                $puntuacionMesa = Capsule::select("SELECT encuesta.numero_pedido, encuesta.mesa AS Puntuacion, pedidos.id_mesa FROM `encuesta` INNER JOIN pedidos ON encuesta.numero_pedido = pedidos.numero_pedido");
                break;
            case 2;
            $fecha1 = $fechas['fecha1'];
            $fecha2 = $fechas['fecha2'];
                $puntuacionMesa= Capsule::select("SELECT encuesta.numero_pedido, encuesta.mesa AS Puntuacion, pedidos.id_mesa FROM `encuesta` INNER JOIN pedidos ON encuesta.numero_pedido = pedidos.numero_pedido WHERE fecha_hora_encuesta BETWEEN {$fecha1} AND {$fecha2}");
                break;
        }


        $valores = array();
        foreach($puntuacionMesa as $fact){
            array_push($valores,$fact->Puntuacion);
        }

        $maximo = max($valores);

        for($i=0; $i<count($puntuacionMesa);$i++){
            if($puntuacionMesa[$i]->Puntuacion != $maximo){  
                unset($puntuacionMesa[$i]); 
            }
        }
        return $puntuacionMesa;
    }

    public static function peoresComentarios($fechas){

        $facturasIguales = array();

        switch(count($fechas)){
            case 0:
                $puntuacionMesa = Capsule::select("SELECT encuesta.numero_pedido, encuesta.mesa AS Puntuacion, pedidos.id_mesa FROM `encuesta` INNER JOIN pedidos ON encuesta.numero_pedido = pedidos.numero_pedido");
                break;
            case 2;
            $fecha1 = $fechas['fecha1'];
            $fecha2 = $fechas['fecha2'];
                $puntuacionMesa= Capsule::select("SELECT encuesta.numero_pedido, encuesta.mesa AS Puntuacion, pedidos.id_mesa FROM `encuesta` INNER JOIN pedidos ON encuesta.numero_pedido = pedidos.numero_pedido WHERE fecha_hora_encuesta BETWEEN {$fecha1} AND {$fecha2}");
                break;
        }


        $valores = array();
        foreach($puntuacionMesa as $fact){
            array_push($valores,$fact->Puntuacion);
        }
        

        $numeros = array();
        foreach($puntuacionMesa as $fact){
            array_push($numeros,$fact->numero_pedido);
        }

        $minimo = min($valores);

        for($i=0; $i<count($puntuacionMesa);$i++){
            if($puntuacionMesa[$i]->Puntuacion != $minimo && $puntuacionMesa[$i]->numero_pedido == $numeros[$i]){  
                unset($puntuacionMesa[$i]); 
            }
        }
        return $puntuacionMesa;
    }



}
