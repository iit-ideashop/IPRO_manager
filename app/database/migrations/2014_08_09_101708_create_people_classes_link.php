<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleClassesLink extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('PeopleProjects', function(Blueprint $table)
		{
                        $table->increments('id');
			$table->integer('UserID')->unsigned();
                        $table->integer('ClassID')->unsigned();
                        $table->integer('AccessType')->unsigned();
                        $table->integer('ModifiedBy')->unsigned();
                        $table->timestamps();
                        
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
