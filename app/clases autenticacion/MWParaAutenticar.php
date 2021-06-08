<?php

class MWParaAutenticar
{


  public function VerificarUsuario($request, $response, $next)
  {
    $objDelaRespuesta = new stdclass();
    $objDelaRespuesta->respuesta = "";

    if ($request->isGet()) {
      $response->getBody()->write('<p>NO necesita credenciales para los get</p>');


      $response = $next($request, $response);
    } else if ($request->isPut()) {
      $response = $next($request, $response);
    } else {

      $header = $request->getHeaderLine('Authorization');
      $token = trim(explode("Bearer", $header)[1]);


      try {
        AutentificadorJWT::verificarToken($token);
        $objDelaRespuesta->esValido = true;
      } catch (Exception $e) {
        //guardar en un log
        $objDelaRespuesta->excepcion = $e->getMessage();
        $objDelaRespuesta->esValido = false;
      }


      if ($objDelaRespuesta->esValido) {
        $payload = AutentificadorJWT::ObtenerData($token);

        if ($payload->empleo == "Socio") {
          $response = $next($request, $response);
        } else {
          $objDelaRespuesta->respuesta = "ERROR. Solo socios puede alterar la base de datos de productos.";
        }
      } else {
        $objDelaRespuesta->respuesta = "Usuario no registrado";
      }

      if ($objDelaRespuesta->respuesta != "") {
        $nueva = $response->withJson($objDelaRespuesta, 401);
        return $nueva;
      }
    }


    return $response;
  }

  public function EsAdmin($request, $response, $next)
  {
    $objDelaRespuesta = new stdclass();
    $objDelaRespuesta->respuesta = "";

    $header = $request->getHeaderLine('Authorization');
    $token = trim(explode("Bearer", $header)[1]);


    try {
      AutentificadorJWT::verificarToken($token);
      $objDelaRespuesta->esValido = true;
    } catch (Exception $e) {
      //guardar en un log
      $objDelaRespuesta->excepcion = $e->getMessage();
      $objDelaRespuesta->esValido = false;
    }


    if ($objDelaRespuesta->esValido) {
      $payload = AutentificadorJWT::ObtenerData($token);

      if ($payload->empleo == "Socio") {
        $response = $next($request, $response);
      } else {
        $objDelaRespuesta->respuesta = "ERROR. Solo socios puede alterar la base de datos de productos.";
      }
    } else {
      $objDelaRespuesta->respuesta = "Usuario no registrado";
    }

    if ($objDelaRespuesta->respuesta != "") {
      $nueva = $response->withJson($objDelaRespuesta, 401);
      return $nueva;
    }



    return $response;
  }

  public function VerificarEmpleoParaPendientes($request, $response, $next)
  {


    if ($request->isGet()) {
      $response->getBody()->write('<p>Verifico credenciales</p>');
      $mail = "d";



      if ($mail == "x") {
        $response->getBody()->write("<h3>Bienvenido $mail </h3>");
        $response = $next($request, $response);
      } else {
        $response->getBody()->write('<p>no tenes habilitado el ingreso</p>');
      }

      $response->getBody()->write('<p>vuelvo del verificador de credenciales</p>');
    }
    return $response;
  }
}
