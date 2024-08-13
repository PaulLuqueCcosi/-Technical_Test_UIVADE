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
        DB::statement('
            CREATE OR REPLACE FUNCTION actualizar_total_detalle()
            RETURNS TRIGGER AS $$
            BEGIN
                NEW.v_d_tot := NEW.v_d_can * NEW.v_d_uni;
                RETURN NEW;
            END;
            $$ LANGUAGE plpgsql;
        ');

        DB::statement('
            CREATE TRIGGER trigger_actualizar_total
            BEFORE INSERT OR UPDATE ON prueba.venta_detalle
            FOR EACH ROW
            EXECUTE FUNCTION actualizar_total_detalle();
        ');
    }
    /**
     * Reverse the migrations.
     */
    public function down()
    {
        DB::statement('DROP TRIGGER IF EXISTS trigger_actualizar_total ON prueba.venta_detalle;');
        DB::statement('DROP FUNCTION IF EXISTS actualizar_total_detalle();');
    }
};
