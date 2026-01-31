<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('patient_medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('doctor_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('medical_history')->nullable();
            $table->text('diagnosis')->nullable();
            $table->text('prescription')->nullable();
            $table->text('lab_results')->nullable();
            $table->string('blood_pressure')->nullable();
            $table->string('temperature')->nullable();
            $table->string('pulse_rate')->nullable();
            $table->string('weight')->nullable();
            $table->string('height')->nullable();
            $table->text('allergies')->nullable();
            $table->text('notes')->nullable();
            $table->enum('visibility_level', ['private', 'restricted', 'public'])->default('private');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('patient_medical_records');
    }
};
