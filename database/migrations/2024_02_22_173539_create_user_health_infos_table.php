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
        Schema::create('user_health_infos', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->boolean('chest_disorders')->default(false);
            $table->boolean('physical_injuries')->default(false);
            $table->boolean('ear_disorders')->default(false);
            $table->boolean('allergies')->default(false);
            $table->boolean('heart_disorders')->default(false);
            $table->boolean('lung_disorders')->default(false);
            $table->boolean('low_muscle_tones')->default(false);
            $table->boolean('wears_spectacles')->default(false);
            $table->boolean('takes_medication')->default(false);
            $table->boolean('past_swimming_lessons')->default(false);
            $table->text('past_swimming_instructor_duration')->nullable();
            $table->boolean('bad_experiences')->default(false);
            $table->boolean('medical_aid_membership')->default(false);
            $table->string('medical_aid_name')->nullable();
            $table->string('medical_aid_number')->nullable();
            $table->string('main_member_full_name')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('user_health_infos');
    }
};
