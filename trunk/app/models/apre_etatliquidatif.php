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
		* Before Validate
		*/

		public function beforeValidate( $options = array() ) {
			if( $return = parent::beforeValidate( $options ) ) {
				$apre_id = Set::classicExtract( $this->data, "{$this->alias}.apre_id" );
				$apre = $this->Apre->findById( $apre_id, null, null, -1 );

				// Déjà versé dans les autres états liquidatifs
				$montantdejaverse = Set::classicExtract( $apre, 'Apre.montantdejaverse' );
				$nbpaiementsouhait = Set::classicExtract( $apre, 'Apre.nbpaiementsouhait' );//FIXME: parfois il est dans le précédent ?
				// Montant attribué dans ce comité
				$montantattribue = Set::classicExtract( $this->data, "{$this->alias}.montantattribue" );
				// Montant attribué par le comité ou pour l'APRE forfaitaire
				$montantaverser = Set::classicExtract( $this->data, "{$this->alias}.montantaverser" );
				// Nombre d'étatsliquidatifs dans lequel cette apre est déjà passé
				$etatliquidatif_id = Set::classicExtract( $this->data, "{$this->alias}.etatliquidatif_id" );
				$nbrPassagesEffectues = $this->find(
					'count',
					array(
						'conditions' => array(
							"{$this->alias}.apre_id" => $apre_id,
							"{$this->alias}.etatliquidatif_id <>" => $etatliquidatif_id,
						),
						'contain' => false
					)
				);

				$nbrPassagesSubsequents = max( 0, ( $nbrPassagesEffectues - 1 - $nbrPassagesEffectues ) );
				$montantversable = max( 0, ( $montantaverser - $montantdejaverse - $nbrPassagesSubsequents ) );

				// Montant positif
				if( $montantattribue < 0 ) {
					$this->invalidate( 'montantattribue', "Le montant doit être positif" );
				}

				// Montant maximum: montant à verser
				if( $montantattribue > $montantversable ) {
					$this->invalidate( 'montantattribue', "Montant trop élevé (max: {$montantversable})" );
				}
			}

			return $return;
		}
	}
?>
