<?php

namespace App;

use App\BranchOffice;
use App\Cashier;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $table = 'transactions';

    protected $primaryKey = 'transaction_id';

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
}
