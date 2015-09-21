<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProjectScrumFKRelations extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('projectScrums', function(Blueprint $table)
		{
			//Create FK relations for the ProjectScrums db
            $table->foreign('ClassID')->references('id')->on('projects');
            $table->foreign('CreatedBy')->references('id')->on('users');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('projectScrums', function(Blueprint $table)
		{
			//
		});
	}

}
