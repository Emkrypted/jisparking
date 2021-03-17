<?php

namespace App;

use App\User;
use Illuminate\Database\Eloquent\Model;

class Honorary extends Model
{
    protected $table = 'honoraries';
    protected $primaryKey = 'honorary_id';

    /**
     * Get the post that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'rut');
    }
}
