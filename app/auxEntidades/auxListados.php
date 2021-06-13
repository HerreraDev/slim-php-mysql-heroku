<?php

require_once './models/UserLogs.php';
require_once './models/Ticket.php';
require_once './models/Usuario.php';

use App\Models\UserLogs as UserLogs;
use App\Models\Ticket as Ticket;
use App\Models\Usuario as Usuario;

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

                    echo "Entre las fechas {$fecha1} - {$fecha2}","<br/>";
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
}
