<?php

namespace App;

use App\BranchOffice;
use App\Customer;
use App\User;
use Illuminate\Database\Eloquent\Model;

class Dte extends Model
{
    protected $table = 'dtes';
    protected $primaryKey = 'dte_id';

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
    public function status()
    {
        return $this->belongsTo(Status::class, 'status_id');
    }

    /**
     * Get the post that owns the comment.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'rut', 'rut');
    }

    /**
     * Get the post that owns the comment.
     */
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'rut', 'rut');
    }
}
