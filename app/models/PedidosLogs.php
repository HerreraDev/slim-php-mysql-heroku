<?php

namespace App\Models;


require_once "../vendor/autoload.php";


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
 
class PedidosLogs extends Model { 
    
    use SoftDeletes;

    protected $primaryKey = 'idLog';
    protected $table = 'pedidosLogs';
    public $incrementing = true;
    public $timestamps = false;

    const DELETED_AT = 'fecha_eliminacion';

    protected $fillable = [
        'idLog', 'id_pedido', 'id_estado', 'id_responsable', 'fecha_hora_log', 'fecha_eliminacion'
    ];
}











?>