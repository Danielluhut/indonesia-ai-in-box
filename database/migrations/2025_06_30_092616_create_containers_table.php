<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::create('containers', function (Blueprint $table) {
            $table->string('id')->primary(); // container_id dari Docker
            $table->unsignedBigInteger('user_id'); // relasi ke users.id
            $table->string('name');
            $table->string('image');
            $table->enum('status', ['Running', 'Stopped'])->default('Stopped');
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down()
    {
        Schema::dropIfExists('containers');
    }
};
