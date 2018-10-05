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
            $table->string('aliexpress_product_id')->unique();
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
            $table->string('	other_image_url')->nullable();
            $table->string('other_image_url1')->nullable();
            $table->string('other_image_url2')->nullable();
            $table->string('other_image_url3')->nullable();
            $table->string('bullet_point1')->nullable();
            $table->string('bullet_point2')->nullable();
            $table->string('bullet_point3')->nullable();
            $table->string('bullet_point4')->nullable();
            $table->string('bullet_point5')->nullable();
            $table->string('generic_keywords1')->nullable();
            $table->string('generic_keywords2')->nullable();
            $table->string('generic_keywords3')->nullable();
            $table->string('generic_keywords4')->nullable();
            $table->string('generic_keywords5')->nullable();
            $table->string('query_keyword')->nullable();
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
