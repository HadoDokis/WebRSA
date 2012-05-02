<?php
	class Mtpcg66Pmtcpcg66 extends AppModel
	{
		public $name = 'Mtpcg66Pmtcpcg66';

		public $belongsTo = array(
			'Modeletraitementpcg66' => array(
				'className' => 'Modeletraitementpcg66',
				'foreignKey' => 'modeletraitementpcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Piecemodeletypecourrierpcg66' => array(
				'className' => 'Piecemodeletypecourrierpcg66',
				'foreignKey' => 'piecemodeletypecourrierpcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
	}
?>