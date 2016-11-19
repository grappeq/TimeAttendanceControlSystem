<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePaychecksTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('paychecks', function (Blueprint $table) {
            $table->increments('id');
            
            $table->datetime('periodStartDatetime');
            $table->datetime('periodEndDatetime');
            
            $table->integer('timeWorked');
            $table->decimal('earnings', 8, 2);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('paychecks');
    }

}
