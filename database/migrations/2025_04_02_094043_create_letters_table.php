<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('letters', function (Blueprint $table) {
            $table->id();
            $table->char('letter', 1)->unique(); // Stocke une lettre unique (A-Z)
            $table->timestamps();
        });
    }
    

    public function down(): void
    {
        Schema::dropIfExists('letters');
    }
};
