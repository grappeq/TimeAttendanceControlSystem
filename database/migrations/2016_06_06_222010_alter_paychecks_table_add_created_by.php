<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;


/**
 * Migration class
 * Add field 'created_by' to 'paychecks' table
 */
class AlterPaychecksTableAddCreatedBy extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up() {
        Schema::table('paychecks', function($table) {
            $table->string('created_by')->references('username')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down() {
        Schema::table('paychecks', function($table) {
            $table->dropColumn('created_by');
        });
    }

}
