<?php

namespace App;

use App\ExpenseType;
use App\Status;
use Illuminate\Database\Eloquent\Model;

class Capitulation extends Model
{
    protected $table = 'capitulations';

    protected $primaryKey = 'capitulation_id';

    /**
     * Get the post that owns the comment.
     */
    public function expense_type()
    {
        return $this->belongsTo(ExpenseType::class, 'expense_type_id');
    }

    /**
     * Get the post that owns the comment.
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
