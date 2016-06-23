<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeoplesChoiceBlacklistTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('peoplesChoiceBlacklist', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer("Semester")->unsigned();
            $table->integer("ProjectID")->unsigned();
			$table->timestamps();

            $table->foreign("Semester")->references("id")->on("semesters");
            $table->foreign("ProjectID")->references("id")->on("projects");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('peoplesChoiceBlacklist');
	}

}
