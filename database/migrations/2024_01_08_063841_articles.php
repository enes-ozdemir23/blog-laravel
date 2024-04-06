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
        Schema::create('articles', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedBigInteger('category_id');
            $table->string('title');
            $table->string('image');
            $table->longText('content');
            $table->integer('hit')->default(0);
            $table->integer('status')->default(0)->comment('0:pasif 1:aktif');
            $table->string('slug');
            $table->softDeletes();
            $table->timestamps();
            $table->foreign('category_id')              // burada tablolar arasında ilişki oluşturduk
                  ->references('id')                    //referans alacağı yer
                  ->on('categories');


            // Yani categories tablosundaki id etiketi ile category_id birbirine bağlanır
            // cascade eklenirse, silinen kategorinin altındaki verilerde silinir biz eklemedik
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('articles');
    }
};
