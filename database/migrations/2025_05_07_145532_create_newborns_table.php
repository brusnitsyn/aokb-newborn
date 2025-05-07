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
        Schema::create('newborns', function (Blueprint $table) {
            $table->id();
            $table->integer('MedicalHistoryID');
            $table->string('FAMILY')->default("");
            $table->string('Name')->default("");
            $table->string('OT')->default("");
            $table->dateTime('BD')->default("01/01/1900");
            $table->boolean('Sex')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('newborns');
    }
};
