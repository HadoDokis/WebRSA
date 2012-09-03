<?php
	App::import( 'Sanitize' );

	class Cohorteindu extends AppModel
	{
		public $name = 'Cohorteindu';

		public $useTable = false;
		
		public $actsAs = array( 'Conditionnable' );

		public $validate = array(
			'compare' => array(
				array(
					'rule' => array( 'allEmpty', 'mtmoucompta' ),
					'message' => 'Si opérateurs est renseigné, nombre de jours depuis l\'orientation doit l\'être aussi'
				)
			),
			'mtmoucompta' => array(
				array(
					'rule' => array( 'allEmpty', 'compare' ),
					'message' => 'Si le montant est saisi, opérateurs doit l\'être aussi'
				),
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez entrer un chiffre valide',
					'allowEmpty' => true
				)
			)
		);

		/**
		 *
		 * @param array $options
		 * @return boolean
		 */
		public function beforeValidate( $options = array() ) {
			$_compare = Set::extract( $this->data, 'Cohorteindu.compare' );
			$_mtmoucompta = Set::extract( $this->data, 'Cohorteindu.mtmoucompta' );

			if( empty( $_compare ) != empty( $_mtmoucompta )  ) {
				$this->data['Cohorteindu']['compare'] = $_compare;
				$this->data['Cohorteindu']['mtmoucompta'] = $_mtmoucompta;
			}

			return parent::beforeValidate( $options );
		}

		/**
		*
		*/

		public function search( $mesCodesInsee, $filtre_zone_geo, $criteresindu, $lockedDossiers ) {
			/// Conditions de base
			$conditions = array();

			/// Filtre zone géographique
			$conditions[] = $this->conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee );
			
			$conditions = $this->conditionsAdresse( $conditions, $criteresindu, $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $criteresindu );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $criteresindu );

			/// Dossiers lockés
			if( !empty( $lockedDossiers ) ) {
				$conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
			}

			/// Critères
			$natpfcre = Set::extract( $criteresindu, 'Cohorteindu.natpfcre' );
			$typeparte = Set::extract( $criteresindu, 'Cohorteindu.typeparte' );
			$structurereferente_id = Set::extract( $criteresindu, 'Cohorteindu.structurereferente_id' );
			$mtmoucompta = Set::extract( $criteresindu, 'Cohorteindu.mtmoucompta' );
			$compare = Set::extract( $criteresindu, 'Cohorteindu.compare' );


			// Suivi
			if( !empty( $typeparte ) ) {
				$conditions[] = 'Dossier.typeparte = \''.Sanitize::clean( $typeparte ).'\'';
			}

			// Structure référente
			if( !empty( $structurereferente_id ) ) {
				$conditions[] = 'Structurereferente.id = \''.$structurereferente_id.'\'';
			}


			/// Requête
			$Situationdossierrsa = ClassRegistry::init( 'Situationdossierrsa' );
			$etatdossier = Set::extract( $criteresindu, 'Situationdossierrsa.etatdosrsa' );
			if( isset( $criteresindu['Situationdossierrsa']['etatdosrsa'] ) && !empty( $criteresindu['Situationdossierrsa']['etatdosrsa'] ) ) {
				$conditions[] = '( Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $etatdossier ).'\' ) )';
			}
			else {
				$conditions[] = '( Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatOuvert() ).'\' ) )';
			}


			$this->Dossier = ClassRegistry::init( 'Dossier' );

			// FIXME -> qu'a-t'on dans la base à un instant t ?
			$date_start = date( 'Y-m-d', strtotime( 'previous month', strtotime( date( 'Y-m-01' ) ) ) );
			$date_end = date( 'Y-m-d', strtotime( 'next month', strtotime( date( 'Y-m-d', strtotime( $date_start ) ) ) ) - 1 );

			$query = array(
				'fields' => array(
					'"Dossier"."id"',
					'"Dossier"."numdemrsa"',
					'"Dossier"."matricule"',
					'"Dossier"."typeparte"',
					'"Personne"."id"',
					'"Personne"."nom"',
					'"Personne"."prenom"',
					'"Personne"."dtnai"',
					'"Personne"."nir"',
					'"Personne"."qual"',
					'"Personne"."nomcomnai"',
					'"Adresse"."locaadr"',
					'"Adresse"."codepos"',
					'"Adresse"."numcomptt"',
					'"Situationdossierrsa"."id"',
					'"Situationdossierrsa"."etatdosrsa"',
					'"Prestation"."rolepers"'
				),
				'recursive' => -1,
				'joins' => array(
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.dossier_id = Dossier.id' )
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
							'( Prestation.rolepers = \'DEM\' )',
						)
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					),
					array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
