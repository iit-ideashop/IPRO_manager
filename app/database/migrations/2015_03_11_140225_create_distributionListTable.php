<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDistributionListTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('distribution_lists', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string("distributionList");
			$table->integer("UserID")->unsigned();
			$table->timestamps();
			$table->foreign("UserID")->references("id")->on("users");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('distribution_lists');
	}

}
