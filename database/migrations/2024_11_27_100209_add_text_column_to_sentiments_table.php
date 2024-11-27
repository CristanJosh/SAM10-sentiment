<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
public function up()
{
    Schema::table('sentiments', function (Blueprint $table) {
        $table->text('text')->nullable(); // Add the 'text' column
    });
}

public function down()
{
    Schema::table('sentiments', function (Blueprint $table) {
        $table->dropColumn('text');
    });
}

};
