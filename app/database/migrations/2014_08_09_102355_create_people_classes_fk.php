<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleClassesFk extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::table('PeopleProjects', function(Blueprint $table)
		{
			$table->foreign('UserID')->references('id')->on('users');
                        $table->foreign('ClassID')->references('id')->on('projects');
                        $table->foreign('AccessType')->references('id')->on('peopleClassesAccessType');
                        $table->foreign('ModifiedBy')->references('id')->on('users');
                        
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('PeopleClasses', function(Blueprint $table)
		{
			//
		});
	}

}
