<?php
	class Dossierep extends AppModel
	{
		public $name = 'Dossierep';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
			'Formattable',
			'Enumerable' => array(
				'fields' => array(
					'etapedossierep',
					'themeep',
				)
			)
		);

		public $belongsTo = array(
			/*'Commissionep' => array(
				'className' => 'Commissionep',
				'foreignKey' => 'commissionep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),*/
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasOne = array(
			// Thèmes 66
			'Saisinebilanparcoursep66' => array(
				'className' => 'Saisinebilanparcoursep66',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Saisinepdoep66' => array(
				'className' => 'Saisinepdoep66',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Defautinsertionep66' => array(
				'className' => 'Defautinsertionep66',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Nonorientationproep66' => array(
				'className' => 'Nonorientationproep66',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			// Thèmes 93
			'Reorientationep93' => array(
				'className' => 'Reorientationep93',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Nonrespectsanctionep93' => array(
				'className' => 'Nonrespectsanctionep93',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Nonorientationproep93' => array(
				'className' => 'Nonorientationproep93',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Signalementep93' => array(
				'className' => 'Signalementep93',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Contratcomplexeep93' => array(
				'className' => 'Contratcomplexeep93',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			// Thèmes 58
			'Nonorientationproep58' => array(
				'className' => 'Nonorientationproep58',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Regressionorientationep58' => array(
				'className' => 'Regressionorientationep58',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Sanctionep58' => array(
				'className' => 'Sanctionep58',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Sanctionrendezvousep58' => array(
				'className' => 'Sanctionrendezvousep58',
				'foreignKey' => 'dossierep_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
		);

		public $hasMany = array(
			'Passagecommissionep' => array(
				'className' => 'Passagecommissionep',
				'foreignKey' => 'dossierep_id',
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

		/*public $hasAndBelongsToMany = array(
			'Commissionep' => array(
				'className' => 'Commissionep',
				'joinTable' => 'passagescommissionseps',
				'foreignKey' => 'dossierep_id',
				'associationForeignKey' => 'commissionep_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'Passagecommissionep'
			)
		);*/

		/**
		* Retourne la liste des thèmes traités par le CG suivant la valeur
		* de la configuration Cg.departement
		*/

		public function themesCg() {
			$return = array();
			$enums = $this->enums();
			$ereg = '/eps'.Configure::read( 'Cg.departement' ).'$/';

			foreach( $enums['Dossierep']['themeep'] as $key => $value ) {
				if( preg_match( $ereg, $key ) ) {
					$return[$key] = $value;
				}
			}

			return $return;
		}

		/**
		*
		*/

		public function themeTraite( $id ) {
			$dossierep = $this->find(
				'first',
				array(
					'conditions' => array(
						"{$this->alias}.{$this->primaryKey}" => $id
					),
					'contain' => array(
						'Passagecommissionep' => array(
							'Commissionep' => array(
								'Ep' => array(
									'Regroupementep'
								)
							)
						)
					)
				)
			);

			$themes = $this->Passagecommissionep->Commissionep->Ep->Regroupementep->themes();
			$themesTraites = array();

			foreach( $themes as $key => $theme ) {
				if( Inflector::tableize( $theme ) == $dossierep['Dossierep']['themeep'] && in_array( $dossierep['Passagecommissionep'][0]['Commissionep']['Ep']['Regroupementep'][$theme], array( 'decisionep', 'decisioncg' ) ) ) {
					$themesTraites[$theme] = $dossierep['Passagecommissionep'][0]['Commissionep']['Ep']['Regroupementep'][$theme];
				}
			}
			return $themesTraites;
		}

		/**
		*
		*/

		public function prepareFormDataUnique( $dossierep_id, $dossier, $niveauDecision ) {
			$data = array();

			foreach( $this->themeTraite( $dossierep_id ) as $theme => $niveauDecision ) {
				$model = Inflector::classify( $theme );

				$data = Set::merge(
					$data,
					$this->{$model}->prepareFormDataUnique(
						$dossierep_id,
						$dossier,
						$niveauDecision
					)
				);
			}

			return $data;
		}

		/**
		*
		*/

		public function sauvegardeUnique( $dossierep_id, $data, $niveauDecision ) {
			$success = true;

			foreach( $this->themeTraite( $dossierep_id ) as $theme => $decision ) {
				$model = Inflector::classify( $theme );
				$success = $this->{$model}->saveDecisionUnique( $data, $niveauDecision ) && $success;
			}

			return $success;
		}

		/**
		* Retourne un array de chaînes de caractères indiquant pourquoi on ne
		* peut pas créer de dossier d'EP pour la personne.
		*
		* Les valeurs possibles sont:
		* 	- Personne.id: la personne n'existe pas en base ou n'a pas de prestation RSA
		* 	- Situationdossierrsa.etatdosrsa: le dossier ne se trouve pas dans un état ouvert
		* 	- Prestation.rolepers: la personne n'est ni demandeur ni conjoint RSA
		* 	- Calculdroitrsa.toppersdrodevorsa: la personne n'est pas soumise à droits et devoirs
		*
		* @param integer $personne_id L'id technique de la personne
		* @return array
		* @access public
		*/

		public function erreursCandidatePassage( $personne_id ) {
			$result = $this->Personne->find(
				'first',
				array(
					'fields' => array(
						'Situationdossierrsa.etatdosrsa',
						'Prestation.rolepers',
						'Calculdroitrsa.toppersdrodevorsa'
					),
					'joins' => array(
						array(
							'table'      => 'foyers',
							'alias'      => 'Foyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Foyer.id = Personne.foyer_id'
							)
						),
						array(
							'table'      => 'situationsdossiersrsa',
							'alias'      => 'Situationdossierrsa',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Foyer.dossier_id = Situationdossierrsa.dossier_id',
							)
						),
						array(
							'table'      => 'prestations',
							'alias'      => 'Prestation',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Personne.id = Prestation.personne_id',
								'Prestation.natprest' => 'RSA',
							)
						),
						array(
							'table'      => 'calculsdroitsrsa',
							'alias'      => 'Calculdroitrsa',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Personne.id = Calculdroitrsa.personne_id'
							)
						),
					),
					'conditions' => array(
						'Personne.id' => $personne_id,
					),
					'contain' => false
				)
			);
			$result = Set::flatten( $result );

			$errors = array();
			if( empty( $result ) ) {
				$errors[] = 'Personne.id';
			}
			else {
				if( !in_array( $result['Situationdossierrsa.etatdosrsa'], ClassRegistry::init( 'Situationdossierrsa' )->etatOuvert() ) ) {
					$errors[] = 'Situationdossierrsa.etatdosrsa';
				}
				if( !in_array( $result['Prestation.rolepers'], array( 'DEM', 'CJT' ) ) ) {
					$errors[] = 'Prestation.rolepers';
				}
				if( empty( $result['Calculdroitrsa.toppersdrodevorsa'] ) ) {
					$errors[] = 'Calculdroitrsa.toppersdrodevorsa';
				}
			}

			return $errors;
		}

		/**
		* Récupération des informations propres au dossier devant passer en EP
		* après liaison avec la commission d'EP
		*/

		public function getConvocationBeneficiaireEpPdf( $passagecommissionep_id ) {
			$passagecommission = $this->Passagecommissionep->find(
				'first',
				array(
					'conditions' => array( 'Passagecommissionep.id' => $passagecommissionep_id ),
					'contain' => array(
						'Dossierep'
					)
				)
			);

			if( empty( $passagecommission ) ) {
				return false;
			}

			$theme = Inflector::classify( $passagecommission['Dossierep']['themeep'] );

			if( empty( $theme ) ) {
				return false;
			}

			$this->Passagecommissionep->updateAll(
				array( 'Passagecommissionep.impressionconvocation' => "'".date( 'Y-m-d' )."'" ),
				array(
					'"Passagecommissionep"."id"' => $passagecommissionep_id,
					'"Passagecommissionep"."impressionconvocation" IS NULL'
				)
			);

			$pdf = $this->{$theme}->getConvocationBeneficiaireEpPdf( $passagecommissionep_id );

			if( empty( $pdf ) ) {
				$this->Passagecommissionep->updateAll(
					array( 'Passagecommissionep.impressionconvocation' => null ),
					array(
						'"Passagecommissionep"."id"' => $passagecommissionep_id,
						'"Passagecommissionep"."impressionconvocation" IS NOT NULL'
					)
				);
			}

			return $pdf;
		}

		/**
		*
		*/

		public function getDecisionPdf( $passagecommissionep_id  ) {
			$passagecommission = $this->Passagecommissionep->find(
				'first',
				array(
					'conditions' => array( 'Passagecommissionep.id' => $passagecommissionep_id ),
					'contain' => array(
						'Dossierep'
					)
				)
			);

			$pdf = false;
			if( !empty( $passagecommission ) ) {
				$theme = Inflector::classify( $passagecommission['Dossierep']['themeep'] );
				$this->Passagecommissionep->updateAll(
					array( 'Passagecommissionep.impressiondecision' => "'".date( 'Y-m-d' )."'" ),
					array(
						'"Passagecommissionep"."id"' => $passagecommissionep_id,
						'"Passagecommissionep"."impressiondecision" IS NULL'
					)
				);
				$pdf = $this->{$theme}->getDecisionPdf( $passagecommissionep_id );
			}

			return $pdf;
		}

		/**
		*
		*/
		public function qdDossiersepsOuverts( $personne_id ) {
			$themes = array_keys( $this->themesCg() );

			return array(
				'conditions' => array(
					'Dossierep.personne_id' => $personne_id,
					'Dossierep.themeep' => $themes,
					'Dossierep.id NOT IN ( '.$this->Passagecommissionep->sq(
						array(
							'alias' => 'passagescommissionseps',
							'fields' => array(
								'passagescommissionseps.dossierep_id'
							),
							'conditions' => array(
								'passagescommissionseps.etatdossierep' => array( 'traite', 'annule' )
							)
						)
					).' )'
				),
				'contain' => false
			);
		}



		/**
		* Vérifie l'intervalle, entre la date du jour et la date de création du dossier EP
		* le dossier d'EP n'apparaîtra que 1 mois et demi après sa création dans la liste des dossiers devant passer en EP
		*/

		public function checkConfigDossierepDelaiavantselection() {
			$delaiavantselection = Configure::read( 'Dossierep.delaiavantselection' );

			if( is_null( $delaiavantselection ) ) {
				return true;
			}

			return $this->_checkPostgresqlIntervals( array( 'Dossierep.delaiavantselection'  ), true );

			/*if( $cg66 ) {
				return 'Oubli de paramétrage: veuillez vérifier que le champ <em>Dossierep.delaiavantselection</em> dans le fichier webrsa.inc est correctement renseigné';
			}*/
			return true;
		}

		public function checkPostgresqlIntervals() {
			$value = Configure::read( 'Dossierep.delaiavantselection' );

			if( is_null( $value ) ) {
				return array();
			}
			else {
				return $this->_checkPostgresqlIntervals(
					array( 'Dossierep.delaiavantselection' )
				);
			}
		}

		/**
		 * Retourne une sous-requête permettant d'obtenir l'id du dossier d'EP de la personne
		 * associé à la commission d'EP la plus récente.
		 *
		 * @param string $personneIdAlias Le champ désignant l'id de la personne
		 * @return string
		 */
		public function sqDernierPassagePersonne( $personneIdAlias = 'Personne.id' ) {
			// Dossierep INNER Passagecommissionep INNER Commissionep ORDER BY Commissionep.dateseance DESC
			return $this->sq(
				array(
					'fields' => array( 'dossierseps.id' ),
					'alias' => 'dossierseps',
					'joins' => array(
						array_words_replace( $this->join( 'Passagecommissionep', array( 'type' => 'INNER' ) ), array( 'Dossierep' => 'dossierseps', 'Passagecommissionep' => 'passagescommissionseps' ) ),
						array_words_replace( $this->Passagecommissionep->join( 'Commissionep', array( 'type' => 'INNER' ) ), array( 'Passagecommissionep' => 'passagescommissionseps', 'Commissionep' => 'commissionseps' ) ),
					),
					'contain' => false,
					'conditions' => array(
						"dossierseps.personne_id = {$personneIdAlias}"
					),
					'order' => array( 'commissionseps.dateseance DESC' ),
					'limit' => 1
				)
			);
		}
	}
?>
