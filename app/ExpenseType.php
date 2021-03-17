<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseType extends Model
{
    protected $table = 'expense_types';

    protected $primaryKey = 'expense_type_id';
}
