<?php

require_once './auxEntidades/auxPedido.php';
require_once './auxEntidades/auxProducto.php';
require_once './auxEntidades/auxMesa.php';


require_once './models/Mesa.php';
require_once './models/Pedido.php';
require_once './models/Producto.php';
require_once './models/Usuario.php';
require_once './models/Encuesta.php';

require_once './Logs/Logs.php';



require_once './clases/IApiUsable.php';

use App\Models\Pedido as Pedido;
use App\Models\Mesa as Mesa;
use App\Models\Encuesta as Encuesta;
use Illuminate\Database\Capsule\Manager as Capsule;


class PedidoApi implements IApiUsable
{
    public function TraerUno($request, $response, $args)
    {
        $id = $args['id'];
        $elPedido = new Pedido;
        $elPedido = $elPedido->find($id);
        $newResponse = $response->withJson($elPedido, 200);
        return $newResponse;
    }
    public function TraerTodos($request, $response, $args)
    {
        $todosLosPedidos = Pedido::all();

        //Pedido::DibujarTablaPedido($todosLosPedidos);

        $newResponse = $response->withJson($todosLosPedidos, 200);
        return $newResponse;
    }

    public function CargarUno($request, $response, $args)
    {
        $ArrayDeParametros = $request->getParsedBody();
        //var_dump($ArrayDeParametros);

        $miPedido = new Pedido();

        $numero_pedido = auxPedido::AlfanumericoRandom(5);
        $mailCliente = $ArrayDeParametros['mailCliente'];
        $numero_mesa = $ArrayDeParametros['numero_mesa'];
        $nombre_producto = $ArrayDeParametros['nombre_producto'];
        $cantidad = $ArrayDeParametros['cantidad'];
        $mail_responsable = $ArrayDeParametros['mail_responsable'];
        $fecha_hora_de_ingreso = date("Y-m-d H:i:s");

        //obtengo el id_mesa
        $idMesa = auxMesa::ObtenerIdPorNumeroMesa($numero_mesa);
        if ($idMesa == -1) {
            $response->getBody()->write("ERROR. No existe una mesa con ese numero o la mesa esta en uso.");
            return $response;
        }

        //obtengo el id_usuario que seria el cliente
        $idCliente = auxUsuario::ObtenerIdCliete($mailCliente);
        if ($idCliente == -1) {
            echo "El cliente no estaba registrado por lo que el dato quedara en -1, es decir, con cliente sin especifiar";
        }

        //obtengo el id_producto
        $productos = explode("/", $nombre_producto);
        $cantidades = explode("/", $cantidad);
        for ($i = 0; $i < count($productos); $i++) {

            $idProducto = auxProducto::ObtenerIdProductoPorNombre($productos[$i]);
            if ($idProducto == -1) {
                $response->getBody()->write("ERROR. No existe un producto con el nombre: " . $productos[$i]);
                return $response;
            } else {

                //obtengo el id_responsable
                $idResponsable = auxUsuario::ObtenerIdPorMail($mail_responsable);
                if ($idResponsable == -1) {
                    $response->getBody()->write("ERROR. No existe un usuario con ese mail.");
                    return $response;
                }




                $miPedido->numero_pedido = $numero_pedido;
                $miPedido->id_usuario = $idCliente;
                $miPedido->id_estado = 0;
                $miPedido->fecha_hora_de_ingreso = $fecha_hora_de_ingreso;
                $miPedido->id_mesa = $idMesa;
                $miPedido->id_responsable = $idResponsable;
                $miPedido->tiempo_estimado = 0;


                $miPedido->cantidad = $cantidades[$i];
                $miPedido->id_producto = $idProducto;
                $miPedido->precio_final = auxProducto::CalcularPrecioFinal($miPedido);

                $miPedido->save();



                $response->getBody()->write("Se inserto el pedido del producto: " . $productos[$i]);
            }
        }

        //Actualizo estado de la mesa
        $auxMesa = new Mesa();
        $MesaAct = $auxMesa->find($idMesa);
        $MesaAct->id_estado = 3;
        $MesaAct->save();











        /*
        $archivos = $request->getUploadedFiles();
        $destino="./fotos/Pedidos/";
        //var_dump($archivos);
        //var_dump($archivos['foto']);

        $nombreAnterior=$archivos['foto']->getClientFilename();
        $extension= explode(".", $nombreAnterior)  ;
        //var_dump($nombreAnterior);
        $extension=array_reverse($extension);

        $archivos['foto']->moveTo($destino.$nombre.".".$extension[0]);
        */

        return $response;
    }


    public function BorrarUno($request, $response, $args)
    {
        /*$id=$args['id'];
        $Pedido= new Pedido();
        $Pedido->idPedido=$id;
        $cantidadDeBorrados=$Pedido->BorrarPedido();

        $objDelaRespuesta= new stdclass();
       $objDelaRespuesta->cantidad=$cantidadDeBorrados;
       if($cantidadDeBorrados>0)
           {
                $objDelaRespuesta->resultado="algo borro!!!";
           }
           else
           {
               $objDelaRespuesta->resultado="no Borro nada!!!";
           }
       $newResponse = $response->withJson($objDelaRespuesta, 200);
       
        return $newResponse;
        */
    }

