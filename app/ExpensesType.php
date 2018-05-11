<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpensesType extends Model
{
    protected $table = 'expenses';

    protected $primaryKey = 'expenseId';

    protected $fillable = [
        'expenseCode',
        'expenseName',
        'expenseCategoryId'
    ];


    public function expenseCategory() {
        return $this->belongsTo('App\ExpensesCategories','expenseCategoryId');
    }

    public function setExpenseCodeAttribute($value){
        $this->attributes['expenseCode'] = strtoupper($value);
    }
}
