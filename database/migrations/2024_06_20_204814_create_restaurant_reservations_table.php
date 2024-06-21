<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantReservationsTable extends Migration
{
    public function up()
    {
        Schema::create('restaurant_reservations', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained('restaurants');
            $table->foreignId('user_id')->constrained('users');
            $table->date('reservation_date');
            $table->time('reservation_time');
            $table->integer('number_of_guests');
            $table->text('special_requests')->nullable();
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('restaurant_reservations');
    }
}
