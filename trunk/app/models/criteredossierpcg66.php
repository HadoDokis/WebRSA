<?php
	class Criteredossierpcg66 extends AppModel
	{
		public $name = 'Criteredossierpcg66';

		public $useTable = false;

		public $actsAs = array( 'Conditionnable' );

		/**
		*
		*/

		public function searchDossier( $params ) {
			$conditions = array();

			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $params );

			/// Critères
			$originepdo = Set::extract( $params, 'Dossierpcg66.originepdo_id' );
			$gestionnaire = Set::extract( $params, 'Dossierpcg66.user_id' );
			$etatdossierpcg = Set::extract( $params, 'Dossierpcg66.etatdossierpcg' );

			$datereceptionpdo = Set::extract( $params, 'Dossierpcg66.datereceptionpdo' );
			$datereceptionpdo_to = Set::extract( $params, 'Dossierpcg66.datereceptionpdo_to' );
			$datereceptionpdo_from = Set::extract( $params, 'Dossierpcg66.datereceptionpdo_from' );

			/// Critères sur les PDOs - date de reception de la PDO
			if( !empty( $datereceptionpdo ) ) {
				$datereceptionpdo_from = "{$datereceptionpdo_from['year']}-{$datereceptionpdo_from['month']}-{$datereceptionpdo_from['day']}";
				$datereceptionpdo_to = "{$datereceptionpdo_to['year']}-{$datereceptionpdo_to['month']}-{$datereceptionpdo_to['day']}";
				$conditions[] = "Dossierpcg66.datereceptionpdo BETWEEN '{$datereceptionpdo_from}' AND '{$datereceptionpdo_to}'";
			}

			// Décision de la PDO
			if( !empty( $decisionpdo ) ) {
				$conditions[] = 'Decisiondossierpcg66.decisionpdo_id = \''.Sanitize::clean( $decisionpdo ).'\'';
			}

			//Etat du dossier PCG - multi-choix
			if( isset( $params['Dossierpcg66']['etatdossierpcg'] ) && !empty( $params['Dossierpcg66']['etatdossierpcg'] ) ) {
				$conditions[] = '( Dossierpcg66.etatdossierpcg IN ( \''.implode( '\', \'', $etatdossierpcg ).'\' ) )';
			}
			
			
			// Origine de la PDO
			if( !empty( $originepdo ) ) {
				$conditions[] = 'Dossierpcg66.originepdo_id = \''.Sanitize::clean( $originepdo ).'\'';
			}
			// Gestionnaire de la PDO
			if( !empty( $gestionnaire ) ) {
				$conditions[] = 'Dossierpcg66.user_id = \''.Sanitize::clean( $gestionnaire ).'\'';
			}

			$query = array(
				'fields' => array(
					'DISTINCT Dossierpcg66.id',
					'Dossierpcg66.foyer_id',
					'Dossierpcg66.datereceptionpdo',
					'Dossierpcg66.typepdo_id',
					'Dossierpcg66.etatdossierpcg',
					'Decisiondossierpcg66.datetransmissionop',
					'Dossierpcg66.originepdo_id',
					'Dossierpcg66.user_id',
					'Dossier.id',
					'Dossier.numdemrsa',
					'Dossier.dtdemrsa',
					'Dossier.matricule',
					'Personne.id',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Personne.nir',
					'Personne.qual',
					'Personne.nomcomnai',
					'Adresse.locaadr',
					'Adresse.codepos',
					'Adresse.numcomptt',
					'Situationdossierrsa.etatdosrsa',
					'Dossierpcg66.nbpropositions'
				),
				'recursive' => -1,
				'joins' => array(
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Dossierpcg66.foyer_id = Foyer.id' )
					),
					array(
						'table'      => 'personnespcgs66',
						'alias'      => 'Personnepcg66',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Dossierpcg66.id = Personnepcg66.dossierpcg66_id' ),
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.foyer_id = Foyer.id',
							'Personne.id IN (
								'.ClassRegistry::init( 'Personne' )->sqResponsableDossierUnique('Foyer.id').'
							)'
						)
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
					),
					array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Situationdossierrsa.dossier_id = Dossier.id' )
					),
					array(
						'table'      => 'decisionsdossierspcgs66',
						'alias'      => 'Decisiondossierpcg66',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Decisiondossierpcg66.dossierpcg66_id = Dossierpcg66.id',
							'Decisiondossierpcg66.id IN (
								'.ClassRegistry::init( 'Decisiondossierpcg66' )->sqDatetransmissionOp( 'Dossierpcg66.id' ).'
							)'
						)
					)
				),
				'limit' => 10,
				'conditions' => $conditions
			);

			return $query;
		}

		/**
		*
		*/

		public function searchTraitement( $params ) {
			$conditions = array();

			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $params );

			/// Critères
			$descriptionpdo = Set::extract( $params, 'Traitementpcg66.descriptionpdo_id' );
			$clos = Set::extract( $params, 'Traitementpcg66.clos' );
			$annule = Set::extract( $params, 'Traitementpcg66.annule' );
			$motifpersonnepcg66_id = Set::extract( $params, 'Traitementpcg66.situationpdo_id' );

			$dateecheance = Set::extract( $params, 'Traitementpcg66.dateecheance' );
			$dateecheance_to = Set::extract( $params, 'Traitementpcg66.dateecheance_to' );
			$dateecheance_from = Set::extract( $params, 'Traitementpcg66.dateecheance_from' );
			
			// Gestionnaire du dossier gérant le traitement
			$gestionnaire = Set::extract( $params, 'Dossierpcg66.user_id' );

			/// Critères sur les PDOs - date de reception de la PDO
			if( !empty( $dateecheance ) ) {
				$dateecheance_from = "{$dateecheance_from['year']}-{$dateecheance_from['month']}-{$dateecheance_from['day']}";
				$dateecheance_to = "{$dateecheance_to['year']}-{$dateecheance_to['month']}-{$dateecheance_to['day']}";
				$conditions[] = "Traitementpcg66.dateecheance BETWEEN '{$dateecheance_from}' AND '{$dateecheance_to}'";
			}
			
			$daterevision = Set::extract( $params, 'Traitementpcg66.daterevision' );
			$daterevision_to = Set::extract( $params, 'Traitementpcg66.daterevision_to' );
			$daterevision_from = Set::extract( $params, 'Traitementpcg66.daterevision_from' );

			/// Critères sur les PDOs - date de reception de la PDO
			if( !empty( $daterevision ) ) {
				$daterevision_from = "{$daterevision_from['year']}-{$daterevision_from['month']}-{$daterevision_from['day']}";
				$daterevision_to = "{$daterevision_to['year']}-{$daterevision_to['month']}-{$daterevision_to['day']}";
				$conditions[] = "Traitementpcg66.daterevision BETWEEN '{$daterevision_from}' AND '{$daterevision_to}'";
			}

			// Description du traitement
			if( !empty( $descriptionpdo ) ) {
				$conditions[] = 'Traitementpcg66.descriptionpdo_id = \''.Sanitize::clean( $descriptionpdo ).'\'';
			}
			if( !empty( $clos ) ) {
				$conditions[] = 'Traitementpcg66.clos = \''.Sanitize::clean( $clos ).'\'';
			}
			if( !empty( $annule ) ) {
				$conditions[] = 'Traitementpcg66.annule = \''.Sanitize::clean( $annule ).'\'';
			}
			
			// Gestionnaire de la PDO
			if( !empty( $gestionnaire ) ) {
				$conditions[] = 'Dossierpcg66.user_id = \''.Sanitize::clean( $gestionnaire ).'\'';
			}
			
			// Motif concernant la perosnne du dossier
			if( !empty( $motifpersonnepcg66_id ) ) {
				$conditions[] = 'Traitementpcg66.personnepcg66_situationpdo_id IN ( '.
					ClassRegistry::init( 'Personnepcg66Situationpdo' )->sq(
						array(
							'alias' => 'personnespcgs66_situationspdos',
							'fields' => array( 'personnespcgs66_situationspdos.id' ),
							'contain' => false,
							'conditions' => array(
								'personnespcgs66_situationspdos.situationpdo_id' => $motifpersonnepcg66_id
							),
							'joins' => array(
								array(
									'table'      => 'situationspdos',
									'alias'      => 'situationspdos',
									'type'       => 'INNER',
									'foreignKey' => false,
									'conditions' => array( 'personnespcgs66_situationspdos.situationpdo_id = situationspdos.id' ),
								)
							)
						)
					)
				.' )';
			}
			
			$query = array(
				'fields' => array(
					'"Dossierpcg66"."id"',
					'"Dossierpcg66"."foyer_id"',
					'"Dossierpcg66"."datereceptionpdo"',
					'"Dossierpcg66"."originepdo_id"',
					'"Dossierpcg66"."user_id"',
					'"Traitementpcg66"."personnepcg66_id"',
					'"Traitementpcg66"."daterevision"',
					'"Traitementpcg66"."dateecheance"',
					'"Traitementpcg66"."descriptionpdo_id"',
					'"Traitementpcg66"."clos"',
					'"Traitementpcg66"."annule"',
					'"Descriptionpdo"."name"',
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
					'"Situationdossierrsa"."etatdosrsa"'
				),
				'recursive' => -1,
				'joins' => array(
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Dossierpcg66.foyer_id = Foyer.id' )
					),
					array(
						'table'      => 'personnespcgs66',
						'alias'      => 'Personnepcg66',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Dossierpcg66.id = Personnepcg66.dossierpcg66_id' ),
					),
					array(
						'table'      => 'traitementspcgs66',
						'alias'      => 'Traitementpcg66',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personnepcg66.id = Traitementpcg66.personnepcg66_id' ),
					),
					array(
						'table'      => 'descriptionspdos',
						'alias'      => 'Descriptionpdo',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Descriptionpdo.id = Traitementpcg66.descriptionpdo_id' ),
					),
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.id = Personnepcg66.personne_id' )
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
					),
					array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Situationdossierrsa.dossier_id = Dossier.id' )
					)
				),
				'limit' => 10,
				'conditions' => $conditions
			);
			return $query;
		}
	}
?>