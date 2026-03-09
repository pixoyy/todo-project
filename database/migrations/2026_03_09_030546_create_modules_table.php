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
        Schema::create('modules', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('module_group_id')->nullable();
            $table->string('name', 100);
            $table->string('route', 100);
            $table->integer('order');
            $table->string('icon', 100)->nullable();
            $table->tinyInteger('is_shown');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('module_group_id')->references('id')->on('module_groups');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('modules');
    }
};
