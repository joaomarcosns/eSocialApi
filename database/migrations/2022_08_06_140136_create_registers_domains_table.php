<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRegistersDomainsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('registers_domains', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('fk_registers_id');
            $table->unsignedBigInteger('fk_domains_id');
            $table->timestamps();

            $table->foreign('fk_registers_id')->references('id')->on('registers');
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
        Schema::dropIfExists('registers_domains');
    }
}
