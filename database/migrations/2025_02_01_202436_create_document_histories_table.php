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
		Schema::create('document_histories', static function (Blueprint $table) {
			$table->id()->primary()->autoIncrement();
			$table->bigIncrements('user_id');
			$table->bigIncrements('document_id');
			$table->string('title');
			$table->string('key')->nullable();
			$table->string('file_name')->nullable();
			$table->string('description')->nullable();
			$table->string('file_path')->nullable();
			$table->bigInteger('file_size')->nullable()->comment('File size in bytes');
			$table->string('file_type')->nullable()->comment('File extension');
			$table->string('action'); // create, update, delete
			$table->timestamps();
		});
	}
	
	/**
	 * Reverse the migrations.
	 */
	public function down(): void
	{
		Schema::dropIfExists('document_histories');
	}
};
