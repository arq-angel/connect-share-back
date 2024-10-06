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
        Schema::create('companies', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->text('image')->nullable();
            $table->text('address');
            $table->string('suburb');
            $table->string('state');
            $table->string('postal_code');
            $table->string('country');
            $table->string('email')->unique();
            $table->string('phone')->nullable();
            $table->string('website')->nullable();
            $table->string('industry')->nullable();
            $table->string('size')->nullable();
            $table->date('established_date')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
//            $table->softDeletes();

            // Optional indexes for frequently queried columns
            $table->index('email');
            $table->index('phone');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('companies');
    }
};
