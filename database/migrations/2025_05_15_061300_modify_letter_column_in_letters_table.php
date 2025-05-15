<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('letters', function (Blueprint $table) {
            $table->string('letter', 50)->change();
        });
    }

    public function down()
    {
        Schema::table('letters', function (Blueprint $table) {
            $table->char('letter', 1)->change();
        });
    }
}; 