<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUnitsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('units', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('property_id');
            $table->enum('rental_terms', ['LONG', 'SHORT']);
            $table->enum('long_term_minimum', ['6_MONTHS', '1_YEAR_OR_MORE'])->nullable();
            $table->enum('short_term_minimum', ['3_MONTHS', '2_MONTHS', '1_MONTH', '3_WEEKS', '2_WEEKS', '1_WEEK', '6_NIGHTS', '5_NIGHTS', '4_NIGHTS', '3_NIGHTS', '2_NIGHTS', '1_NIGHTS'])->nullable();
            $table->decimal('long_term_rate', 9, 3)->nullable();
            $table->decimal('short_term_daily_rate', 9, 3)->nullable();
            $table->decimal('short_term_weekly_rate', 9, 3)->nullable();
            $table->decimal('short_term_monthly_rate', 9, 3)->nullable();
            $table->string('unit_number');
            $table->string('unit_floor');
            $table->enum('furnishing', ['FULLY_FURNISHED', 'SEMI_FURNISHED', 'UNFURNISHED']);
            $table->enum('bedrooms', range(1, 5));
            $table->enum('bathrooms', range(1, 5));
            $table->text('amenities')->nullable();
            $table->string('inclusions')->nullable();
            $table->text('photos')->nullable();
            $table->unsignedInteger('created_by');
            $table->unsignedInteger('approved_by')->nullable();
            $table->boolean('status')->nullable();
            $table->timestamps();

            $table->foreign('property_id')->references('id')->on('properties')->onDelete('cascade');
            $table->foreign('created_by')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('approved_by')->references('id')->on('users')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('units');
    }
}
