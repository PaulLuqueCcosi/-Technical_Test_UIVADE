<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up()
    {
        DB::statement('CREATE SCHEMA IF NOT EXISTS prueba');

        Schema::create('prueba.venta', function (Blueprint $table) {
            $table->increments('ven_ide')->primary();
            $table->string('ven_ser', 5)->default('');
            $table->string('ven_num', 100)->default('');
            $table->text('ven_cli')->nullable();
            $table->decimal('ven_mon', 14, 2);
            $table->integer('est_ado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prueba.venta');
    }
};