    public function ModificarUno($request, $response, $args)
    {
    }



    //esto esta sin terminar
    public function TraerPedidoPendiente($request, $response, $args)
    {

        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);

        $payload = AutentificadorJWT::ObtenerData($token);


        switch ($payload->empleo) {
            case "Socio":
                $todosLosPedidos = auxPedido::TraerPendientes("Socio");
                break;
            case "Mozo":
                $todosLosPedidos = auxPedido::TraerPendientes("Mozo");
                break;
            case "Bartender":
                $todosLosPedidos = auxPedido::TraerPendientes("Bartender");
                break;
            case "Cervezero":
                $todosLosPedidos = auxPedido::TraerPendientes("Cervezero");
                break;
            case "Cocinero":
                $todosLosPedidos = auxPedido::TraerPendientes("Cocinero");
                break;
            default:
                echo "ERROR, el usuario no es de los esperados.";
                break;
        }

        $newResponse = $response->withJson($todosLosPedidos, 200);

        return $newResponse;
    }

    public function TomarPedidoPendiente($request, $response, $args)
    {

        //datos del pedido
        $ArrayDeParametros = $request->getParsedBody();
        $idPedido = $ArrayDeParametros['idPedido'];
        $tiempo_estimado = $ArrayDeParametros['tiempo_estimado'];
        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $payload = AutentificadorJWT::ObtenerData($token);

        //obtengo el id_responsable
        $idResponsable = auxUsuario::ObtenerIdPorMail($payload->mail);
        if ($idResponsable == -1) {
            $response->getBody()->write("ERROR. No existe un usuario con ese mail.");
            return $response;
        } else {
            $resultado = auxPedido::TomarPedido($idPedido, $tiempo_estimado, $idResponsable);

            Logs::logPedido($idPedido);
            Logs::LogUsuario($payload->mail, "Tomo pedido");


            $objDelaRespuesta = new stdclass();
            //var_dump($resultado);
            $objDelaRespuesta->resultado = $resultado;
            return $response->getBody()->write("Se tomo el pedido.Recuerde que si estaba en preparacion se paso a listo para servir.");
        }
    }

    public function ServirPedidoListo($request, $response, $args)
    {
        $ArrayDeParametros = $request->getParsedBody();
        $idPedido = $ArrayDeParametros['idPedido'];

        $header = $request->getHeaderLine('Authorization');
        $token = trim(explode("Bearer", $header)[1]);
        $payload = AutentificadorJWT::ObtenerData($token);

        //obtengo el id_responsable
        $idResponsable = auxUsuario::ObtenerIdPorMail($payload->mail);
        if ($idResponsable == -1) {
            $response->getBody()->write("ERROR. No existe un usuario con ese mail.");
            return $response;
        } else {
            $resultado = auxPedido::ServirPedido($idPedido, $idResponsable);

            Logs::logPedido($idPedido);
            Logs::LogUsuario($payload->mail, "Sirvio pedido");



            $objDelaRespuesta = new stdclass();
            //var_dump($resultado);
            $objDelaRespuesta->resultado = $resultado;
            return $response->getBody()->write("Se sirvio el pedido.");
        }
    }

    public function ConsultarTiempoEspera($request, $response, $args)
    {
        $id = $args['numero_pedido'];

        $pedidoMax = Capsule::table('pedidos')->where('numero_pedido', $id)->max("tiempo_estimado");

        echo "Tiempo estimado para entrega: ", $pedidoMax, " minutos.";
    }

    public function PagarCuenta($request, $response, $args)
    {

        $ArrayDeParametros = $request->getParsedBody();

        $numero_pedido = $ArrayDeParametros["numero_pedido"];
        $metodoPago = $ArrayDeParametros["metodoPago"];
        if (auxPedido::OperacionCobro($numero_pedido, $metodoPago)) {
            echo "Pago realizado con exito!!";
        } else {
            echo "Algo fallo";
        }
    }

    public function PuntuarAtencion($request, $response, $args)
    {
        $ArrayDeParametros = $request->getParsedBody();
        $numero_pedido = $ArrayDeParametros["numero_pedido"];
        $mesa = $ArrayDeParametros["mesa"];
        $restaurante = $ArrayDeParametros["restaurante"];
        $mozo = $ArrayDeParametros["mozo"];
        $cocinero = $ArrayDeParametros["cocinero"];
        $experiencia = $ArrayDeParametros["experiencia"];

        $encuesta = new Encuesta();

        $encuesta->numero_pedido = $numero_pedido;
        $encuesta->mesa = $mesa;
        $encuesta->restaurante = $restaurante;
        $encuesta->mozo = $mozo;
        $encuesta->cocinero = $cocinero;
        $encuesta->experiencia = $experiencia;
        //$encuesta->fecha_eliminacion = NULL;
        $encuesta->save();

        //Actualizo estado de la mesa
        $auxPedido = Capsule::table('pedidos')->where('numero_pedido', $numero_pedido)->first();
        $auxMesa = new Mesa();
        $MesaAct = $auxMesa->find($auxPedido->id_mesa);
        $MesaAct->id_estado = 0;
        $MesaAct->save();

        echo "Gracias, encuesta guardada.";
    }
}
