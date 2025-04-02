<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('pronunciation_attempts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('letter');
            $table->boolean('success');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('pronunciation_attempts');
    }
};
