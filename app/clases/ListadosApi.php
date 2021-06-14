<?php

require_once './auxEntidades/auxListados.php';

class ListadosApi
{

    //EMPLEADOS:

    public function LoginEnSistema($request, $response, $args)
    {
        $logins = auxListados::loginEnSistema($args);

        $newResponse = $response->withJson($logins, 200);
        return $newResponse;
    }

    public function CantidadOperaciones($request, $response, $args)
    {

        auxListados::cantidadOperaciones($args);
    }

    public function CantidadOperacionesPorEmpleado($request, $response, $args)
    {

        auxListados::cantidadOperacionesPorEmpleado($args);
    }

    public function CantidadOperacionesCadaUno($request, $response, $args)
    {

        $newResponse = $response->withJson(auxListados::cantidadOperacionesCadaUno($args), 200);
        return $newResponse;
    }

    //---------------------------------------------------//
    //PEDIDOS:
    public function MasVendido($request, $response, $args)
    {

        auxListados::masVendido($args);
    }

    public function MenosVendido($request, $response, $args)
    {

        auxListados::menosVendido($args);
    }

    public function Cancelados($request, $response, $args)
    {
        $cancelados = auxListados::cancelados($args);

        $newResponse = $response->withJson($cancelados, 200);
        return $newResponse;
    }

    //---------------------------------------------------//
    //Mesas:

    public function MasUsada($request, $response, $args)
    {

        $newResponse = $response->withJson(auxListados::masUsada($args), 200);
        return $newResponse;
    }

    public function MenosUsada($request, $response, $args)
    {

        $newResponse = $response->withJson(auxListados::menosUsada($args), 200);
        return $newResponse;
    }

    public function MasFacturo($request, $response, $args)
    {

        $newResponse = $response->withJson(auxListados::masFacturo($args), 200);
        return $newResponse;
    }

    public function MenosFacturo($request, $response, $args)
    {

        $newResponse = $response->withJson(auxListados::menosFacturo($args), 200);
        return $newResponse;
    }

    public function FacturaMayorImporte($request, $response, $args)
    {

        $newResponse = $response->withJson(auxListados::facturaMayorImporte($args), 200);
        return $newResponse;
    }

    public function FacturaMenorImporte($request, $response, $args)
    {

        $newResponse = $response->withJson(auxListados::facturaMenorImporte($args), 200);
        return $newResponse;
    }

    public function FacturoEntreFechas($request, $response, $args)
    {

        $newResponse = $response->withJson(auxListados::facturoEntreFechas($args), 200);
        return $newResponse;
    }

    public function MejoresComentarios($request, $response, $args)
    {

        $newResponse = $response->withJson(auxListados::mejoresComentarios($args), 200);
        return $newResponse;
    }

    public function PeoresComentarios($request, $response, $args)
    {

        $newResponse = $response->withJson(auxListados::peoresComentarios($args), 200);
        return $newResponse;
    }
}
