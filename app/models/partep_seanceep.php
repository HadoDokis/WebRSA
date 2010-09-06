<?php
	class PartepSeanceep extends AppModel
	{
		public $name = 'PartepSeanceep';

		public $actsAs = array(
			'Autovalidate',
            'Enumerable' => array(
                'fields' => array(
                    'reponseinvitation',
                    'presence'
                )
            ),
            'Formattable'
		);

		/*public $belongsTo = array(
			'Partep',
			'Seanceep',
            'RemplacantPartep' =>array(
                'className' => 'Partep',
                'foreignKey' => 'remplacant_partep_id'
            )
		);*/
	}
?>