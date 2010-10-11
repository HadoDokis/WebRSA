<?php
	class ApreEtatliquidatif extends AppModel
	{
		public $name = 'ApreEtatliquidatif';

		public $actsAs = array(
			'Enumerable', // FIXME ?
			'Frenchfloat' => array(
				'fields' => array(
					'montantattribue',
				)
			)
		);

		public $validate = array(
			'montantattribue' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => 'numeric',
					'message' => 'Valeur numérique seulement'
				)
			)
		);

		public $belongsTo = array(
			'Apre' => array(
				'className' => 'Apre',
				'foreignKey' => 'apre_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Etatliquidatif' => array(
				'className' => 'Etatliquidatif',
				'foreignKey' => 'etatliquidatif_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		/**
		*   Before Validate
		**/

		public function beforeValidate( $options = array() ) {
			if( $return = parent::beforeValidate( $options ) ) {
				$apre_id = Set::classicExtract( $this->data, "{$this->name}.apre_id" );
				$apre = $this->Apre->findById( $apre_id, null, null, -1 );

				$montantattribue = Set::classicExtract( $this->data, "{$this->name}.montantattribue" );
				$montantdejaverse = Set::classicExtract( $apre, 'Apre.montantdejaverse' );
				$montantaverser = Set::classicExtract( $this->data, "{$this->name}.montantaverser" );

				$montantversable = ( $montantaverser - $montantdejaverse );

				// FIXME: règles de validation ?
				if( $montantattribue < 0 ) {
					$this->validationErrors['montantattribue'] = "Le montant doit être positif";
				}
				if( (float)$montantdejaverse + $montantattribue > $montantaverser ) {
					$this->validationErrors['montantattribue'] = "Montant trop élevé (max: {$montantversable})";
				}
			}

			return $return;
		}
	}
?>
