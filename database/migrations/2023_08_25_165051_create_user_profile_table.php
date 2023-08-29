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
			$table->string('display_name');
			$table->string('sexuality');
			$table->text('bio')->nullable();
			$table->integer('height')->nullable();
			$table->integer('weight')->nullable();
			$table->string('body type')->nullable();
			$table->string('position')->nullable();
			$table->string('dominance')->nullable();
			$table->string('ethnicity')->nullable();
			$table->string('relationship_status')->nullable();
			$table->string('looking_for')->nullable();
			$table->string('gender')->nullable();
			$table->string('pronouns')->nullable();
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
		Schema::dropIfExists('user_profiles');
	}
};
