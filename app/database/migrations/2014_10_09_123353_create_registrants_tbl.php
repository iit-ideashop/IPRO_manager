<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegistrantsTbl extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('registrants', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->string('firstName');
                        $table->string('lastName');
                        $table->string('organization');
                        $table->string('phone');
                        $table->string('address');
                        $table->string('email');
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
		Schema::drop('registrants');
	}

}
