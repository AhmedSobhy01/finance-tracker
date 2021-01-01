<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateDuesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('dues', function (Blueprint $table) {
            $table->id();
            $table->string('process_serial')->unique();
            $table->boolean('type')->comment('0 => he/she borrowed / 1 => he/she lended');
            $table->decimal('amount');
            $table->string('description')->nullable();
            $table->unsignedBigInteger('person_id');
            $table->timestamps();
            $table->timestamp('paid_at')->nullable();

            $table->foreign('person_id')->references('id')->on('people')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('dues');
    }
}