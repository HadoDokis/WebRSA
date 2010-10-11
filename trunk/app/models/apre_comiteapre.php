<?php
	class ApreComiteapre extends AppModel
	{
		public $name = 'ApreComiteapre';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'decisioncomite' => array( 'type' => 'decisioncomite', 'domain' => 'apre' ),
					'recoursapre' => array( 'type' => 'recoursapre', 'domain' => 'apre' ),
				)
			),
			'Frenchfloat' => array( 'fields' => array( 'montantattribue' ) )

		);

		public $validate = array(
			'decisioncomite' => array(
				array(
					'rule'      => array( 'inList', array( 'AJ', 'ACC', 'REF' ) ),
					'message'   => 'Veuillez choisir une valeur.',
					'allowEmpty' => false
				)
			),
			'montantattribue' => array(
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.',
					'allowEmpty' => false
				)
			),
		);

		public $belongsTo = array(
			'Apre' => array(
				'className' => 'Apre',
				'foreignKey' => 'apre_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Comiteapre' => array(
				'className' => 'Comiteapre',
				'foreignKey' => 'comiteapre_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			// Apparemment, n'est jamais utilisé comme ceci
			/*'ComitePcd' => array(
				'className' => 'ComitePcd',
				'foreignKey' => 'comite_pcd_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)*/
		);

		/**
		*   Before Save pour remettre à zéro les montants attribués par le comité si la décision est passée en Refus
		**/

		public function beforeSave( $options = array() ) {
			$return = parent::beforeSave( $options );
			//FIXME: a mettre dans le beforeValidate
			if( isset( $this->data[$this->name]['decisioncomite'] ) ) {
				if( $this->data[$this->name]['decisioncomite'] != 'ACC' ) {
					$this->data[$this->name]['montantattribue'] = null;
				}
				else {
					$apre = $this->Apre->read( array( 'id', $this->Apre->sousRequeteMontantTotal().' AS "Apre__montantaverser"' ), $this->data[$this->name]['apre_id'] );


					$montantattribue = Set::classicExtract( $this->data, "{$this->alias}.montantattribue" );
					if( ( Set::check( $montantattribue ) == false ) && !is_numeric( $montantattribue ) ) {
						$this->invalidate( 'montantattribue', 'Veuillez saisir un montant' );
					}

					/// INFO: devrait fonctionner avec comparison, mais ce n'est pas le cas
					$montantpositif = ( $montantattribue >= 0 );
					if( !$montantpositif ) {
						$this->invalidate( 'montantattribue', 'Veuillez entrer un nombre positif' );
					}

					$return = ( $return && $montantpositif );
				}
			}

			return $return;
		}
	}
?>
