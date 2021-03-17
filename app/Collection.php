<?php

namespace App;

use App\BranchOffice;
use App\Cashier;
use App\Status;
use Illuminate\Database\Eloquent\Model;

class Collection extends Model
{
    protected $table = 'collections';

    protected $primaryKey = 'collection_id';

    public $timestamps = false;

    /**
     * Get the post that owns the comment.
     */
    public function branch_office()
    {
        return $this->belongsTo(BranchOffice::class, 'branch_office_id');
    }

    /**
     * Get the post that owns the comment.
     */
    public function cashier()
    {
        return $this->belongsTo(Cashier::class, 'cashier_id');
    }

    /**
     * Get the post that owns the comment.
     */
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }
}
