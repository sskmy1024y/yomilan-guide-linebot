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
            $table->string('image_path')->nullable();
            $table->enum('type', ['ATTRACTION', 'RESTAURANT', 'SHOP', 'OTHER']);
            $table->enum('area', ['GOODJOBA', 'SUN_SQUARE', 'RANRAN_AREA', 'FLAG_STREAT', 'FAMILY_AREA', 'BANDET_AREA', 'OTHER']);
            $table->float('latitude');
            $table->float('longtitude');
            $table->integer('price')->nullable();
            $table->boolean('use_pass')->default(false);
            $table->boolean('for_child')->default(false);
            $table->boolean('is_indoor');
            $table->boolean('enable')->default(false);
            $table->timestamps()->useCurrent();
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
