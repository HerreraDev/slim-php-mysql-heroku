<?php

namespace App\Models;


require_once "../vendor/autoload.php";


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
 
class Ticket extends Model { 
    
    use SoftDeletes;

    protected $primaryKey = 'idTicket';
    protected $table = 'tickets';
    public $incrementing = true;
    public $timestamps = false;

    const DELETED_AT = 'fecha_eliminacion';

    protected $fillable = [
        'idTicket', 'idMesa', 'numero_de_pedido', 'total', 'metodo_pago', 'fecha_hora_salida', 'fecha_eliminacion'
    ];
}











?>