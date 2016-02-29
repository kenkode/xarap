<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAllowancesTable extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('allowances', function(Blueprint $table)
		{
			$table->increments('id');
			$table->string('allowance_name');
			$table->integer('organization_id')->unsigned()->default('0')->index('allowances_organization_id_foreign');
			$table->timestamps();
		});

		DB::table('allowances')->insert(array(
            array('allowance_name' => 'House Allowance','organization_id' => '1'),
            array('allowance_name' => 'Medical Allowance','organization_id' => '1'),
            array('allowance_name' => 'Transport Allowance','organization_id' => '1'),
        ));
	}


	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::drop('allowances');
	}

}
