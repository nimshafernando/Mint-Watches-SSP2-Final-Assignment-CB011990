<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateUsersTableRemoveNamesAddLocation extends Migration
{
    public function up()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('first_name');  // Remove first_name
            $table->dropColumn('last_name');   // Remove last_name
            $table->string('location')->nullable(); // Add location
        });
    }

    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->nullable();  // Re-add first_name in case of rollback
            $table->string('last_name')->nullable();   // Re-add last_name in case of rollback
            $table->dropColumn('location');           // Remove location in case of rollback
        });
    }
}
