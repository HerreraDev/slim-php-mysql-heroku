<?php

include_once "./models/Usuario.php";
require_once './auxEntidades/auxUsuario.php';
require_once './auxEntidades/auxPedido.php';

include_once "./models/Pedido.php";
include_once "./models/UserLogs.php";


use App\Models\Pedido as Pedido;
use App\Models\UserLogs as UserLogs;

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

            $objetoAccesoDato = AccesoDatos::dameUnObjetoAcceso();

            $consulta = $objetoAccesoDato->RetornarConsulta("
				INSERT INTO `pedidosLogs` (`id_pedido`,`id_estado`,`id_responsable`,`fecha_hora_log`) VALUES(:id_pedido,:estado,:idResponsable,:fecha_hora)");

            $consulta->bindValue(':id_pedido', $idPedido, PDO::PARAM_INT);
            $consulta->bindValue(':estado', $id_estadoAux, PDO::PARAM_INT);
            $consulta->bindValue(':idResponsable', $id_responsableAux, PDO::PARAM_INT);
            $consulta->bindValue(':fecha_hora', date("Y-m-d H:i:s"), PDO::PARAM_INT);

            $consulta->execute();
            return $consulta->rowCount();
        }
    }
}
