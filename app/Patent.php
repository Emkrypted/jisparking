<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Patent extends Model
{
    protected $table = 'patents';
    protected $primaryKey = 'patent_id';

    /**
     * Get the post that owns the comment.
     */
    public function branch_office()
    {
        return $this->belongsTo(BranchOffice::class, 'branch_office_id');
    }
}
