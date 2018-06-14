<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEntriesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('entries', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('parking_lot_id');
            $table->timestamp('arrived_at')->useCurrent();
            $table->timestamp('payed_at')->nullable();
            $table->timestamp('exited_at')->nullable();
            $table->float('price')->nullable();

            $table->foreign('parking_lot_id')->references('id')->on('parking_lots');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('entries');
    }
}
