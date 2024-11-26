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
            $table->id();
            $table->enum('status', ['active', 'waiting', 'expire'])->default('waiting');
            $table->enum('type', ['monthly', 'yearly']);
            $table->date('start')->nullable();
            $table->date('end')->nullable();
            $table->foreignId('magazine_id')->constrained('magazines', 'id')->cascadeOnDelete()->cascadeOnUpdate();
            $table->foreignId('subscriber_id')->constrained('users', 'id')->cascadeOnDelete()->cascadeOnUpdate();
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
