<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $table = 'customers';
    protected $keyType = 'string';
    protected $primaryKey = 'rut';

    /**
     * Get the post that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'rut', 'rut');
    }
}
