<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFkDomainsIdToDomainsNamesServer extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('names_server', function (Blueprint $table) {
            $table->unsignedBigInteger('fk_domains_id');
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
        Schema::table('domains_names_server', function (Blueprint $table) {
            $table->dropColumn('fk_domains_id');
        });
    }
}
