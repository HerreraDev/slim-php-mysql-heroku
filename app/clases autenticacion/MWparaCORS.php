<?php


class MWparaCORS{


	public function HabilitarCORS8080($request, $response, $next) {

		/*
		al ingresar no hago nada
		*/
		 $response = $next($request, $response);
   		 return $response
            ->withHeader('Access-Control-Allow-Origin', 'http://localhost:8080')
            ->withHeader('Access-Control-Allow-Headers', 'X-Requested-With, Content-Type, Accept, Origin, Authorization')
            ->withHeader('Access-Control-Allow-Methods', 'GET, POST, PUT, DELETE, OPTIONS');
	}


}



?>