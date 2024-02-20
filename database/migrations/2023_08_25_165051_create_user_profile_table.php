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
        Schema::create('users_profile', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id');
            $table->string('display_name')->nullable()->default('');
            $table->string('sexuality')->nullable()->default('');
            $table->string('bio')->nullable()->default('');
            $table->integer('height')->nullable()->default(0);
            $table->integer('weight')->nullable()->default(0);
            $table->string('body_type')->nullable()->default('');
            $table->string('position')->nullable()->default('');
            $table->string('dominance')->nullable()->default('');
            $table->string('ethnicity')->nullable()->default('');
            $table->string('relationship_status')->nullable()->default('');
            $table->string('looking_for')->nullable()->default('');
            $table->string('gender')->nullable()->default('');
            $table->string('pronouns')->nullable()->default('');
            $table->boolean('show_location')->default(false);
            $table->boolean('show_age')->default(false);
            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('users_profile');
    }
};