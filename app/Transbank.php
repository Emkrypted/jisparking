<?php

namespace App;

use App\BranchOffice;
use Illuminate\Database\Eloquent\Model;

class Transbank extends Model
{
    protected $table = 'transbank_collections';

    protected $primaryKey = 'transbank_collection_id';

    /**
     * Get the post that owns the comment.
     */
    public function branch_office()
    {
        return $this->belongsTo(BranchOffice::class, 'branch_office_id');
    }
}
