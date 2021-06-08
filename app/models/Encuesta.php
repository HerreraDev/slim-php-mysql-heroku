<?php

namespace App\Models;


require_once "../vendor/autoload.php";


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
 
class Encuesta extends Model { 
    
    use SoftDeletes;

    protected $primaryKey = 'idEncuesta';
    protected $table = 'encuesta';
    public $incrementing = true;
    public $timestamps = false;

    const DELETED_AT = 'fecha_eliminacion';

    protected $fillable = [
        'idEncuesta', 'numero_pedido', 'mesa', 'restaurante', 'mozo', 'cocinero', 'experiencia'
    ];
}











?>