<?php

require_once './models/Mesa.php';

use App\Models\Mesa as Mesa;


class auxMesa{   

    public static function VerificarMesaDB($auxMesa)
    {

        $arrayMesas = array();
        $arrayMesas = Mesa::all();

        $verificado = 0;
        foreach($arrayMesas as $mesa)
        {
            if($mesa->numero_de_mesa == $auxMesa->numero_de_mesa)
            {
                $verificado = 1;
            }
        }
		return $verificado;
	}

	public static function mostrarDatos($mesa)
	{
        return $mesa->idMesa.",".$mesa->numero_de_mesa.",".$mesa->max_personas.",".$mesa->id_estado.",".$mesa->fecha_eliminacion;
	}

	public static function ObtenerIdPorNumeroMesa($numDeMesa)
    {

        $arrayMesas = array();
        $arrayMesas = Mesa::all();

        $idMesa = -1;
        foreach($arrayMesas as $mesa)
        {
            if($mesa->numero_de_mesa == $numDeMesa && $mesa->id_estado == 0)
            {
                $idMesa = $mesa->idMesa;
				break;
            }
        }
		return $idMesa;
	}


    public static function GuardarEnCsv($mesa, $mode)
    {

        $direccionArchivo = fopen("csv/Mesas.csv", $mode);

        if ($direccionArchivo != false) {
            if (fwrite($direccionArchivo, self::mostrarDatos($mesa) . "\n") != false) {
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

        $mesas = array();
        $mesas = Mesa::all();

        $mode = "w";

        foreach ($mesas as $mesa) {
            self::GuardarEnCsv($mesa, $mode);
            $mode = "a";
        }

        echo "Csv generado en la ruta /csv/Mesas.csv";
    }

    public static function GenerarPdf()
    {
        $lista = Mesa::all();

        $pdf = new FPDF('P', 'mm', 'A4');
        $pdf->AddPage();
        $pdf->SetFont('Arial', 'B', 16);

        foreach ($lista as $user) {
            $pdf->Cell(40, 10, $user->numero_de_mesa,1, 0, 'C',0);
            $pdf->Cell(40, 10, $user->max_personas,1,0,'C',0);
            $pdf->Cell(40, 10, $user->id_estado,1,1,'C',0);

        }

        echo $pdf->Output("mesas.pdf","F");

        echo "Pdf de mesas generado productos.pdf";
    }
}



?>