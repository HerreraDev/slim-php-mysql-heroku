<?php
require_once './models/Producto.php';
require_once './clases/IApiUsable.php';

use App\Models\Producto as Producto;


class ProductoApi implements IApiUsable
{
 	public function TraerUno($request, $response, $args) {
    $id = $args['id'];
    $elProducto = new Producto;
    $elProducto = $elProducto->find($id);
    $newResponse = $response->withJson($elProducto, 200);
    return $newResponse;
    }
     public function TraerTodos($request, $response, $args) {
      	$todosLosProductos=Producto::all();

        //Producto::DibujarTablaProducto($todosLosProductos);

     	$newResponse = $response->withJson($todosLosProductos, 200);  
    	return $newResponse;
    }
      public function CargarUno($request, $response, $args) {
     	 $ArrayDeParametros = $request->getParsedBody();
        //var_dump($ArrayDeParametros);
        $codigo_de_barra = $ArrayDeParametros['codigo_de_barra'];
        $nombre= $ArrayDeParametros['nombre'];
        $tipo= $ArrayDeParametros['tipo'];
        $stock= $ArrayDeParametros['stock'];
        $precio= $ArrayDeParametros['precio'];
        $fecha_de_creacion = date("Y-m-d");
        $fecha_de_modificacion = date("Y-m-d");


        $tipo = strtolower($tipo);

        if($tipo != "bar" && $tipo != "cerveza" && $tipo != "cocina")
        {
          $response->getBody()->write("ERROR. Solo se pueden ingresar los siguientes tipos de producto: bar - cerveza - cocina.");
          return $response;
        }

        $miProducto = new Producto();

        $miProducto->codigo_de_barra=$codigo_de_barra;
        $miProducto->nombre=$nombre;
        $miProducto->tipo=$tipo;
        $miProducto->stock=$stock;
        $miProducto->precio=$precio;
        $miProducto->fecha_de_creacion=$fecha_de_creacion;
        $miProducto->fecha_de_modificacion=$fecha_de_modificacion;



        $archivos = $request->getUploadedFiles();
        $destino="./fotos/productos/";

        $nombreAnterior=$archivos['foto']->getClientFilename();
        $extension= explode(".", $nombreAnterior)  ;
        $extension=array_reverse($extension);

        $archivos['foto']->moveTo($destino.$codigo_de_barra.".".$extension[0]);

        $miProducto->ruta_foto = $destino . $codigo_de_barra . "." . $extension[0];

        if(auxProducto::VerificarProductoDB($miProducto))
        {
          $response->getBody()->write("ERROR. Como el producto ya existia, no se pudo cargar");
        }
        else
        {
          $miProducto->save();
          $response->getBody()->write("Se ingreso el Producto nuevo");
        }


     
        

        return $response;
    }

    
    public function BorrarUno($request, $response, $args) {
      $id = $args['id'];
      $producto = new Producto();
      $producto->find($id)->delete();
  
  
      $response->getBody()->write("Se elimino el producto con exito");
      return $response;
   }
     
     public function ModificarUno($request, $response, $args) {
     	//$response->getBody()->write("<h1>Modificar  uno</h1>");
     	$ArrayDeParametros = $request->getParsedBody();
	    //var_dump($ArrayDeParametros);
        
        $id= $ArrayDeParametros['id'];
        $nombre= $ArrayDeParametros['nombre'];
        $tipo= $ArrayDeParametros['tipo'];
        $stock= $ArrayDeParametros['stock'];
        $precio= $ArrayDeParametros['precio'];
        $fecha_de_modificacion = date("Y-m-d");

        
	    $miProducto = new Producto();

      $elPrdoModif = $miProducto->find($id);
        $elPrdoModif->nombre=$nombre;
        $elPrdoModif->tipo=$tipo;
        $elPrdoModif->stock=$stock;
        $elPrdoModif->precio=$precio;
        $elPrdoModif->fecha_de_modificacion=$fecha_de_modificacion;

	   	$resultado =$elPrdoModif->save();

		return $response->withJson($resultado, 200);		
  }


}


