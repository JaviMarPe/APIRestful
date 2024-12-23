<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Product;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('products', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('name');
            $table->string('description', 1000);
            $table->integer('quantity')->unsigned();
            $table->string('status')->default(Product::PRODUCTO_NO_DISPONIBLE);
            $table->string('image');
            $table->unsignedBigInteger('seller_id');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('seller_id')->references('id')->on('users');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
