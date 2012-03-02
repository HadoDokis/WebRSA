<?php
	class Piecetraitementpcg66 extends AppModel
	{
		public $name = 'Piecetraitementpcg66';

                public $belongsTo = array(
                    'Traitementpcg66' => array(
                            'className' => 'Traitementpcg66',
                            'foreignKey' => 'traitementpcg66_id',
                            'conditions' => '',
                            'fields' => '',
                            'order' => ''
                    ),
                    'Piecetypecourrierpcg66' => array(
                            'className' => 'Piecetypecourrierpcg66',
                            'foreignKey' => 'piecetypecourrierpcg66_id',
                            'conditions' => '',
                            'fields' => '',
                            'order' => ''
                    )
		);
	}
?>
