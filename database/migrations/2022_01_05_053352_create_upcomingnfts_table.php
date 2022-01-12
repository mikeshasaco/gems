<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUpcomingnftsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('upcomingnfts', function (Blueprint $table) {
            $table->id();
            // $table->integer('user_id');
            $table->string('project_name');
            $table->date('release_date');
            $table->time('release_time');
            $table->string('website')->nullable();
            $table->text('socialmedia')->nullable();
            $table->string('timeZoneSelect')->nullable();
            $table->text('images')->nullable();
            $table->string('max_number_collection')->nullable();
            $table->string('unit_price_eth')->nullable();
            $table->longText('briefdescription')->nullable();
            $table->string('network')->nullable();
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
        Schema::dropIfExists('upcomingnfts');
    }
}
