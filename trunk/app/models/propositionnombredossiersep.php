<?php
	class Propositionnombredossiersep extends AppModel
	{
		public $name = 'Propositionnombredossiersep';

		public $useTable = false;

// 		'limit' => $demandereorient['Demandereorient']['limit'],
// 		'numcomptt' => $demandereorient['Adresse']['numcomptt'],

		public $validate = array(
			'limit' => array(
				array( 'rule' => 'notEmpty' ),
				array( 'rule' => 'integer' )
			)
		);
	}
?>