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
        Schema::table('employee_work_experiences', function (Blueprint $table) {
            $table->string('person_contact', 20)->nullable()->after('file');
            $table->string('employee_id',50)->nullable()->after('person_contact');
            $table->string('offer_letter',255)->nullable()->after('employee_id');
            $table->string('appointment_letter',255)->nullable()->after('offer_letter');
            $table->string('exp_letter',255)->nullable()->after('appointment_letter');
            $table->string('releiving_letter',255)->nullable()->after('exp_letter');
            $table->string('increment_letter',255)->nullable()->after('releiving_letter');
            $table->string('salary_slip',255)->nullable()->after('increment_letter');
            $table->string('bank_statement',255)->nullable()->after('salary_slip');
            $table->text('leaving_reason')->nullable()->after('bank_statement');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('employee_work_experiences', function (Blueprint $table) {
            $table->dropColumn('person_contact');
            $table->dropColumn('employee_id');
            $table->dropColumn('offer_letter');
            $table->dropColumn('appointment_letter');
            $table->dropColumn('exp_letter');
            $table->dropColumn('releiving_letter');
            $table->dropColumn('increment_letter');
            $table->dropColumn('salary_slip');
            $table->dropColumn('bank_statement');
            $table->dropColumn('leaving_reason');

        });
    }
};
