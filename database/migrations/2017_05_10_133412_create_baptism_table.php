<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateBaptismTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('baptism', function (Blueprint $table) {
            $table->integer('baptism_id')->unisgned()->primary();
            $table->string('child_name');
            $table->string('father_name');
            $table->string('mother_name');
            $table->string('birth_place')->nullable();
            $table->date('birthday')->nullable();
            $table->date('baptism_date')->nullable();
            $table->integer('book_no');
            $table->integer('page_no');
            $table->integer('entry_no');
            $table->string('sponsors')->nullable();
            $table->string('remarks')->nullable();
            $table->integer('minister_id');
            $table->timestamps();
            $table->foreign('minister_id')->references('minister_id')->on('minister');
            
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
         Schema::dropIfExists('baptism');
    }
}
