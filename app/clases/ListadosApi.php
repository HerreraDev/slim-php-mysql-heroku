<?php

require_once './auxEntidades/auxListados.php';

class ListadosApi{

    //EMPLEADOS:

    public function LoginEnSistema($request, $response, $args)
    {
        $logins = auxListados::loginEnSistema($args);
      
      $newResponse = $response->withJson($logins, 200);
      return $newResponse;
    }

    public function CantidadOperaciones($request, $response, $args){

        auxListados::cantidadOperaciones($args);
    }

    public function CantidadOperacionesPorEmpleado($request, $response, $args){

        auxListados::cantidadOperacionesPorEmpleado($args);
    }

    public function CantidadOperacionesDe($request, $response, $args){

        auxListados::cantidadOperacionesPorEmpleado($args);
    }
    //---------------------------------------------------//

    //PEDIDOS:
    

}


?>