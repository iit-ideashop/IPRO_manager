<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddEnumOptionsToBarcodes extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('barcodes', function(Blueprint $table)
		{
			//
			$table->string("other_description")->after("reference");
		});
		DB::statement("ALTER TABLE barcodes CHANGE COLUMN type type ENUM('ITEM', 'PRINT', 'OTHER')");
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('barcodes', function(Blueprint $table)
		{
			//
		});
	}

}
