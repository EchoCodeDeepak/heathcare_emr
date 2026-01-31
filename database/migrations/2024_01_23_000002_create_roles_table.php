<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Insert default roles
        DB::table('roles')->insert([
            ['name' => 'System Admin', 'slug' => 'system-admin', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Doctor', 'slug' => 'doctor', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Nurse', 'slug' => 'nurse', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Lab Technician', 'slug' => 'lab-technician', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Patient', 'slug' => 'patient', 'created_at' => now(), 'updated_at' => now()],
        ]);
    }

    public function down()
    {
        Schema::dropIfExists('roles');
    }
};
