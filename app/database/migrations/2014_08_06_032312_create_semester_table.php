<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateSemesterTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('semesters', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->string('Name');//Semester name, default should be season + year
                        $table->boolean('Active');
                        $table->date('ActiveStart');
                        $table->date('ActiveEnd');
                        $table->integer('modifiedBy')->unsigned();
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
		Schema::table('Semesters', function(Blueprint $table)
		{
			Schema::dropIfExists('Semesters');
		});
	}

}
