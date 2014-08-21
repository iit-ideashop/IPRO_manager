<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePickupsDB extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('pickups', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('PersonID')->unsigned();
                        $table->integer('RetreiveCode')->nullable();
                        $table->integer('AuthorizeCode')->nullable();
                        $table->boolean('Completed')->default(false);
                        $table->text('SignatureData')->nullable();
                        $table->text('OverrideReason')->nullable();
                        $table->integer('CreatedBy')->unsigned();
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
		Schema::drop('pickups');
	}

}
