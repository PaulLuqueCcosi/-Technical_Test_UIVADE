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
        DB::statement('CREATE SCHEMA IF NOT EXISTS prueba');

        Schema::create('prueba.trabajador', function (Blueprint $table) {
            $table->increments('tra_ide')->primary();
            $table->integer('tra_cod')->default(0);
            $table->string('tra_nom', 200)->default('');
            $table->string('tra_pat', 200)->default('');
            $table->string('tra_mat', 200)->default('');
            $table->integer('est_ado')->default(1);
            $table->timestamps();

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prueba.trabajador');
    }
};
