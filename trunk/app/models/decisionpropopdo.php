<?php
	class Decisionpropopdo extends AppModel
	{
		public $name = 'Decisionpropopdo';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
	 				'validationdecision' => array( 'domain' => 'decisionpropopdo' ),
	 				'etatdossierpdo' => array( 'domain' => 'propopdo' )
				)
			),
			'Formattable',
			'Autovalidate',
		);

		public $validate = array(
			'datedecisionpdo' => array(
				'rule' => 'date',
				'message' => 'Veuillez entrer une date valide.',
				'allowEmpty' => true
			)
		);

		public $belongsTo = array(
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => 'propopdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Decisionpdo' => array(
				'className' => 'Decisionpdo',
				'foreignKey' => 'decisionpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
		
		public function beforeSave( $options = array() ) {
			$return = parent::beforeSave( $options );

			if( Configure::read( 'nom_form_pdo_cg' ) == 'cg66' ) {
				$decisionpdo_id = Set::extract( $this->data, 'Decisionpropopdo.decisionpdo_id' );
				$isvalidation = Set::extract( $this->data, 'Decisionpropopdo.isvalidation' );
				
				$etat = null;
				//'attaffect', 'attinstr', 'instrencours', 'attval', 'decisionval', 'dossiertraite', 'attpj'

				if ( !empty($decisionpdo_id) && empty($decisionpdo_id) )
					$etat = 'attval';
				elseif ( !empty($decisionpdo_id) && !empty($isvalidation) )
					$etat = 'decisionval';
				
				$this->data['Decisionpropopdo']['etatdossierpdo'] = $etat;
			}

			return $return;
		}
	}
?>
