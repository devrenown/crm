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
        Schema::create('subscriptions', function (Blueprint $table) {
            $table->id(); // Creates BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY
    
            $table->unsignedBigInteger('tenant_id')->index();
            $table->foreign('tenant_id')
                ->references('id')
                ->on('tenants')
                ->onDelete('cascade');
    
            $table->unsignedBigInteger('plan_id')->index();
            $table->foreign('plan_id')
                ->references('id')
                ->on('plans')
                ->onDelete('cascade');
    
            $table->date('start_date');
            $table->date('end_date');
            
            $table->tinyInteger('status')
                ->nullable()
                ->comment('1: active, 2: expired, 3: canceled');

            $table->string('razorpay_payment_id')->nullable()->unique();
            $table->json('razorpay_payment_payload')->nullable();
    
            $table->timestamps();
        });
    }


    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('subscriptions');
    }
};
