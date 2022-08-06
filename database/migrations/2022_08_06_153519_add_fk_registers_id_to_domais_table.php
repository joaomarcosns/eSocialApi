<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

<<<<<<<< HEAD:database/migrations/2022_08_06_154317_add_fk_registers_id_to_domains_table.php
class AddFkRegistersIdToDomainsTable extends Migration
========
class AddFkRegistersIdToDomaisTable extends Migration
>>>>>>>> e2f03a640ac50b59be5ce7a6c6877b9957393149:database/migrations/2022_08_06_153519_add_fk_registers_id_to_domais_table.php
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
<<<<<<<< HEAD:database/migrations/2022_08_06_154317_add_fk_registers_id_to_domains_table.php
        Schema::table('domains', function (Blueprint $table) {
========
        Schema::table('domais', function (Blueprint $table) {
>>>>>>>> e2f03a640ac50b59be5ce7a6c6877b9957393149:database/migrations/2022_08_06_153519_add_fk_registers_id_to_domais_table.php
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
<<<<<<<< HEAD:database/migrations/2022_08_06_154317_add_fk_registers_id_to_domains_table.php
        Schema::table('domains', function (Blueprint $table) {
========
        Schema::table('domais', function (Blueprint $table) {
>>>>>>>> e2f03a640ac50b59be5ce7a6c6877b9957393149:database/migrations/2022_08_06_153519_add_fk_registers_id_to_domais_table.php
            $table->dropColumn('fk_registers_id');
        });
    }
}
