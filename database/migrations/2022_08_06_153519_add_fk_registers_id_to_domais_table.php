<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkRegistersIdToDomaisTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('domais', function (Blueprint $table) {
            $table->unsignedBigInteger('fk_registers_id');
            $table->foreign('fk_registers_id')->references('id')->on('registers');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('domais', function (Blueprint $table) {
            $table->dropColumn('fk_registers_id');
        });
    }
}
