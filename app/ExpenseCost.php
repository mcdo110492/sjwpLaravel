<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class ExpenseCost extends Model
{
    
    protected $table = 'expenseCosts';

    protected $primaryKey = 'expenseCostId';

    protected $fillable = [
        'expenseId',
        'expenseCost',
        'expenserrNo',
        'dateExpense',
        'details',
        'status',
        'user_id'
    ];


    public function users(){
        return $this->belongsTo('App\User','user_id');
    }


    public function expenses(){
        return $this->belongsTo('App\ExpensesType','expenseId');
    }

    
}
