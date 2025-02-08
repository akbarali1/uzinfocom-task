<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
	/**
	 * Run the migrations.
	 */
	public function up(): void
	{
		Schema::create('documents', static function (Blueprint $table) {
			$table->id()->primary()->autoIncrement();
			$table->bigIncrements('user_id');
			$table->string('title');
			$table->string('description')->nullable();
			$table->string('file_name');
			$table->string('file_path');
			$table->bigInteger('file_size')->nullable()->comment('File size in bytes');
			$table->string('file_type')->nullable()->comment('File extension');
			$table->timestamp('last_edited_at')->nullable()->comment('Last edited time');
			$table->timestamps();
			$table->softDeletes();
		});
	}
	
	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('documents');
	}
};
