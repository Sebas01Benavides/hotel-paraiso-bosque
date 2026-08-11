<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('check_in');
            $table->date('check_out');
            $table->integer('guests');
            $table->string('room_type');
            $table->text('comments')->nullable();

            $table->integer('dias_reserva')->default(0);
            $table->decimal('porcentaje_descuento', 5, 2)->default(0.00);
            $table->enum('estado', ['pendiente', 'confirmado', 'cancelado', 'completado'])->default('pendiente');
            $table->decimal('costo_total', 10, 2)->default(0.00);

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reservations');
    }
};