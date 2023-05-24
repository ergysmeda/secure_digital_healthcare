<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('appointments', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('patient_id');
            $table->unsignedBigInteger('healthcare_professional_id');
            $table->unsignedBigInteger('status_id');
            $table->dateTime('appointment_time');
            $table->timestamps();

            $table->foreign('patient_id')->references('id')->on('users');
            $table->foreign('healthcare_professional_id')->references('id')->on('users');
        });

        Schema::table('appointments', function (Blueprint $table) {
            $table->foreign('status_id')->references('id')->on('appointment_statuses');
        });
    }

    public function down()
    {
        Schema::dropIfExists('appointments');
    }
};
