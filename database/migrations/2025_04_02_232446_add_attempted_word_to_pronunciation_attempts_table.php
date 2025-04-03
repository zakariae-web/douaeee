<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('pronunciation_attempts', function (Blueprint $table) {
            $table->string('attempted_word')->nullable()->after('letter');
        });
    }

    public function down()
    {
        Schema::table('pronunciation_attempts', function (Blueprint $table) {
            $table->dropColumn('attempted_word');
        });
    }
};
