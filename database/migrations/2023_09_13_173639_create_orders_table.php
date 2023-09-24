<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->unsignedBigInteger('provider_id');
            $table->unsignedBigInteger('address_id')->nullable();
            $table->decimal('sub_total_price', 10, 2)->nullable();
            $table->decimal('coupon_amount', 10, 2)->nullable();
            $table->decimal('total_amount', 10, 2)->nullable();
            $table->enum('type', ['Cart', 'Order'])->nullable();
            $table->enum('status',
             ['Accepted','Pending','Completed','Rejected']
             )->nullable();
            $table->enum('payment_status', ['Pending', 'Paid'])->nullable();
            $table->enum('shipping_status', ['Pending','Shipped','OnTheWay', 'Delivered'])->nullable();
            $table->enum('shipping_method', ['CaptainAsk', 'OurDelivery', 'Pickup'])->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('orders');
    }
}
