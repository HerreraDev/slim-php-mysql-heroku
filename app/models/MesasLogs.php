<?php

namespace App\Models;


require_once "../vendor/autoload.php";


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
 
class MesasLogs extends Model { 
    
    use SoftDeletes;

    protected $primaryKey = 'idLog';
    protected $table = 'mesasLogs';
    public $incrementing = true;
    public $timestamps = false;

    const DELETED_AT = 'fecha_eliminacion';

    protected $fillable = [
        'idLog', 'id_mesa', 'id_responsable', 'id_estado', 'fecha_hora_log', 'fecha_eliminacion'
    ];
}











?>