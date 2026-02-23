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
        Schema::create('onboarding_invitations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('verification_code', 20);
            $table->dateTime('accepted_at')->nullable();
            $table->dateTime('expired_at')->nullable();
            $table->tinyInteger('is_sent')->default(0);
            $table->string('progress', 5)->nullable();
            $table->enum('status', [0,1,2])->default(0)->comment('1:visited, 2:submited');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('onboarding_invitations');
    }
};
