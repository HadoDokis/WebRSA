<?php
	class Criterepdo extends AppModel
	{
		public $name = 'Criterepdo';

		public $useTable = false;
		public $actsAs = array( 'Conditionnable' );

		/**
		*
		*/

		public function listeDossierPDO( $mesCodesInsee, $filtre_zone_geo, $criterespdos, $lockedDossiers ) {
			/// Conditions de base
			$conditions = array();

			
			// On a un filtre par défaut sur l'état du dossier si celui-ci n'est pas renseigné dans le formulaire.
			$Situationdossierrsa = ClassRegistry::init( 'Situationdossierrsa' );			
			$etatdossier = Set::extract( $criterespdos, 'Situationdossierrsa.etatdosrsa' );
			if( !isset( $criterespdos['Situationdossierrsa']['etatdosrsa'] ) || empty( $criterespdos['Situationdossierrsa']['etatdosrsa'] ) ) {
				$criterespdos['Situationdossierrsa']['etatdosrsa']  = $Situationdossierrsa->etatAttente();
			}

			/// Filtre zone géographique
			$conditions[] = $this->conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsAdresse( $conditions, $criterespdos, $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $criterespdos );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $criterespdos );

			/// Dossiers lockés
			if( !empty( $lockedDossiers ) ) {
				$conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
			}

			/// Critères

			$decisionpdo = Set::extract( $criterespdos, 'Decisionpropopdo.decisionpdo_id' );
			$motifpdo = Set::extract( $criterespdos, 'Propopdo.motifpdo' );
			$originepdo = Set::extract( $criterespdos, 'Propopdo.originepdo_id' );
			$etatdossierpdo = Set::extract( $criterespdos, 'Propopdo.etatdossierpdo' );


			/// Critères sur les PDOs - date de decisonde la PDO
			if( isset( $criterespdos['Decisionpropopdo']['datedecisionpdo'] ) && !empty( $criterespdos['Decisionpropopdo']['datedecisionpdo'] ) ) {
				$valid_from = ( valid_int( $criterespdos['Decisionpropopdo']['datedecisionpdo_from']['year'] ) && valid_int( $criterespdos['Decisionpropopdo']['datedecisionpdo_from']['month'] ) && valid_int( $criterespdos['Decisionpropopdo']['datedecisionpdo_from']['day'] ) );
				$valid_to = ( valid_int( $criterespdos['Decisionpropopdo']['datedecisionpdo_to']['year'] ) && valid_int( $criterespdos['Decisionpropopdo']['datedecisionpdo_to']['month'] ) && valid_int( $criterespdos['Decisionpropopdo']['datedecisionpdo_to']['day'] ) );

				if( $valid_from && $valid_to ) {
					$conditions[] = 'Decisionpropopdo.datedecisionpdo BETWEEN \''.implode( '-', array( $criterespdos['Decisionpropopdo']['datedecisionpdo_from']['year'], $criterespdos['Decisionpropopdo']['datedecisionpdo_from']['month'], $criterespdos['Decisionpropopdo']['datedecisionpdo_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterespdos['Decisionpropopdo']['datedecisionpdo_to']['year'], $criterespdos['Decisionpropopdo']['datedecisionpdo_to']['month'], $criterespdos['Decisionpropopdo']['datedecisionpdo_to']['day'] ) ).'\'';
				}
			}

			/// Critères sur les PDOs - date de reception de la PDO
			if( isset( $criterespdos['Propopdo']['datereceptionpdo'] ) && !empty( $criterespdos['Propopdo']['datereceptionpdo'] ) ) {
				$valid_from = ( valid_int( $criterespdos['Propopdo']['datereceptionpdo_from']['year'] ) && valid_int( $criterespdos['Propopdo']['datereceptionpdo_from']['month'] ) && valid_int( $criterespdos['Propopdo']['datereceptionpdo_from']['day'] ) );
				$valid_to = ( valid_int( $criterespdos['Propopdo']['datereceptionpdo_to']['year'] ) && valid_int( $criterespdos['Propopdo']['datereceptionpdo_to']['month'] ) && valid_int( $criterespdos['Propopdo']['datereceptionpdo_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Propopdo.datereceptionpdo BETWEEN \''.implode( '-', array( $criterespdos['Propopdo']['datereceptionpdo_from']['year'], $criterespdos['Propopdo']['datereceptionpdo_from']['month'], $criterespdos['Propopdo']['datereceptionpdo_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterespdos['Propopdo']['datereceptionpdo_to']['year'], $criterespdos['Propopdo']['datereceptionpdo_to']['month'], $criterespdos['Propopdo']['datereceptionpdo_to']['day'] ) ).'\'';
				}
			}


			// Décision de la PDO
			if( !empty( $decisionpdo ) ) {
				$conditions[] = 'Decisionpropopdo.decisionpdo_id = \''.Sanitize::clean( $decisionpdo ).'\'';
			}


			// Etat du dossier PDO
			if( !empty( $etatdossierpdo ) ) {
				$conditions[] = 'Propopdo.etatdossierpdo = \''.Sanitize::clean( $etatdossierpdo ).'\'';
			}


			// Motif de la PDO
			if( !empty( $motifpdo ) ) {
				$conditions[] = 'Propopdo.motifpdo = \''.Sanitize::clean( $motifpdo ).'\'';
			}

			// Origine de la PDO
			if( !empty( $originepdo ) ) {
				$conditions[] = 'Propopdo.originepdo_id = \''.Sanitize::clean( $originepdo ).'\'';
			}


			/// Requête
			$this->Dossier = ClassRegistry::init( 'Dossier' );

			$query = array(
				'fields' => array(
					'"Personne"."id"',
					'"Personne"."nom"',
					'"Personne"."prenom"',
					'"Personne"."dtnai"',
					'"Personne"."nir"',
					'"Personne"."qual"',
					'"Dossier"."id"',
					'"Dossier"."numdemrsa"',
					'"Dossier"."dtdemrsa"',
					'"Dossier"."matricule"',
					'"Personne"."nomcomnai"',
					'"Adresse"."locaadr"',
					'"Adresse"."codepos"',
					'"Adresse"."numcomptt"',
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
						'conditions' => array( 'Foyer.id = Personne.foyer_id' )
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Foyer.id = Adressefoyer.foyer_id',
							'Adressefoyer.id IN (
								'.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id').'
							)'
						)
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					),
					array(
						'table'      => 'dossiers',
						'alias'      => 'Dossier',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
					),
					array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Situationdossierrsa.dossier_id = Dossier.id'
						)
					),
					array(
						'table'      => 'prestations',
						'alias'      => 'Prestation',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Prestation.personne_id',
							'Prestation.natprest = \'RSA\'',
							'( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )',
						)
					),
				),
				'limit' => 10,
				'conditions' => $conditions
			);

			return $query;
		}

		/**
		*
		*/

		public function search( $mesCodesInsee, $filtre_zone_geo, $criterespdos, $lockedDossiers ) {
			/// Conditions de base
			$conditions = array();

			/// Filtre zone géographique
			$conditions[] = $this->conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsAdresse( $conditions, $criterespdos, $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $criterespdos );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $criterespdos );

			/// Dossiers lockés
			if( !empty( $lockedDossiers ) ) {
				$conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
			}

			/// Critères

			$decisionpdo = Set::extract( $criterespdos, 'Decisionpropopdo.decisionpdo_id' );
			$motifpdo = Set::extract( $criterespdos, 'Propopdo.motifpdo' );
			$originepdo = Set::extract( $criterespdos, 'Propopdo.originepdo_id' );
			$gestionnaire = Set::extract( $criterespdos, 'Propopdo.user_id' );
			$etatdossierpdo = Set::extract( $criterespdos, 'Propopdo.etatdossierpdo' );

			$etatdossierpdo = Set::extract( $criterespdos, 'Propopdo.etatdossierpdo' );

			/// Critères sur les PDOs - date de decisonde la PDO
			if( isset( $criterespdos['Decisionpropopdo']['datedecisionpdo'] ) && !empty( $criterespdos['Decisionpropopdo']['datedecisionpdo'] ) ) {
				$valid_from = ( valid_int( $criterespdos['Decisionpropopdo']['datedecisionpdo_from']['year'] ) && valid_int( $criterespdos['Decisionpropopdo']['datedecisionpdo_from']['month'] ) && valid_int( $criterespdos['Decisionpropopdo']['datedecisionpdo_from']['day'] ) );
				$valid_to = ( valid_int( $criterespdos['Decisionpropopdo']['datedecisionpdo_to']['year'] ) && valid_int( $criterespdos['Decisionpropopdo']['datedecisionpdo_to']['month'] ) && valid_int( $criterespdos['Decisionpropopdo']['datedecisionpdo_to']['day'] ) );

				if( $valid_from && $valid_to ) {
					$conditions[] = 'Decisionpropopdo.datedecisionpdo BETWEEN \''.implode( '-', array( $criterespdos['Decisionpropopdo']['datedecisionpdo_from']['year'], $criterespdos['Decisionpropopdo']['datedecisionpdo_from']['month'], $criterespdos['Decisionpropopdo']['datedecisionpdo_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterespdos['Decisionpropopdo']['datedecisionpdo_to']['year'], $criterespdos['Decisionpropopdo']['datedecisionpdo_to']['month'], $criterespdos['Decisionpropopdo']['datedecisionpdo_to']['day'] ) ).'\'';
				}
			}

			/// Critères sur les PDOs - date de reception de la PDO
			if( isset( $criterespdos['Propopdo']['datereceptionpdo'] ) && !empty( $criterespdos['Propopdo']['datereceptionpdo'] ) ) {
				$valid_from = ( valid_int( $criterespdos['Propopdo']['datereceptionpdo_from']['year'] ) && valid_int( $criterespdos['Propopdo']['datereceptionpdo_from']['month'] ) && valid_int( $criterespdos['Propopdo']['datereceptionpdo_from']['day'] ) );
				$valid_to = ( valid_int( $criterespdos['Propopdo']['datereceptionpdo_to']['year'] ) && valid_int( $criterespdos['Propopdo']['datereceptionpdo_to']['month'] ) && valid_int( $criterespdos['Propopdo']['datereceptionpdo_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Propopdo.datereceptionpdo BETWEEN \''.implode( '-', array( $criterespdos['Propopdo']['datereceptionpdo_from']['year'], $criterespdos['Propopdo']['datereceptionpdo_from']['month'], $criterespdos['Propopdo']['datereceptionpdo_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterespdos['Propopdo']['datereceptionpdo_to']['year'], $criterespdos['Propopdo']['datereceptionpdo_to']['month'], $criterespdos['Propopdo']['datereceptionpdo_to']['day'] ) ).'\'';
				}
			}

			// Décision de la PDO
			if( !empty( $decisionpdo ) ) {
				$conditions[] = 'Decisionpropopdo.decisionpdo_id = \''.Sanitize::clean( $decisionpdo ).'\'';
			}


			// Motif de la PDO
			if( !empty( $motifpdo ) ) {
				$conditions[] = 'Propopdo.motifpdo = \''.Sanitize::clean( $motifpdo ).'\'';
			}


			// Etat du dossier PDO
			if( !empty( $etatdossierpdo ) ) {
				$conditions[] = 'Propopdo.etatdossierpdo = \''.Sanitize::clean( $etatdossierpdo ).'\'';
			}

			// Origine de la PDO
			if( !empty( $originepdo ) ) {
				$conditions[] = 'Propopdo.originepdo_id = \''.Sanitize::clean( $originepdo ).'\'';
			}


			// Gestionnaire de la PDO
			if( !empty( $gestionnaire ) ) {
				$conditions[] = 'Propopdo.user_id = \''.Sanitize::clean( $gestionnaire ).'\'';
			}

			// Trouver les PDOs avec un traitement possédant une date d'échéance
			if( isset( $criterespdos['Propopdo']['traitementencours'] ) && $criterespdos['Propopdo']['traitementencours'] ) {
				$conditions[] = array(
					'Propopdo.id IN ( '.ClassRegistry::init( 'Traitementpdo' )->sq(
						array(
							'alias' => 'traitementspdos',
							'fields' => array( 'traitementspdos.propopdo_id' ),
							'conditions' => array(
								'traitementspdos.propopdo_id = Propopdo.id',
								'traitementspdos.dateecheance IS NOT NULL'
							)
						)
					).' )',
				);
			}

			/// Requête

			$query = array(
				'fields' => array(
					'"Propopdo"."id"',
					'"Propopdo"."personne_id"',
					'"Decisionpropopdo"."decisionpdo_id"',
					'"Propopdo"."datereceptionpdo"',
					'"Decisionpropopdo"."datedecisionpdo"',
					'"Propopdo"."motifpdo"',
					'"Propopdo"."etatdossierpdo"',
					'"Propopdo"."originepdo_id"',
					'"Propopdo"."user_id"',
					'"Dossier"."id"',
					'"Dossier"."numdemrsa"',
					'"Dossier"."dtdemrsa"',
					'"Dossier"."matricule"',
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
					'"Prestation"."rolepers"',
					'"Decisionpdo"."libelle"'
				),
				'recursive' => -1,
				'joins' => array(
					array(
						'table'      => 'decisionspropospdos',
						'alias'      => 'Decisionpropopdo',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Decisionpropopdo.propopdo_id = Propopdo.id' )
					),
					array(
						'table'      => 'decisionspdos',
						'alias'      => 'Decisionpdo',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Decisionpdo.id = Decisionpropopdo.decisionpdo_id' )
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Propopdo.personne_id = Personne.id' ),
					),
					array(
						'table'      => 'prestations',
						'alias'      => 'Prestation',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Prestation.personne_id',
							'Prestation.natprest = \'RSA\'',
							'( Prestation.rolepers = \'DEM\' OR Prestation.rolepers = \'CJT\' )',
						)
					),
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
					),
					array(
						'table'      => 'adressesfoyers',
						'alias'      => 'Adressefoyer',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Foyer.id = Adressefoyer.foyer_id',
							'Adressefoyer.id IN (
								'.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id').'
							)'
						)
					),
					array(
						'table'      => 'adresses',
						'alias'      => 'Adresse',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
					),
					array(
						'table'      => 'dossiers',
						'alias'      => 'Dossier',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.dossier_id = Dossier.id' )
					)
				),
				'limit' => 10,
				'conditions' => $conditions
			);

			return $query;
		}
	}
?>