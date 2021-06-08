<?php

namespace App\Models;


require_once "../vendor/autoload.php";


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
 
class Pedido extends Model { 
    
    use SoftDeletes;

    protected $primaryKey = 'idPedido';
    protected $table = 'pedidos';
    public $incrementing = true;
    public $timestamps = false;

    const DELETED_AT = 'fecha_eliminacion';

    protected $fillable = [
        'idPedido', 'numero_pedido', 'id_usuario', 'id_mesa', 'id_estado', 'id_producto', 'cantidad', 'id_responsable', 'precio_final', 'fecha_hora_de_ingreso', 'tiempo_estimado'
    ];
}











?>