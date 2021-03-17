<?php

namespace App;

use App\Cashier;
use Illuminate\Database\Eloquent\Model;

class BranchOffice extends Model
{
    protected $table = 'branch_offices';

    protected $primaryKey = 'branch_office_id';

    public function cashiers()
    {
        return $this->hasMany(Cashier::class, 'cashier_id');
    }
}
