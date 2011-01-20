<?php

	class BudgetapreFixture extends CakeTestFixture {
		var $name = 'Budgetapre';
		var $table = 'budgetsapres';
		var $import = array( 'table' => 'budgetsapres', 'connection' => 'default', 'records' => false);
		var $records = array(
			array(
				'id' => '1',
				'exercicebudgetai' => '1',
				'montantattretat' => '1',
				'ddexecutionbudge' => '2010-02-24',
				'dfexecutionbudge' => '2009-02-24',
			),
		);
	}

?>
