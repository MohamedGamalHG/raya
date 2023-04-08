<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->decimal('qty_beaf');
            $table->decimal('qty_cheese');
            $table->decimal('qty_onion');
            $table->decimal('real_beaf');
            $table->decimal('real_cheese');
            $table->decimal('real_onion');
            $table->boolean('send_mail_beaf')->default(0);
            $table->boolean('send_mail_cheese')->default(0);
            $table->boolean('send_mail_onion')->default(0);
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
        Schema::dropIfExists('stocks');
    }
};
