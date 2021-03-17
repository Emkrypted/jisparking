<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Supplier extends Model
{
    protected $table = 'supplier_data';

    protected $primaryKey = 'rut';

    /**
     * Get the post that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'rut');
    }
}
