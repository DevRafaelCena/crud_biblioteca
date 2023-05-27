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
        Schema::create('users_libraries', function (Blueprint $table) {
            $table->id();
            $table->string('name')->max(255)->required();
            $table->string('email')->max(255)->required();
            $table->integer('deleted')->default(0)->max(1)->required();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('users_libraries');
    }
};
