<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDeathTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::create('death', function (Blueprint $table) {
            $table->integer('death_id')->unisgned()->primary();
            $table->string('deceased_name');
            $table->string('residence');
            $table->date('date_death')->nullable();
            $table->date('date_burial')->nullable();
            $table->string('place_burial')->nullable();
            $table->integer('book_no');
            $table->integer('page_no');
            $table->integer('entry_no');
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
        Schema::dropIfExists('death');
    }
}
