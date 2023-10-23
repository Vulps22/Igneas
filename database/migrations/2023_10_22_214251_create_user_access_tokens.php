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
		Schema::create('user_access_tokens', function (Blueprint $table) {
			$table->string('token', 60)->primary;
			$table->integer('user_id');
			$table->string('agent');
			$table->timestamps();
			$table->date('expires_at');
		});
	}

	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('user_access_tokens');
	}
};
