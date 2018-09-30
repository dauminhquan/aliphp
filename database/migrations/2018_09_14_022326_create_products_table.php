<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->increments('id');
            $table->string('item_name');
            $table->string('feed_product_type')->nullable();
            $table->string('item_sku')->nullable();
            $table->string('brand_name')->nullable();
            $table->string('external_product_id')->nullable();
            $table->string('external_product_id_type')->nullable();
            $table->string('part_number')->nullable();
            $table->string('manufacturer')->nullable();
            $table->string('item_type')->nullable();
            $table->string('unit_count')->nullable();
            $table->string('unit_count_type')->nullable();
            $table->string('aliexpress_product_id');
            $table->string('standard_price')->nullable();
            $table->string('quantity')->nullable();
            $table->string('missing_keyset_reason')->nullable();
            $table->string('update_delete')->default('Update');
            $table->text('product_description')->nullable();
            $table->string('main_image_url');
            $table->string('swatch_image')->nullable();
            $table->text('colors')->nullable();
            $table->text('other_images')->nullable();
            $table->text('sizes')->nullable();
            $table->string('branch_aliexpress');
            $table->text('url_aliexpress');
            $table->text('specifics')->nullable();
            $table->string('key_works')->nullable();
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
        Schema::dropIfExists('products');
    }
}
