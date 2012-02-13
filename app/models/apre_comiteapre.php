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
			)
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

		/**
		* Sous-requête permettant d'obtenir l'id du dernier passage en comité
		* (par-rapport à la date et à l'heure du comité) pour une APRE donnée.
		*
		* @param string $field Le nom du champ contanant l'id de l'APRE
		* @param mixed $conditions Conditions supplémentaire à insérer dans la sous-requête
		* @return string Une sous-requête SQL, suivant le driver utilisé
		*/

		public function sqDernierComiteApre( $field = 'Apre.id', $conditions = array() ) {
			$dbo = $this->getDataSource( $this->useDbConfig );

			$conditions = Set::merge(
				array( "apres_comitesapres.apre_id = {$field}" ),
				(array) $conditions
			);

			return $this->sq(
				array(
					'alias' => 'apres_comitesapres',
					'fields' => array( 'apres_comitesapres.id' ),
					'joins' => array(
						array(
							'table' => $dbo->fullTableName( $this->Comiteapre, true ),
							'alias' => 'comitesapres',
							'type' => 'INNER',
							'conditions' => array(
								'apres_comitesapres.comiteapre_id = comitesapres.id'
							)
						)
					),
					'conditions' => $conditions,
					'order' => array(
						'comitesapres.datecomite DESC',
						'comitesapres.heurecomite DESC'
					),
					'limit' => 1
				)
			);
		}
	}
?>
