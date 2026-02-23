<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('employee_details', function (Blueprint $table) {
            $table->string('total_exp')->nullable()->after('dob');
            $table->enum('blood_group', ['A+', 'A-', 'B+', 'B-', 'AB+', 'AB-', 'O+', 'O-'])->nullable()->after('total_exp');
            $table->enum('know_about', [1,2,3,4])->nullable()->comment('1=company,2=friends,3=job portal,4=social')->after('blood_group');
            $table->text('major_illness')->nullable()->after('know_about');
            $table->string('ref_emp_name', 255)->nullable()->after('major_illness');
            $table->string('ref_emp_id', 50)->nullable()->after('ref_emp_name');
            $table->string('bank', 255)->nullable()->after('ref_emp_id');
            $table->string('branch', 255)->nullable()->after('bank');
            $table->string('account', 50)->nullable()->after('branch');
            $table->string('ifsc', 50)->nullable()->after('account');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_details', function (Blueprint $table) {
            $table->dropColumn('total_exp');
            $table->dropColumn('blood_group');
            $table->dropColumn('know_about');
            $table->dropColumn('major_illness');
            $table->dropColumn('ref_emp_name');
            $table->dropColumn('ref_emp_id');
            $table->dropColumn('bank');
            $table->dropColumn('branch');
            $table->dropColumn('account');
            $table->dropColumn('ifsc');
        });
    }
};
