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

        Schema::create('prueba.venta_detalle', function (Blueprint $table) {
            $table->increments('v_d_ide')->primary();
            $table->foreignId('ven_ide')->constrained('prueba.venta', 'ven_ide'); // Sin onDelete('cascade') ya que se usa eliminación lógica
            $table->text('v_d_pro')->default('');
            $table->decimal('v_d_uni', 14, 2)->default(0.00);
            $table->decimal('v_d_can', 14, 2)->default(0.00);
            $table->decimal('v_d_tot', 14, 2)->default(0.00);
            $table->integer('est_ado')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('prueba.venta_detalle');
    }
};
