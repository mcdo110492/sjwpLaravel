<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpensesCategories extends Model
{
    protected $table = 'expenseCategories';

    protected $primaryKey = 'expenseCategoryId';

    protected $fillable = [
        'expenseCategoryName'
    ];
}
