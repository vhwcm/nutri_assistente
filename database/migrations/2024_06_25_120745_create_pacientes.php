<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('pacientes', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('nutri_id')->nullable();
            $table->bigInteger('user_id')->unsigned()->nullable();
            $table->string('nome');
            $table->integer('idade');
            $table->float('peso');
            $table->float('altura');
            $table->boolean('sexo');
            $table->binary('anaminesia')->nullable();
            $table->integer('fa');
            $table->timestamps();
        });

        DB::statement('ALTER TABLE pacientes MODIFY COLUMN anaminesia MEDIUMBLOB');
    }
    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('pacientes');
    }
};



