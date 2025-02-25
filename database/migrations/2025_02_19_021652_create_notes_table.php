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
        Schema::create('notes', function (Blueprint $table) {
            $table->id();
            $table->string('description');
            $table->boolean('completed');
            $table->date('start_date');
            $table->date('end_date');
            $table->unsignedBigInteger('type_id');
            $table->unsignedBigInteger('group_id');
            $table->timestamps();
            $table->foreign('type_id')->references('id')->on('types');
            $table->foreign('group_id')->references('id')->on('groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('notes');
    }
};
