<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWorkSessionsTable extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::create('work_sessions', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedSmallInteger('employeeId')->references('id')->on('employees');
            
            $table->dateTime('startDatetime');
            $table->dateTime('endDatetime');
            
            $table->unsignedSmallInteger('jobId')->references('id')->on('jobs');
            $table->decimal('wagePerHour',6,2);
            $table->mediumInteger('timeWorked');
            $table->decimal('earnedAmount', 8, 2);
            $table->integer('paycheckId')->references('id')->on('paychecks');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::drop('work_sessions');
    }

}
