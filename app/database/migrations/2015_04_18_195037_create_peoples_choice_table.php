<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeoplesChoiceTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('peoplesChoice', function(Blueprint $table)
		{
			$table->increments('id'); //Unique ID for the vote
            $table->string("FirstName");
            $table->string("LastName");
            $table->string("idnumber"); // Person's ID number in specific format or a hash of the CWID
            $table->boolean("IIT");
            $table->integer("ProjectID")->unsigned();
            $table->integer("Semester")->unsigned();
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
		Schema::drop('peoplesChoice');
	}

}
