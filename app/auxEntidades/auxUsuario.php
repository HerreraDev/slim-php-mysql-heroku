<?php

require_once './fpdf183/fpdf.php';
require_once './models/Usuario.php';
require_once './models/Ticket.php';

use App\Models\Usuario as Usuario;
use App\Models\UserLogs as UserLogs;
use App\Models\Ticket as Tickets;

use Illuminate\Database\Capsule\Manager as Capsule;

class auxUsuario
{


    public static function VerificarUsuarioDB($user)
    {

        $arrayUsuarios = array();
        $arrayUsuarios = Usuario::all();

        $verificado = -1;
        foreach ($arrayUsuarios as $usuario) {
            if ($usuario->mail == $user->mail) {

                if ($usuario->clave == $user->clave) {
                    $verificado = 1;
                    $user->empleo = $usuario->empleo;
                } else {
                    $verificado = 0;
                }
            }
        }
        return $verificado;
    }

    public static function mostrarDatos($usuario)
    {
        return $usuario->idUsuario . "," . $usuario->nombre . "," . $usuario->apellido . "," . $usuario->clave . "," . $usuario->mail . "," . $usuario->empleo . "," . $usuario->fecha_de_ingreso . "," . $usuario->ruta_foto . "," . $usuario->fecha_de_salida;
    }


    public static function ObtenerIdPorMail($mail)
    {

        $arrayUsuarios = array();
        $arrayUsuarios = Usuario::all();

        $idUsuario = -1;
        foreach ($arrayUsuarios as $usuario) {
            if ($usuario->mail == $mail) {
                $idUsuario = $usuario->idUsuario;
                break;
            }
        }
        return $idUsuario;
    }

    public static function ObtenerIdCliete($mailCliente)
    {

        $arrayUsuarios = array();
        $arrayUsuarios = Usuario::all();

        $idCliente = -1;
        foreach ($arrayUsuarios as $usuario) {
            if ($usuario->mail == $mailCliente && $usuario->empleo == "Cliente") {
                $idCliente = $usuario->idUsuario;
                break;
            }
        }
        return $idCliente;
    }



    //--------------------------------------------------//
    //CSV

    public static function GuardarEnCsv($usuario, $mode)
    {

        $direccionArchivo = fopen("csv/Usuarios.csv", $mode);

        if ($direccionArchivo != false) {
            if (fwrite($direccionArchivo, auxUsuario::mostrarDatos($usuario) . "\n") != false) {
                fclose($direccionArchivo);
                return 1;
            } else {
                fclose($direccionArchivo);
                return 0;
            }
        }
    }

    public static function GenerarCSV()
    {

        $usuarios = array();
        $usuarios = Usuario::all();

        $mode = "w";

        foreach ($usuarios as $user) {
            self::GuardarEnCsv($user, $mode);
            $mode = "a";
        }

        echo "Csv generado en la ruta /csv/Usuarios.csv";
    }



    public static function CargarDeCSV($archivo)
    {

        $archivo = "csv/carga/cargarUsuarios.csv";
        $direccionArchivo = fopen($archivo, "r");

        $arrayDatos = array();

        if($direccionArchivo != false)
        {
            while($arrayDatos = fgetcsv($direccionArchivo,1000,","))
            {

                for($i=0; $i<count($arrayDatos);$i++){
                    echo $arrayDatos[$i] . "<br />\n";
                }


                $user = new Usuario();
                $user->nombre= $arrayDatos[0];$user->apellido=$arrayDatos[1];$user->clave=$arrayDatos[2];
                $user->mail=$arrayDatos[3];
                $user->fecha_de_ingreso=$arrayDatos[4];
                $user->empleo=$arrayDatos[5];
                $user->ruta_foto=$arrayDatos[6];

                $user->save();
                
            }

            fclose($direccionArchivo);

        }
        else
        {
            echo "No existe el archivo";
        }
    }

    public static function GenerarPdf()
    {
        $lista = Usuario::all();

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        foreach ($lista as $user) {
            $pdf->Cell(40, 10, $user->nombre, 1, 0, 'C', 0);
            $pdf->Cell(40, 10, $user->apellido, 1, 0, 'C', 0);
            $pdf->Cell(40, 10, $user->empleo, 1, 0, 'C', 0);
            $pdf->Cell(40, 10, $user->fecha_de_ingreso, 1, 1, 'C', 0);
        }

        echo $pdf->Output("usuarios.pdf", "F");

        echo "Pdf de usuarios generado en /pdf/usuarios.pdf";
    }

    //------------------------------------------------------------------//
    //Listados:

    public static function listadoA(){

        $logs = UserLogs::all()->where('accion',"=", "Login");

        //$arrayDiaHoraLogin = Capsule::table('userLogs')

        return $arrayDiaHoraLogin;
    }

    
    public static function ticetksGet(){

        $logs = Tickets::all();

        return $logs;
    }

    
}
