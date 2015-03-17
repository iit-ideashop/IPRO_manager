<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePrintSubmissionDb extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('printSubmissions', function(Blueprint $table)
		{
			$table->increments('id');
            $table->integer("UserID")->unsigned();
            $table->integer("ProjectID")->unsigned();
            $table->string("filename");
            $table->string("size");
            $table->string("dimensions");
            $table->string("thumb_filename");
            $table->boolean("override");
            $table->integer("status")->unsigned();
            $table->text("reject_comments");
			$table->timestamps();

            $table->foreign("UserID")->references("id")->on("users");
            $table->foreign("ProjectID")->references("id")->on("projects");
            $table->foreign("status")->references("id")->on("printSubmissionStatuses");
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('printSubmission');
	}

}
