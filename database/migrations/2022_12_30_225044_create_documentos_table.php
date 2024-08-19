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
        Schema::create('documentos', function (Blueprint $table) {
            $table->id();
            // $table->string('folio', 50)->nullable();
            $table->string('folio');
            $table->integer('folio_num');
            $table->string('no_documento')->nullable();
            $table->date('fecha_ingreso')->nullable();
            $table->date('fecha_emision')->nullable();
            $table->date('fecha_recepcion')->nullable();
            $table->date('fecha_recepcion_area')->nullable();
            $table->string('emisor')->nullable();
            $table->string('cargo')->nullable();
            $table->text('asunto')->nullable();
            $table->string('dependencia')->nullable();
            $table->string('dirigido_a')->nullable();
            $table->string('cargo_a')->nullable();
            $table->string('observaciones_a')->nullable();
            $table->string('dependencia_a')->nullable();
            // $table->string('carpeta')->nullable();
            // $table->string('tipo_archivo')->nullable();
            // $table->string('archivo')->nullable();
            // $table->string('archivo2')->nullable();
            $table->text('enlace')->nullable();
            $table->boolean('status')->default(1);
            $table->string('tipo_documento_id')->nullable();

            // $table->unsignedBigInteger('tipo_documento_id');
            $table->foreignId('departamento_id')->nullable()->constrained();
            $table->unsignedBigInteger('prioridad_id')->nullable();
            // $table->unsignedBigInteger('categoria_id')->nullable();
            $table->unsignedBigInteger('estatus_id');
            $table->integer('ultimo_usuario');

            $table->timestamps();

            // $table->foreign('tipo_documento_id')->references('id')->on('cat_tipo_doc');
            $table->foreign('prioridad_id')->references('id')->on('cat_prioridad');
            // $table->foreign('categoria_id')->references('id')->on('cat_categoria');
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
        Schema::dropIfExists('documentos');
    }
};
