<?php
	class Ressource extends AppModel
	{
		var $name = 'Ressource';

		var $validate = array(
			'ddress' => array(
				'rule' => 'date',
				'message' => 'Veuillez entrer une date valide'
			),
			'dfress' => array(
				'rule' => 'date',
				'message' => 'Veuillez entrer une date valide'
			)
		);

		var $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		var $hasMany = array(
			'Ressourcemensuelle' => array(
				'className' => 'Ressourcemensuelle',
				'foreignKey' => 'ressource_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);


		var $hasAndBelongsToMany = array(
			'Ressourcemensuelle' => array(
				'className' => 'Ressourcemensuelle',
				'joinTable' => 'ressources_ressourcesmensuelles',
				'foreignKey' => 'ressource_id',
				'associationForeignKey' => 'ressourcemensuelle_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'RessourceRessourcemensuelle'
			)/*,
			'Detailressourcemensuelle' => array(
				'className' => 'Detailressourcemensuelle',
				'joinTable' => 'detailsressourcesmensuelles_ressourcesmensuelles',
				'foreignKey' => 'ressource_id',
				'associationForeignKey' => 'detailressourcemensuelle_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'DetailressourcemensuelleRessource'
			)*/
		);

		/**
		*
		*/

		public function afterFind( $results, $primary = false ) {
			$return = parent::afterFind( $results, $primary );

			if( !empty( $results ) ) {
				foreach( $results as $key => $result ) {
					if( isset( $result['Ressource'] ) ) {
						if( isset( $result['Ressource']['topressnul'] ) ) {
							$result['Ressource']['topressnotnul'] = !$result['Ressource']['topressnul'];
						}
					}
					$results[$key] = $result;
				}
			}

			return $results;
		}

		/**
		*
		*/

		public function moyenne( $ressource ) {
			$somme = 0;
			$moyenne = 0;

			$montants = Set::extract( $ressource, '/Ressourcemensuelle/Detailressourcemensuelle/mtnatressmen' );
			if( empty( $montants ) ) {
				$montants = Set::extract( $ressource, '/Detailressourcemensuelle/mtnatressmen' );
			}

			if( count( $montants ) > 0 ) {
				foreach( $montants as $montant ) {
					$somme += $montant;
				}
				$moyenne = ( $somme / count( $montants ) );
			}

			return $moyenne;
		}

		/**
		*
		*/

		public function refresh( $personne_id ) {
			$this->unbindModel( array( 'belongsTo' => array( 'Personne' ) ) );

			$ressource  = $this->find(
				'first',
				array(
					'conditions' => array(
						'Ressource.personne_id' => $personne_id
					),
					'order' => 'Ressource.dfress DESC',
					'recursive' => 2
				)
			);

			if( !empty( $ressource ) ) {
				$moyenne = $this->moyenne( $ressource );
				$ressource['Ressource']['topressnotnul'] = ( $moyenne != 0 );
				$ressource['Ressource']['topressnul'] = !$ressource['Ressource']['topressnotnul'];
				$this->create( $ressource );
				$saved = $this->save();

				// INFO: en version2 c'est dans Calculdroitrsa
				$ModelCalculdroitrsa = ClassRegistry::init( 'Calculdroitrsa' );
				$calculdroitrsa = $ModelCalculdroitrsa->findByPersonneId( $personne_id, null, null, -1 );
				$calculdroitrsa['Calculdroitrsa']['personne_id'] = $personne_id;
				$calculdroitrsa['Calculdroitrsa']['mtpersressmenrsa'] = number_format( $moyenne, 2, '.', '' );
				$ModelCalculdroitrsa->create( $calculdroitrsa );
				$saved = $ModelCalculdroitrsa->save() && $saved;

				return $saved;
			}

			return true;
		}

		/**
		*
		*/

		public function beforeSave( $options = array() ) {
			$return = parent::beforeSave( $options );

			$moyenne = $this->moyenne( $this->data );
			$this->data['Ressource']['topressnotnul'] = ( $moyenne != 0 );
			$this->data['Ressource']['topressnul'] = !$this->data['Ressource']['topressnotnul'];

			return $return;
		}

		/**
		*
		*/

		public function afterSave( $created ) {
			$return = parent::afterSave( $created );

			$personne_id = Set::classicExtract( $this->data, 'Ressource.personne_id' );
			$modelCalculdroitrsa = ClassRegistry::init( 'Calculdroitrsa' );

			// Mise à jour de Calculdroitrsa
			$moyenne = $this->moyenne( $this->data );
			$calculdroitrsa = $modelCalculdroitrsa->findByPersonneId( $personne_id, null, null, -1 );

			// FIXME: si $calculdroitrsa est vide ? Ne doit pas arriver
			$calculdroitrsa[$modelCalculdroitrsa->alias]['personne_id'] = $personne_id;
			$calculdroitrsa[$modelCalculdroitrsa->alias]['mtpersressmenrsa'] = number_format( $moyenne, 2, '.', '' );
			$modelCalculdroitrsa->create( $calculdroitrsa );
			$modelCalculdroitrsa->save();

			$thisPersonne = $this->Personne->findById( $personne_id, null, null, -1 );
			$this->Personne->Foyer->refreshSoumisADroitsEtDevoirs( $thisPersonne['Personne']['foyer_id'] );

			return $return;
		}

		/**
		*
		*/

		public function dossierId( $ressource_id ) {
			$this->unbindModelAll();
			$this->bindModel(
				array(
					'hasOne' => array(
						'Personne' => array(
							'foreignKey' => false,
							'conditions' => array( 'Personne.id = Ressource.personne_id' )
						),
						'Foyer' => array(
							'foreignKey' => false,
							'conditions' => array( 'Foyer.id = Personne.foyer_id' )
						)
					)
				)
			);
			$ressource = $this->findById( $ressource_id, null, null, 1 );

			if( !empty( $ressource ) ) {
				return $ressource['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}
	}
?>