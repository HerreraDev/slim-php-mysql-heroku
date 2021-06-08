<?php

namespace App\Models;


require_once "../vendor/autoload.php";


use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
 
class UserLogs extends Model { 
    
    use SoftDeletes;

    protected $primaryKey = 'idLog';
    protected $table = 'userLogs';
    public $incrementing = true;
    public $timestamps = false;

    const DELETED_AT = 'fecha_eliminacion';

    protected $fillable = [
        'idLog', 'id_usuario', 'accion', 'hora_accion', 'fecha_eliminacion'
    ];
}











?>