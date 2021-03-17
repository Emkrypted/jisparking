<?php

namespace App;
use App\RequirementType;
use Illuminate\Database\Eloquent\Model;

class Requirement extends Model
{
    protected $table = 'requirements';

    protected $primaryKey = 'requirement_id';

    /**
     * Get the post that owns the comment.
     */
    public function requirement_type()
    {
        return $this->belongsTo(RequirementType::class, 'requirement_type_id');
    }

    /**
     * Get the post that owns the comment.
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    /**
     * Get the post that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'rut');
    }
}
