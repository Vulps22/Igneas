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
		Schema::create('messages', function (Blueprint $table) {
			$table->id();
			$table->foreignId('conversation_id');
			$table->integer('sender_id');
			$table->string('text')->default('');
			$table->integer('image_id')->nullable();
			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('messages');
	}
};
