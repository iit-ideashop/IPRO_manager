<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ModifyPrintSubmissionsTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('printSubmissions', function(Blueprint $table)
		{
			$table->string("original_filename")->after("filename");
			$table->integer("count_copies")->after("dimensions");
			$table->enum('file_type', array('Poster', 'Brochure'))->after("thumb_filename");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('printSubmissions', function(Blueprint $table)
		{
			//
		});
	}

}
