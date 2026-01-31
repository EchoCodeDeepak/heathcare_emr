<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->unique();
            $table->timestamps();
        });

        // Insert default permissions
        $permissions = [
            ['name' => 'View Medical Records', 'slug' => 'view-medical-records', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Edit Medical Records', 'slug' => 'edit-medical-records', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'View Lab Results', 'slug' => 'view-lab-results', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Add Lab Results', 'slug' => 'add-lab-results', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'View Prescriptions', 'slug' => 'view-prescriptions', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Add Prescriptions', 'slug' => 'add-prescriptions', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'View Patient History', 'slug' => 'view-patient-history', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Manage Users', 'slug' => 'manage-users', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Manage Permissions', 'slug' => 'manage-permissions', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'View All Medical Records','slug' => 'view-all-medical-records','created_at' => now(),'updated_at' => now()],
            ['name' => 'Edit All Medical Records','slug' => 'edit-all-medical-records','created_at' => now(),'updated_at' => now()],
            ['name' => 'Delete All Medical Records','slug' => 'delete-all-medical-records','created_at' => now(),'updated_at' => now()]
        ];

        DB::table('permissions')->insert($permissions);
    }

    public function down()
    {
        Schema::dropIfExists('permissions');
    }
};
