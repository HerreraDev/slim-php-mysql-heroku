<?php

include_once "./models/Usuario.php";
require_once './auxEntidades/auxUsuario.php';
require_once './auxEntidades/auxPedido.php';

include_once "./models/Pedido.php";
include_once "./models/UserLogs.php";
include_once "./models/PedidosLogs.php";


use App\Models\Pedido as Pedido;
use App\Models\UserLogs as UserLogs;
use App\Models\PedidosLogs as PedidosLogs;

class Logs
{

    public static function LogUsuario($mail,$accion)
    {

        $idResponsable = auxUsuario::ObtenerIdPorMail($mail);
        if ($idResponsable != -1) {


            $logUsuario = new UserLogs();

            $logUsuario->id_usuario = $idResponsable;
            $logUsuario->accion = $accion;
            $logUsuario->hora_accion = date("Y-m-d H:i:s");

            $logUsuario->save();           
        }
    }

    //Loguea los siguientes datos:
    //-idPedido
    //-id_estado
    //-id_responsable
    public static function logPedido($idPedido)
    {

        $id_estadoAux = -1;
        $id_responsableAux = -1;

        $pedidos = Pedido::all();

        foreach ($pedidos as $pedido) {
            if ($pedido->idPedido == $idPedido) {
                $id_estadoAux = $pedido->id_estado;
                $id_responsableAux = $pedido->id_responsable;
                break;
            }
        }

        if ($id_estadoAux != -1 && $id_responsableAux != -1) {

            $logPedido = new PedidosLogs();
            $logPedido->id_pedido = $idPedido;
            $logPedido->id_estado = $id_estadoAux;
            $logPedido->id_responsable = $id_responsableAux;
            $logPedido->fecha_hora_log = date("Y-m-d H:i:s");

            $logPedido->save();

        }
    }
}
