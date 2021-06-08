<?php

namespace App\Models;


require_once "../vendor/autoload.php";


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
 
class Producto extends Model { 
    
    use SoftDeletes;

    protected $primaryKey = 'idProducto';
    protected $table = 'producto';
    public $incrementing = true;
    public $timestamps = false;

    const DELETED_AT = 'fecha_eliminacion';

    protected $fillable = [
        'idProducto', 'codigo_de_barra', 'nombre', 'tipo', 'stock', 'precio', 'fecha_de_creacion', 'fecha_de_modificacion', 'ruta_foto'
    ];
}











?>