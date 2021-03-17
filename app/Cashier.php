<?php

namespace App;

use App\BranchOffice;
use Illuminate\Database\Eloquent\Model;

class Cashier extends Model
{
    protected $table = 'cashiers';

    protected $primaryKey = 'cashier_id';

    /**
     * Get the post that owns the comment.
     */
    public function branch_office()
    {
        return $this->belongsTo(BranchOffice::class, 'branch_office_id');
    }
}
