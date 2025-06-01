<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Drop the existing foreign key
            $table->dropForeign(['product_id']);
            
            // Add the new foreign key with ON DELETE SET NULL
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products')
                  ->onDelete('set null');
        });
    }

    public function down()
    {
        Schema::table('transactions', function (Blueprint $table) {
            // Drop the modified foreign key
            $table->dropForeign(['product_id']);
            
            // Restore the original foreign key
            $table->foreign('product_id')
                  ->references('id')
                  ->on('products');
        });
    }
}; 