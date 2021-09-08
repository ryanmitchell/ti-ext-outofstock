<?php

namespace Thoughtco\Outofstock\Database\Migrations;

use DB;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StockTables extends Migration
{
    public function up()
    {
        Schema::create('thoughtco_outofstock', function (Blueprint $table) {
            $table->engine = 'InnoDB';
            $table->increments('id');
            $table->string('type', 15);
            $table->integer('type_id');
            $table->integer('location_id')->nullable();
            $table->dateTime('timeout')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('thoughtco_outofstock');
    }

}
