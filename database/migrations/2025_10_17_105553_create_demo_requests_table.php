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
        Schema::create('demo_requests', function (Blueprint $table) {
            $table->id();
            $table->string('name', 255);
            $table->string('organization', 255)->nullable();
            $table->string('size', 50)->nullable();
            $table->string('email', 150);
            $table->string('contact', 30);
            $table->text('additional')->nullable();
            $table->enum('status', [0, 1])->nullable()->comment('0:unread, 1:read');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('demo_requests');
    }
};
