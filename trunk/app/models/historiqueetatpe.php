<?php
	class Historiqueetatpe extends AppModel
	{
		public $name = 'Historiqueetatpe';

        public $actsAs = array(
            'Enumerable' => array(
                'fields' => array(
                    'etat'
                )
            )
        );

		// FIXME: validation

        public $recursive = -1;

		public $belongsTo = array(
			'Informationpe' => array(
				'className' => 'Informationpe',
				'foreignKey' => 'informationpe_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>