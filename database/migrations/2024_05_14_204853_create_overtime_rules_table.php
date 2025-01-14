<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateOvertimeRulesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('overtime_rules', function(Blueprint $table)
		{
			$table->increments('overtime_rule_id');
			$table->integer('per_min')->default(1);
			$table->boolean('amount_of_deduction')->default(2);
			$table->boolean('status')->default(0);
			$table->integer('created_by')->nullable();
			$table->integer('updated_by')->nullable();
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
		Schema::drop('overtime_rules');
	}

}
