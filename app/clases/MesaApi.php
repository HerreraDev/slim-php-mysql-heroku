<?php
require_once './models/Mesa.php';
require_once './clases/IApiUsable.php';

use App\Models\Mesa as Mesa;


class MesaApi implements IApiUsable
{
  public function TraerUno($request, $response, $args)
  {
    $id = $args['id'];
    $laMesa = new Mesa;
    $laMesa = $laMesa->find($id);
    $newResponse = $response->withJson($laMesa, 200);
    return $newResponse;
  }
  public function TraerTodos($request, $response, $args)
  {
    $todasLasMesas = Mesa::all();

    //Mesa::DibujarTablaMesa($todasLasMesas);

    $newResponse = $response->withJson($todasLasMesas, 200);
    return $newResponse;
  }
  public function CargarUno($request, $response, $args)
  {
    $ArrayDeParametros = $request->getParsedBody();
    //var_dump($ArrayDeParametros);
    $numero_de_mesa = $ArrayDeParametros['numero_de_mesa'];
    $max_personas = $ArrayDeParametros['max_personas'];


    $miMesa = new Mesa();

    $miMesa->numero_de_mesa = $numero_de_mesa;
    $miMesa->max_personas = $max_personas;
    $miMesa->id_estado = 0;

    $miMesa->save();

    $response->getBody()->write("se guardo el Mesa");

    return $response;
  }


  public function BorrarUno($request, $response, $args)
  {
    $id = $args['id'];
    $mesa = new Mesa();
    $mesa->find($id)->delete();


    $response->getBody()->write("Se elimino la mesa con exito");
    return $response;
  }

  public function ModificarUno($request, $response, $args)
  {
    //$response->getBody()->write("<h1>Modificar  uno</h1>");
    $ArrayDeParametros = $request->getParsedBody();
    //var_dump($ArrayDeParametros);

    $numero_de_mesa = $ArrayDeParametros['numero_de_mesa'];
    $max_personas = $ArrayDeParametros['max_personas'];


    $miMesa = new Mesa();

    $miMesa->numero_de_mesa = $numero_de_mesa;
    $miMesa->max_personas = $max_personas;

    $resultado = $miMesa->ModificarMesaParametros();
    $objDelaRespuesta = new stdclass();
    //var_dump($resultado);
    $objDelaRespuesta->resultado = $resultado;
    return $response->withJson($objDelaRespuesta, 200);
  }
}
