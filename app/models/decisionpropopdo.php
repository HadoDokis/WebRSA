<?php
	class Decisionpropopdo extends AppModel
	{
		public $name = 'Decisionpropopdo';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
	 				'validationdecision' => array( 'domain' => 'decisionpropopdo' ),
	 				'etatdossierpdo' => array( 'domain' => 'decisionpropopdo' )
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
				$propopdo = $this->Propopdo->find(
					'first',
					array(
						'conditions'=>array(
							'Propopdo.id'=>$this->data['Decisionpropopdo']['propopdo_id']
						)
					)
				);
				
				$typepdo_id = Set::extract( $propopdo, 'Propopdo.typepdo_id' );
				$iscomplet = Set::extract( $propopdo, 'Propopdo.iscomplet' );
				$decisionpdo_id = Set::extract( $this->data, 'Decisionpropopdo.decisionpdo_id' );
				$isvalidation = Set::extract( $this->data, 'Decisionpropopdo.isvalidation' );
				
				$etat = null;

				if( !empty( $typepdo_id ) && empty( $iscomplet ) && empty( $decisionpdo_id ) && empty( $isvalidation ) ){
					$etat = '1';
				}
				else if ( !empty( $typepdo_id ) && !empty( $iscomplet ) && empty( $decisionpdo_id ) && empty( $isvalidation ) ){
					$etat = '2';
				}
				else if ( !empty( $typepdo_id ) && !empty( $iscomplet ) && !empty( $decisionpdo_id ) && empty( $isvalidation ) ){
					$etat = '3';
				}
				else if ( !empty( $typepdo_id ) && !empty( $iscomplet ) && !empty( $decisionpdo_id ) && ( $isvalidation == 'O' ) ){
					$etat = '4';
				}
				else if ( !empty( $typepdo_id ) && ( $iscomplet == 'COM' ) && !empty( $decisionpdo_id ) && !empty( $isvalidation ) ){
					$etat = '5';
				}
				else if ( !empty( $typepdo_id ) && ( $iscomplet == 'INC' ) && !empty( $decisionpdo_id ) && !empty( $isvalidation ) ){
					$etat = '6';
				}
				
				$this->data['Decisionpropopdo']['etatdossierpdo'] = $etat;
			}

			return $return;
		}
	}
?>
