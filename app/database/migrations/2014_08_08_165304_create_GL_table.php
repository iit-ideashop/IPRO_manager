<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateGLTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ledgerEntries', function(Blueprint $table)
		{
			$table->increments('id');
                        $table->integer('AccountNumber')->unsigned();
                        $table->enum('EntryType',array('ORDER','REIMBURSEMENT','TRANSFER','BUDGET','OTHER','RECONCILE'));
                        $table->integer('EntryTypeID')->nullable();
                        $table->double('Credit',9,2);
                        $table->double('Debit',9,2);
                        $table->double('NewAccountBalance',9,2);
                        $table->integer('EnteredBy')->unsigned();
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
		Schema::drop('GeneralLedger');
	}

}
