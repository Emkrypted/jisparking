<?php

namespace App;

use App\RolPermission;
use Illuminate\Database\Eloquent\Model;

class Rol extends Model
{
    protected $table = 'rols';

    protected $primaryKey = 'rol_id';

    public function rol_permissions()
    {
        return $this->hasMany(RolPermission::class, 'rol_id');
    }
}
