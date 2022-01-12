<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateNftdetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('nftdetails', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->string('nft_name');
            $table->string('nft_link');
            $table->integer('total_likes')->default(0);
            $table->string('image')->nullable();
            $table->string('popularity')->nullable();
            $table->string('community')->nullable();
            $table->string('utility')->nullable();
            $table->string('originality')->nullable();
            $table->string('total')->nullable();
            $table->tinyInteger('verify')->default(0);
            $table->string('verify_token')->nullable();
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
        Schema::dropIfExists('nftdetails');
    }
}
