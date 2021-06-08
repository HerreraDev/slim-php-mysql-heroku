<?php

namespace App\Models;


require_once "../vendor/autoload.php";


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
 
class Mesa extends Model { 
    
    use SoftDeletes;

    protected $primaryKey = 'idMesa';
    protected $table = 'mesas';
    public $incrementing = true;
    public $timestamps = false;

    const DELETED_AT = 'fecha_eliminacion';

    protected $fillable = [
        'idMesa', 'numero_de_mesa', 'max_personas', 'id_estado', 'fecha_eliminacion'
    ];
}











?>