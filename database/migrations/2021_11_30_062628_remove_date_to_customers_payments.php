<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RemoveDateToCustomersPayments extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (Schema::hasColumn('customers_payments', 'payment_date')){

            Schema::table('customers_payments', function (Blueprint $table) {
                $table->dropColumn('payment_date');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('customers_payments', function (Blueprint $table) {
            //
        });
    }
}
