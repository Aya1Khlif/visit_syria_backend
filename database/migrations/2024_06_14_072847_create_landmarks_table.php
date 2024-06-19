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
        Schema::create('landmarks', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('location');
            $table->string('short_description');
            $table->text('long_description');
            $table->string('exterior_photos')->nullable();
            $table->string('interior_photos')->nullable();
           // $table->string('more_images')->nullable();
            $table->text('services')->nullable();
            $table->decimal('price', 8, 2);
            $table->foreignId('user_id')->constrained();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('landmarks');
    }
};
