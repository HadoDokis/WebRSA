<?php
	class Suiviaideapretypeaide extends AppModel
	{
		public $name = 'Suiviaideapretypeaide';

		public $belongsTo = array(
			'Suiviaideapre' => array(
				'className' => 'Suiviaideapre',
				'foreignKey' => 'suiviaideapre_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>