<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::post('api/authenticate', 'AuthenticateController@authenticate');



Route::group(['prefix' => 'api', 'middleware' => 'jwt.auth'], function()
{
    /* Check the Validity of the Token */
    Route::get('authenticate','AuthenticateController@checkValidity');
     /* End Check the Validity of the Token */

    /* Route of the Minister Features */
    Route::get('minister/all','MinisterController@all');

    Route::get('minister/active','MinisterController@active');
    
    Route::post('minister/status','MinisterController@changeStatus');

    Route::resource('minister','MinisterController');
    /* End Route of the Minister Features */
    
    /* Route of the Baptism Feature */
    Route::resource('baptism','BaptismController');
    /* End of Route of the Baptism Feature */

    /* Route for the Excel File */
    Route::post('excel/import/{source}','ExcelImportController@importExcel');
    /* End Route for the Excel File */

    /* Route for the Confirmation Feature */
    Route::resource('confirmation','ConfirmationController');
    /* End Route for the Confirmation Feature */

    /* Route for the Death Feature */
    Route::resource('death','DeathController');
    /* End Route for the Death Feature */
    
    /* Route for the Marraige Feature */
    Route::get('marriage','MarriageController@index');

    Route::get('marriage/{id}','MarriageController@show');

    Route::post('marriage','MarriageController@store');

    Route::put('marriage/{id}','MarriageController@update');
    /* End Route for the Marriage Feature */

    /* Route for the Services Categories Features */

    Route::get('services/categories/all','ServicesCategoriesController@all');

    Route::get('services/categories/{id}','ServicesCategoriesController@show');

    Route::get('services/categories','ServicesCategoriesController@index');

    Route::post('services/categories/validate','ServicesCategoriesController@checkValue');

    Route::post('services/categories','ServicesCategoriesController@store');

    Route::put('services/categories/{id}','ServicesCategoriesController@update');

    /* End Route for the Services Categories Features */


    /* Route for the Services Categories Features */

    Route::get('services/type/search','ServiceTypeController@getServices');

    Route::get('services/type/{id}','ServiceTypeController@show');
    
    Route::get('services/type','ServiceTypeController@index');
    
    Route::post('services/type/validate','ServiceTypeController@checkValue');
    
    Route::post('services/type','ServiceTypeController@store');
    
    Route::put('services/type/{id}','ServiceTypeController@update');
    
     /* End Route for the Services Categories Features */


    /* Route for the Expense Categories Features */

    Route::get('expenses/categories/all','ExpensesCategoriesController@all');
    
    Route::get('expenses/categories/{id}','ExpensesCategoriesController@show');
    
    Route::get('expenses/categories','ExpensesCategoriesController@index');
    
    Route::post('expenses/categories/validate','ExpensesCategoriesController@checkValue');
    
    Route::post('expenses/categories','ExpensesCategoriesController@store');
    
    Route::put('expenses/categories/{id}','ExpensesCategoriesController@update');
    
    /* End Route for the Expense Categories Features */

    /* Route for the Expense Type Features */
    
    Route::get('expenses/type/all','ExpensesTypeController@all');

    Route::get('expenses/type/{id}','ExpensesTypeController@show');
    
    Route::get('expenses/type','ExpensesTypeController@index');
    
    Route::post('expenses/type/validate','ExpensesTypeController@checkValue');
    
    Route::post('expenses/type','ExpensesTypeController@store');
    
    Route::put('expenses/type/{id}','ExpensesTypeController@update');
    
    /* End Route for the Expense Type Features */

    /* Route for the Sales/POS Feeatures */

    Route::get('sales/collection','SalesController@totalCollection');

    Route::get('sales/items/{id}','SalesController@getItems');

    Route::post('sales','SalesController@store');

    Route::post('sales/print','SalesController@print');

    Route::post('sales/list','SalesController@index');

    Route::post('sales/validate','SalesController@checkValue');

    Route::post('sales/status','SalesController@changeStatus');

    /* End Route for the Sales/POS Feeatures */

    /* Route for the Sales/Report Feature */

    Route::post('sales/report','SalesReportController@index');

    /* End Route for the Sales/Report Feature */

    /* Route for the Expense/List Feature */

    Route::get('expenses/details/{id}','ExpenseCostController@getDetails');

    Route::post('expenses/list','ExpenseCostController@index');

    Route::post('expenses/save','ExpenseCostController@store');

    Route::post('expenses/print','ExpenseCostController@print');

    Route::put('expenses/update/{id}','ExpenseCostController@update');

    Route::put('expenses/status','ExpenseCostController@changeStatus');

    /* End of Route for the Expense/List Feature */

    /* Route for the Expenses/Report Feature */

     Route::post('expenses/report','ExpensesReportController@index');
     
    /* End Route for the Expenses/Report Feature */

    /* Route for the User/Profile Feature */

    Route::post('user/username/validate','UserProfileController@checkUsername');

    Route::post('user/password/validate','UserProfileController@checkPassword');

    Route::post('user/profile/username','UserProfileController@changeUsername');

    Route::post('user/profile/password','UserProfileController@changePassword');

    /* End Route for the User/Profile Feature */

});



