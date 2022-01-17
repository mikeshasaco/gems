<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNftusersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nftusers', function (Blueprint $table) {
            $table->id();
            $table->string('project_name')->nullable();
            $table->string('email')->nullable();
            $table->string('opensea_link')->nullable();
            $table->string('wallet_address')->nullable();
            $table->string('twitter_link')->nullable();
            $table->string('discord_link')->nullable();
            
            $table->string('maximum_number_in_collection')->nullable();
            $table->string('collection_blockchain')->nullable();
            $table->string('collection_contract_address')->nullable();
            $table->string('item_sold')->nullable();
            $table->timestamp('created_at')->useCurrent();
            $table->timestamp('updated_at')->useCurrent()->useCurrentOnUpdate();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('nftusers');
    }
}
