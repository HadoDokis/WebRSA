<?php
	class Objetcontratprecedent extends AppModel
	{
		public $name = 'Objetcontratprecedent';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'objetcerprec'
				)
			)
		);

		public $belongsTo = array(
			'Contratinsertion' => array(
				'className' => 'Contratinsertion',
				'foreignKey' => 'contratinsertion_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>