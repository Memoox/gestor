<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('evidencias', function (Blueprint $table) {
            $table->id();
            $table->string('descripcion');
            $table->string('archivo')->nullable();
            $table->foreignId('documento_id')->constrained();
            $table->unsignedBigInteger('estatus_id');
            $table->timestamps();

            $table->foreign('estatus_id')->references('id')->on('cat_estatus');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('evidencias');
    }
};
