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
        Schema::create('facilities', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->foreignId('company_id')->constrained()->onDelete('cascade');
            $table->text('image')->nullable();
            $table->text('address');
            $table->string('suburb');
            $table->string('state');
            $table->string('postal_code');
            $table->string('country');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('size');
            $table->date('established_date');
            $table->boolean('is_active')->default(true);
            $table->timestamps();
//            $table->softDeletes();

            // Optional: Indexing for performance on frequently queried fields
            $table->index('email');
            $table->index('phone');
            $table->index('company_id');

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('facilities');
    }
};