// 						'conditions' => array( 'Situationdossierrsa.dossier_id = Dossier.id AND ( Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatOuvert() ).'\' ) )' )
						'conditions' => array( 'Situationdossierrsa.dossier_id = Dossier.id' )
					),
					array(
						'table'      => 'detailsdroitsrsa',
						'alias'      => 'Detaildroitrsa',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Detaildroitrsa.dossier_id = Dossier.id' )
					),
					array(
						'table'      => 'detailscalculsdroitsrsa',
						'alias'      => 'Detailcalculdroitrsa',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Detailcalculdroitrsa.detaildroitrsa_id = Detaildroitrsa.id' )
					)
				),
				'limit' => 10,
				'conditions' => array()
			);

			$typesAllocation = array( 'IndusConstates', 'IndusTransferesCG', 'RemisesIndus' );
			$conditionsNotNull = array();
			$conditionsComparator = array();
			$conditionsNat = array();
			$coalesce = array();

			foreach( $typesAllocation as $type ) {
				$meu  = Inflector::singularize( Inflector::tableize( $type ) );
				$query['fields'][] = '"'.$type.'"."mtmoucompta" AS mt_'.$meu;

				$join = array(
					'table'      => 'infosfinancieres',
					'alias'      => $type,
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array(
						$type.'.dossier_id = Dossier.id',
						$type.'.type_allocation' => $type
					)
				);
				$query['joins'][] = $join;
				$conditionsNotNull[] = $type.'.mtmoucompta IS NOT NULL';

				$coalesce[] = '"'.$type.'"."moismoucompta"';

				// Montant indu + comparatif vis à vis du montant
				if( !empty( $compare ) && !empty( $mtmoucompta ) ) {
					$conditionsComparator[] = $type.'.mtmoucompta '.$compare.' '.Sanitize::clean( $mtmoucompta );
				}

				// Nature de la prestation de créance
				if( !empty( $natpfcre ) ) {
					$conditionsNat[] = $type.'.natpfcre = \''.Sanitize::clean( $natpfcre ).'\'';
				}
			}
			$query['fields'][] = 'COALESCE( '.implode( ',', $coalesce ).' ) AS "moismoucompta"';
			$conditions[] = '( '.implode( ' OR ', $conditionsNotNull  ).' )';
			if( !empty( $conditionsComparator ) ) {
				$conditions[] = '( '.implode( ' OR ', $conditionsComparator  ).' )';
			}
			if( !empty( $natpfcre ) ) {
				$conditions[] = '( '.implode( ' OR ', $conditionsNat  ).' )';
			}
			$query['conditions'] = Set::merge( $query['conditions'], $conditions );

			$tConditions = array();
			foreach( $coalesce as $item1 ) {
				foreach( $coalesce as $item2 ) {
					if( $item1 != $item2 ) {
						$cmp = strcmp( $item1, $item2 );
						if( $cmp < 0 ) {
							$tConditions[] = '( ( '.$item1.' = '.$item2.' ) OR '.$item1.' IS NULL OR '.$item2.' IS NULL )';
						}
						else {
							$tConditions[] = '( ( '.$item2.' = '.$item1.' ) OR '.$item2.' IS NULL OR '.$item1.' IS NULL )';
						}
					}
				}
			}
			$query['conditions'] = Set::merge( $query['conditions'], '( '.implode( ' OR ', array_unique( $tConditions ) ).' )' );
			$query['conditions'] = Set::merge( $query['conditions'], array( 'COALESCE( '.implode( ',', $coalesce ).' ) IS NOT NULL' ) );

			return $query;
		}
	}
?>