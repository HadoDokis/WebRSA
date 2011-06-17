<?php
	class Dossier extends AppModel
	{
		public $name = 'Dossier';

		public $actsAs = array( 'Conditionnable' );

		public $validate = array(
			'numdemrsa' => array(
				array(
					'rule' => 'isUnique',
					'message' => 'Cette valeur est déjà utilisée'
				),
				array(
					'rule' => 'alphaNumeric',
					'message' => 'Veuillez n\'utiliser que des lettres et des chiffres'
				),
				array(
					'rule' => array( 'between', 11, 11 ),
					'message' => 'Le n° de demande est composé de 11 caractères'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'dtdemrsa' => array(
				array(
					'rule' => 'date',
					'message' => 'Veuillez vérifier le format de la date.'
				),
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'matricule' => array(
				/*array(
					'rule' => 'isUnique',
					'message' => 'Ce numéro CAF est déjà utilisé',
					'allowEmpty' => true
				),*/
				/*array(
					'rule' => array( 'between', 15, 15 ),
					'message' => 'Le numéro CAF est composé de 15 chiffres',
					'allowEmpty' => true
				),
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.',
					'allowEmpty' => true
				)*/
			)
		);

		public $belongsTo = array(
			/*'Detaildroitrsa' => array(
				'className' => 'Detaildroitrsa',
				'foreignKey' => 'detaildroitrsa_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),*/
			/*'Avispcgdroitrsa' => array(
				'className' => 'Avispcgdroitrsa',
				'foreignKey' => 'avispcgdroitrsa_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),*/
			/*'Organisme' => array(
				'className' => 'Organisme',
				'foreignKey' => 'organisme_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)*/
		);

		public $hasOne = array(
			'Avispcgdroitrsa' => array(
				'className' => 'Avispcgdroitrsa',
				'foreignKey' => 'dossier_id',
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
			'Detaildroitrsa' => array(
				'className' => 'Detaildroitrsa',
				'foreignKey' => 'dossier_id',
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
			'Foyer' => array(
				'className' => 'Foyer',
				'foreignKey' => 'dossier_id',
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
			'Jeton' => array(
				'className' => 'Jeton',
				'foreignKey' => 'dossier_id',
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
			'Situationdossierrsa' => array(
				'className' => 'Situationdossierrsa',
				'foreignKey' => 'dossier_id',
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
			'Infofinanciere' => array(
				'className' => 'Infofinanciere',
				'foreignKey' => 'dossier_id',
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
			'Suiviinstruction' => array(
				'className' => 'Suiviinstruction',
				'foreignKey' => 'dossier_id',
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

		/**
		*
		*/

		public function beforeSave() {
			// Champs déduits
			if( !empty( $this->data['Dossier']['numdemrsa'] ) ) {
				$this->data['Dossier']['numdemrsa'] = strtoupper( $this->data['Dossier']['numdemrsa'] );
			}

			return parent::beforeSave();
		}

		/**
		*
		*/

		public function findByZones( $zonesGeographiques = array(), $filtre_zone_geo = true ) {
			$this->Foyer->unbindModelAll();

			$this->Foyer->bindModel(
				array(
					'hasOne'=>array(
						'Adressefoyer' => array(
							'foreignKey'    => false,
							'type'          => 'LEFT',
							'conditions'    => array(
								'"Adressefoyer"."foyer_id" = "Foyer"."id"',
								'"Adressefoyer"."rgadr" = \'01\''
							)
						),
						'Adresse' => array(
							'foreignKey'    => false,
							'type'          => 'LEFT',
							'conditions'    => array(
								'"Adressefoyer"."adresse_id" = "Adresse"."id"'
							)
						)
					)
				)
			);

			if( $filtre_zone_geo ) {
				$params = array (
					'conditions' => array(
						'Adresse.numcomptt' => array_values( $zonesGeographiques )
					)
				);
			}
			else {
				$params = array();
			}

			$foyers = $this->Foyer->find( 'all', $params );

			$return = Set::extract( $foyers, '{n}.Foyer.dossier_id' );
			return ( !empty( $return ) ? $return : null );
		}


		/**
		*
		*/

		public function search( $mesCodesInsee, $filtre_zone_geo, $params ) {
			$conditions = array();

			$conditions = $this->conditionsAdresse( $conditions, $params, $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $params );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $params );


			/// Critères sur la nature de la prestation - natpf
			if( isset( $params['Detailcalculdroitrsa']['natpf'] ) && !empty( $params['Detailcalculdroitrsa']['natpf'] ) ) {
				$conditions[] = "Dossier.id IN ( SELECT detailsdroitsrsa.dossier_id FROM detailsdroitsrsa INNER JOIN detailscalculsdroitsrsa ON detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id WHERE detailscalculsdroitsrsa.natpf ILIKE '%".Sanitize::paranoid( $params['Detailcalculdroitrsa']['natpf'] )."%' )";
			}

			/// Critères sur Soumis à Droit et Devoir - toppersdrodevorsa
			if( isset( $params['Calculdroitrsa']['toppersdrodevorsa'] ) && is_numeric( $params['Calculdroitrsa']['toppersdrodevorsa'] ) ) {
				$conditions[] = array('Calculdroitrsa.toppersdrodevorsa'=>$params['Calculdroitrsa']['toppersdrodevorsa']);
			}

			/// Critères sur le dossier - date de demande
			if( isset( $params['Dossier']['dtdemrsa'] ) && !empty( $params['Dossier']['dtdemrsa'] ) ) {
				$valid_from = ( valid_int( $params['Dossier']['dtdemrsa_from']['year'] ) && valid_int( $params['Dossier']['dtdemrsa_from']['month'] ) && valid_int( $params['Dossier']['dtdemrsa_from']['day'] ) );
				$valid_to = ( valid_int( $params['Dossier']['dtdemrsa_to']['year'] ) && valid_int( $params['Dossier']['dtdemrsa_to']['month'] ) && valid_int( $params['Dossier']['dtdemrsa_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Dossier.dtdemrsa BETWEEN \''.implode( '-', array( $params['Dossier']['dtdemrsa_from']['year'], $params['Dossier']['dtdemrsa_from']['month'], $params['Dossier']['dtdemrsa_from']['day'] ) ).'\' AND \''.implode( '-', array( $params['Dossier']['dtdemrsa_to']['year'], $params['Dossier']['dtdemrsa_to']['month'], $params['Dossier']['dtdemrsa_to']['day'] ) ).'\'';
				}
			}


			/// FIXME: Critères sur le dossier - service instructeur
			if( isset( $params['Serviceinstructeur']['id'] ) && !empty( $params['Serviceinstructeur']['id'] ) ) {
				$conditions[] = "Dossier.id IN ( SELECT suivisinstruction.dossier_id FROM suivisinstruction INNER JOIN servicesinstructeurs ON suivisinstruction.numdepins = servicesinstructeurs.numdepins AND suivisinstruction.typeserins = servicesinstructeurs.typeserins AND suivisinstruction.numcomins = servicesinstructeurs.numcomins AND suivisinstruction.numagrins = servicesinstructeurs.numagrins WHERE servicesinstructeurs.id = '".Sanitize::paranoid( $params['Serviceinstructeur']['id'] )."' )";
				//$conditions[] = 'Serviceinstructeur.id = \''.Sanitize::paranoid( $params['Serviceinstructeur']['id'] ).'\'';
			}

			///FIXME : tranche d'âge pour la recherche mais non finalisé
			$trancheAge = Set::extract( $params, 'Personne.trancheAge' );
			if( $trancheAge == 0 ) {
				$ageMin = 0;
				$ageMax = 25;
			}
			else if( $trancheAge == 1 ) {
				$ageMin = 25;
				$ageMax = 30;
			}
			else if( $trancheAge == 2 ) {
				$ageMin = 31;
				$ageMax = 55;
			}
			else if( $trancheAge == 3 ) {
				$ageMin = 56;
				$ageMax = 65;
			}
			else if( $trancheAge == 4 ) {
				$ageMin = 66;
				$ageMax = 120;
			}

			if( is_numeric( $trancheAge )  ) {
				$conditions[] = '( EXTRACT ( YEAR FROM AGE( Personne.dtnai ) ) ) BETWEEN '.$ageMin.' AND '.$ageMax;
			}


			$hasContrat  = Set::extract( $params, 'Personne.hascontrat' );
			/// Statut de présence contrat engagement reciproque
			if( !empty( $hasContrat ) && in_array( $hasContrat, array( 'O', 'N' ) ) ) {
				if( $hasContrat == 'O' ) {
					$conditions[] = '( SELECT COUNT(contratsinsertion.id) FROM contratsinsertion WHERE contratsinsertion.personne_id = "Personne"."id" ) > 0';
				}
				else {
					$conditions[] = '( SELECT COUNT(contratsinsertion.id) FROM contratsinsertion WHERE contratsinsertion.personne_id = "Personne"."id" ) = 0';
				}
			}

			// Personne ne possédant pas d'orientation, ne possédant aucune entrée dans la table orientsstructs
			if( isset( $params['Orientstruct']['sansorientation'] ) && $params['Orientstruct']['sansorientation'] ) {
				$conditions[] = '( SELECT COUNT(orientsstructs.id) FROM orientsstructs WHERE orientsstructs.personne_id = "Personne"."id" ) = 0';
			}


			/**
			*	FIXME: pour les tests de performance
			*	'Z' => 'Non défini',
			*	'0'  => 'Nouvelle demande en attente de décision CG pour ouverture du droit',
			*	'1'  => 'Droit refusé',
			*	'2'  => 'Droit ouvert et versable',
			*	'3'  => 'Droit ouvert et suspendu (le montant du droit est calculable, mais l\'existence du droit est remis en cause)',
			*	'4'  => 'Droit ouvert mais versement suspendu (le montant du droit n\'est pas calculable)',
			*	'5'  => 'Droit clos',
			*	'6'  => 'Droit clos sur mois antérieur ayant eu des créances transferées ou une régularisation dans le mois de référence pour une période antérieure.'
			*/
// 			$conditions['Situationdossierrsa.etatdosrsa'] = array( 2, 3, 4 );

			$query = array(
				'fields' => array(
					'"Dossier"."id"',
					'"Dossier"."numdemrsa"',
					'"Dossier"."dtdemrsa"',
					'"Dossier"."matricule"',
					'"Personne"."nir"',
					'"Personne"."qual"',
					'"Personne"."nom"',
					'"Personne"."prenom"',
					'"Personne"."prenom2"',
					'"Personne"."prenom3"',
					'"Personne"."dtnai"',
					'"Personne"."idassedic"',
					'"Personne"."nomcomnai"',
					'"Adresse"."locaadr"',
					'"Adresse"."numcomptt"',
					'"Situationdossierrsa"."etatdosrsa"',
//                    '"Detailcalculdroitrsa"."natpf"',///FIXME
//                     '"Serviceinstructeur"."id"',
//                     '"Serviceinstructeur"."lib_service"',
//                     '"Orientstruct"."typeorient_id"',
				),
				'recursive' => -1,
				'joins' => array(
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
					),
					array(
						'table'      => 'prestations',
						'alias'      => 'Prestation',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Prestation.personne_id',
							'Prestation.natprest = \'RSA\'',
							'Prestation.rolepers' => array( 'DEM', 'CJT' )
						)
					),
					array(
						'table'      => 'calculsdroitsrsa',
						'alias'      => 'Calculdroitrsa',
//                         'type'       => 'INNER',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Calculdroitrsa.personne_id'
						)
					),
					array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'INNER', // FIXME
						'foreignKey' => false,
						'conditions' => array( 'Situationdossierrsa.dossier_id = Dossier.id' )
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					),
					array(
						'table'      => 'detailsdroitsrsa',
						'alias'      => 'Detaildroitrsa',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Detaildroitrsa.dossier_id = Dossier.id' )
					),
				),
				'limit' => 10,
				'conditions' => $conditions
			);
			return $query;
		}
	}
?>
