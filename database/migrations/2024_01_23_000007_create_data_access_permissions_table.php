<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('data_access_permissions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('record_id')->constrained('patient_medical_records')->onDelete('cascade');
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->boolean('can_view')->default(false);
            $table->boolean('can_edit')->default(false);
            $table->timestamps();

            $table->unique(['record_id', 'user_id']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('data_access_permissions');
    }
};
