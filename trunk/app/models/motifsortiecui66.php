<?php
	class Motifsortiecui66 extends AppModel
	{
		public $name = 'Motifsortiecui66';

		public $actsAs = array(
			'Autovalidate',
			'Formattable'
		);

		public $validate = array(
			'name' => array(
				array(
					'rule' => 'isUnique',
					'message' => 'Valeur déjà utilisée'
				)
			)
		);

	}
?>