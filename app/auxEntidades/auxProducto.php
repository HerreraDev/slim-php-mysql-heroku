<?php

require_once './models/Producto.php';

use App\Models\Producto as Producto;


class auxProducto{

    public static function VerificarProductoDB($prod)
    {

        $arrayProductos = array();
        $arrayProductos = Producto::all();

        $verificado = 0;
        foreach($arrayProductos as $producto)
        {
            if($producto->codigo_de_barra == $prod->codigo_de_barra)
            {
                $verificado = 1;
            }
        }
		return $verificado;
	}

	public static function mostrarDatos($producto)
	{
        return $producto->idProducto.",".$producto->codigo_de_barra.",".$producto->nombre.",".$producto->tipo.",".$producto->stock.",".$producto->precio.",".$producto->fecha_de_creacion.",".$producto->fecha_de_modificacion.",".$producto->ruta_foto;
	}

	public static function ObtenerIdProductoPorNombre($nombreProd)
    {

        $arrayProductos = array();
        $arrayProductos = Producto::all();

        $idProd = -1;
        foreach($arrayProductos as $prod)
        {
            if($prod->nombre == $nombreProd)
            {
                $idProd = $prod->idProducto;
				break;
            }
        }
		return $idProd;
	}

	public static function CalcularPrecioFinal($pedido){
		$cant = $pedido->cantidad;
		$auxIdProd = $pedido->id_producto;

	    $prods = Producto::all();
		$precioProd = -1;

	   foreach($prods as $prod)
	   {
		   if($prod->idProducto == $auxIdProd)
		   {
			   $precioProd = $prod->precio;
			   break;
		   }
	   }

	   return $cant * $precioProd;

	}


    public static function GuardarEnCsv($producto, $mode)
    {

        $direccionArchivo = fopen("csv/Productos.csv", $mode);

        if ($direccionArchivo != false) {
            if (fwrite($direccionArchivo, self::mostrarDatos($producto) . "\n") != false) {
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

        $productos = array();
        $productos = Producto::all();

        $mode = "w";

        foreach ($productos as $prod) {
            self::GuardarEnCsv($prod, $mode);
            $mode = "a";
        }

        echo "Csv generado en la ruta /csv/Productos.csv";
    }


    public static function GenerarPdf()
    {
        $lista = Producto::all();

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        foreach ($lista as $user) {
            $pdf->Cell(40, 10, $user->codigo_de_barra,1, 0, 'C',0);
            $pdf->Cell(40, 10, $user->stock,1,0,'C',0);
            $pdf->Cell(40, 10, $user->precio,1,0,'C',0);
            $pdf->Cell(40, 10, $user->fecha_de_creacion,1,1,'C',0);

        }

        echo $pdf->Output("productos.pdf","F");

        echo "Pdf de productos generado productos.pdf";
    }





}



?>