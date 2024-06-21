<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRestaurantServiceTable extends Migration
{
    public function up()
    {
        Schema::create('restaurant_service', function (Blueprint $table) {
            $table->id();
            $table->foreignId('restaurant_id')->constrained('restaurants');
            $table->foreignId('service_id')->constrained('services');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('restaurant_service');
    }
}
