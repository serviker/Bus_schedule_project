<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('bus_stops', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('route_id')->nullable(); // Поле route_id
            $table->foreignId('bus_id')->constrained('buses')->onDelete('cascade');
            $table->foreignId('stop_id')->constrained('stops')->onDelete('cascade');
            $table->time('arrival_time'); // Время прибытия на остановку
            $table->integer('stop_order'); // Порядок следования остановки на маршруте
            $table->integer('interval')->default(30);
            $table->timestamps();

            $table->softDeletes();

            // Внешний ключ для route_id
            $table->foreign('route_id')->references('id')->on('routes')->onDelete('cascade');
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bus_stops');
    }
};
