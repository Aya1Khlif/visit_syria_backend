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
        Schema::create('hotels', function (Blueprint $table) {
            $table->id();
            // $table->unsignedBigInteger('user_id');

            $table->foreignId('user_id')->constrained();
            $table->string('name');
            $table->string('location');
            $table->text('short_description');
            $table->text('long_description')->nullable();
            $table->string('exterior_photos')->nullable();
            $table->string('interior_photos')->nullable();
           // $table->string('more_images')->nullable();
            $table->json('services')->nullable();
            $table->decimal('price', 8, 2);
            // $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('hotels');
    }
};
