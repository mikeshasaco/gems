<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddExtraFieldsNftdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        Schema::table('nftdetails', function (Blueprint $table) {
            $table->string('growth_evaluation')->nullable();
            $table->string('resell_evaluation')->nullable();
            $table->string('potential_blue_chip')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nftdetails', function($table)
        {
            $table->dropColumn('growth_evaluation');
            $table->dropColumn('resell_evaluation');
            $table->dropColumn('potential_blue_chip');
        });
    }
}
