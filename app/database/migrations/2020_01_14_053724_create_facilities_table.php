<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateFacilitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('facilities', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->text('description');
            $table->integer('price')->default(0);
            $table->string('image_path')->nullable();
            $table->integer('area_id');
            $table->enum('type', ['ATTRACTION', 'RESTAURANT', 'SHOP', 'OTHER']);
            $table->float('latitude');
            $table->float('longitude');
            $table->boolean('use_pass')->default(false);
            $table->boolean('for_child')->default(false);
            $table->boolean('is_indoor');
            $table->string('capacity')->nullable();
            $table->string('age_limit')->nullable();
            $table->string('physical_limit')->nullable();
            $table->integer('require_time')->nullable();
            $table->boolean('enable')->default(true);
            $table->string('url');
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
        Schema::dropIfExists('facilities');
    }
}
