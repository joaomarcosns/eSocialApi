<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistersNameServerTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registers_name_server', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_names_server_id');
            $table->unsignedBigInteger('fk_domains_id');
            $table->timestamps();

            $table->foreign('fk_names_server_id')->references('id')->on('names_server');
            $table->foreign('fk_domains_id')->references('id')->on('domains');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('registers_name_server');
    }
}
