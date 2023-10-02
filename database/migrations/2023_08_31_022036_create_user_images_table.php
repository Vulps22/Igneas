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
		Schema::create('user_images', function (Blueprint $table) {
			$table->id();
			$table->unsignedBigInteger('user_id');
			$table->string('filename')->default('');
			$table->integer('position')->default(0);
			$table->boolean('is_message')->default(false);
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('user_images');
	}
};
