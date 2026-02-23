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
        Schema::create('shifts', function (Blueprint $table) {
            $table->id();

        $table->unsignedBigInteger('tenant_id');

        $table->string('name', 20);
        $table->time('start_time');
        $table->time('end_time');

        $table->unsignedSmallInteger('break_minutes')->nullable();
        $table->unsignedSmallInteger('grace_minutes')->nullable();

        $table->tinyInteger('status')
              ->default(1)
              ->comment('1: active, 2: inactive');

        $table->timestamps();

        $table->index('tenant_id');

        $table->foreign('tenant_id')
              ->references('id')
              ->on('tenants')
              ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('shifts');
    }
};
