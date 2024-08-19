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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('nombre', 45);
            $table->string('apellido', 75);
            $table->string('email');
            // $table->string('email')->unique();
            $table->timestamp('email_verified_at')->nullable();
            $table->string('password');
            $table->string('foto')->nullable();
            $table->integer('intentos')->default(0);
            $table->rememberToken();
            $table->unsignedBigInteger('tipo_usuario_id');
            $table->foreignId('departamento_id')->constrained();
            $table->boolean('status')->default(1);
            $table->timestamps();

            $table->foreign('tipo_usuario_id')->references('id')->on('cat_tipo_usuario');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users');
    }
};
