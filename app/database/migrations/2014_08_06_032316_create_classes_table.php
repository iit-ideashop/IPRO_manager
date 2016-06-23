<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateClassesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('projects', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->string('UID');//unique ID for the class, not really unique as an ipro num can be reused ex IPRO 300
                        $table->string('Name');// Name of the class
                        $table->text('Description');//Class description
                        $table->text('TimeSlots')->nullable(); // Array of time slots in JSON format
                        $table->integer('Semester')->unsigned();//FK Semester ID
                        $table->integer('iGroupsID')->default(0);
                        $table->integer('ParentClass')->nullable()->unsigned();
                        $table->integer('modifiedBy')->unsigned();
                        $table->timestamps();
                        $table->softDeletes();

		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::table('Classes', function(Blueprint $table)
		{
			Schema::dropIfExists('Classes');
		});
	}

}
