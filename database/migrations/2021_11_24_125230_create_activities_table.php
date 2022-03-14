<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateActivitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('activities', function (Blueprint $table) {
            $table->id();
            $table->foreignId('customer_id')->constrained()->onDelete('cascade');
            $table->date('activity_date');
            $table->foreignId('staff_id')->constrained()->onDelete('cascade');
            $table->double('staff_commission_amount')->default(0);
            $table->integer('staff_commission_type');
            $table->double('staff_commission_sum');
            $table->foreignId('package_id')->constrained()->onDelete('cascade');
            $table->double('quantity')->default(0);
            $table->double('price')->default(0);
            $table->double('total_amount')->default(0);
            $table->string('description')->nullable();
            $table->integer('visible')->default(1);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('activities');
    }
}
