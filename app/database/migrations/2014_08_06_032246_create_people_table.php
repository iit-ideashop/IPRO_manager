<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeopleTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->string('FirstName');
                        $table->string('LastName');
                        $table->integer('iGroupsID')->nullable()->default(0);
                        $table->string('Email')->unique();
                        $table->string('CWIDHash')->nullable();
                        $table->boolean('isAdmin')->default(false);
                        $table->string('modifiedBy');
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
		Schema::table('People', function(Blueprint $table)
		{
			Schema::dropIfExists('People');
		});
	}

}
