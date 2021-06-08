<?php
require_once './models/Usuario.php';
require_once './auxEntidades/auxUsuario.php';
require_once './Logs/Logs.php';
require_once './clases/IApiUsable.php';
require_once './clases autenticacion/AutentificadorJWT.php';

use App\Models\Usuario as Usuario;

class usuarioApi implements IApiUsable
{
  public function TraerUno($request, $response, $args)
  {
    $id = $args['id'];
    $elUsuario = new Usuario;
    $elUsuario = $elUsuario->find($id);
    $newResponse = $response->withJson($elUsuario, 200);
    return $newResponse;
  }
  public function TraerTodos($request, $response, $args)
  {
    $lista = Usuario::all();
    $payload = json_encode(array("listaUsuario" => $lista));

    $response->getBody()->write($payload);
    return $response
      ->withHeader('Content-Type', 'application/json');
  }
  public function CargarUno($request, $response, $args)
  {
    $ArrayDeParametros = $request->getParsedBody();
    //var_dump($ArrayDeParametros);
    $nombre = $ArrayDeParametros['nombre'];
    $apellido = $ArrayDeParametros['apellido'];
    $clave = $ArrayDeParametros['clave'];
    $mail = $ArrayDeParametros['mail'];
    $empleo = $ArrayDeParametros['empleo'];
    $fecha_de_ingreso = date("Y-m-d");

    if ($empleo != "Bartender" && $empleo != "Cervecero" && $empleo != "Cocinero" && $empleo != "Mozo" && $empleo != "Socio" && $empleo != "Cliente") {
      $response->getBody()->write("ERROR. Solo se pueden ingresar los siguientes empleos: Bartender - Cervecero - Cocinero - Mozo - Socio o Cliente. ¡¡RECUERDE RESPETAR MAYUSCULAS Y MINUSCULAS!!");
      return $response;
    }

    $miUsuario = new Usuario();
    $miUsuario->nombre = $nombre;
    $miUsuario->apellido = $apellido;
    $miUsuario->clave = $clave;
    if($empleo == "Cliente"){
      $miUsuario->clave = "Sin clave";
    }
    $miUsuario->mail = $mail;
    $miUsuario->empleo = $empleo;
    $miUsuario->fecha_de_ingreso = $fecha_de_ingreso;

    $archivos = $request->getUploadedFiles();
    $destino = "./fotos/usuarios/";
    //var_dump($archivos);
    //var_dump($archivos['foto']);

    $nombreAnterior = $archivos['foto']->getClientFilename();
    $extension = explode(".", $nombreAnterior);
    //var_dump($nombreAnterior);
    $extension = array_reverse($extension);

    $archivos['foto']->moveTo($destino . $mail . "." . $extension[0]);

    $miUsuario->ruta_foto = $destino . $mail . "." . $extension[0];

    



    if (auxUsuario::VerificarUsuarioDB($miUsuario) == 0 || auxUsuario::VerificarUsuarioDB($miUsuario) == 1) {
      $response->getBody()->write("ERROR. Ya existe un usuario registrado con ese mail, ingrese otro.");
    } else {
      $miUsuario->save();
      $response->getBody()->write("Se registro el usuario con exito");
    }








    return $response;
  }


  public function BorrarUno($request, $response, $args)
  {
    $id = $args['id'];
    $usuario = new Usuario();
    $usuario->find($id)->delete();


    $response->getBody()->write("Se elimino el usuario con exito");
    return $response;
  }

  public function ModificarUno($request, $response, $args)
  {
    //$response->getBody()->write("<h1>Modificar  uno</h1>");
    $ArrayDeParametros = $request->getParsedBody();
    //var_dump($ArrayDeParametros);

    $id = $ArrayDeParametros['id'];
    $nombre = $ArrayDeParametros['nombre'];
    $apellido = $ArrayDeParametros['apellido'];
    $clave = $ArrayDeParametros['clave'];
    $mail = $ArrayDeParametros['mail'];
    $empleo = $ArrayDeParametros['empleo'];

    $miUsuario = new Usuario();

    $elUserModif = $miUsuario->find($id);

    $elUserModif->idUsuario = $id;
    $elUserModif->nombre = $nombre;
    $elUserModif->apellido = $apellido;
    $elUserModif->clave = $clave;
    $elUserModif->mail = $mail;
    $elUserModif->empleo = $empleo;

    $elUserModif->save();

    return $response->withJson($elUserModif, 200);
  }

  public function LoginUsuario($request, $response, $args)
  {
    $ArrayDeParametros = $request->getParsedBody();

    $mail = $ArrayDeParametros['mail'];
    $clave = $ArrayDeParametros['clave'];

    $user = new Usuario();
    $user->mail = $mail;
    $user->clave = $clave;

    $respuesta = auxUsuario::VerificarUsuarioDB($user);

    switch ($respuesta) {
      case -1:
        echo "No existe";
        break;
      case 0:
        echo "Mail correcto pero clave incorrecta";
        break;
      case 1:
        $datos = ["empleo" => $user->empleo, "mail" => $user->mail];
        echo AutentificadorJWT::CrearToken($datos);

        Logs::LogUsuario($user->mail, "Login");
        break;
    };
  }
}
