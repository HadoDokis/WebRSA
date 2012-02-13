<?php
	App::import( 'Sanitize' );

	class Critere extends AppModel
	{
		public $name = 'Critere';

		public $useTable = false;

		public $actsAs = array( 'Conditionnable' );

		/**
		*
		*/

		public function search( $mesCodesInsee, $filtre_zone_geo, $criteres, $lockedDossiers ) {
			/// Conditions de base
			$conditions = array();

			/// Critere zone géographique
			$conditions[] = $this->conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee );

			/// Dossiers lockés
			if( !empty( $lockedDossiers ) ) {
				$conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
			}

			/// Critères
			$locaadr = Set::extract( $criteres, 'Critere.locaadr' );
			$numcomptt = Set::extract( $criteres, 'Critere.numcomptt' );
			$natpf = Set::extract( $criteres, 'Critere.natpf' );
			$nir = Set::extract( $criteres, 'Critere.nir' );
			$statut_orient = Set::extract( $criteres, 'Critere.statut_orient' );
			$typeorient_id = Set::extract( $criteres, 'Critere.typeorient_id' );
			$structurereferente_id = Set::extract( $criteres, 'Critere.structurereferente_id' );
			$serviceinstructeur_id = Set::extract( $criteres, 'Critere.serviceinstructeur_id' );
			$referent_id = Set::extract( $criteres, 'Critere.referent_id' );
			$dtnai = Set::extract( $criteres, 'Critere.dtnai' );
			$matricule = Set::extract( $criteres, 'Critere.matricule' );
			$identifiantpe = Set::extract( $criteres, 'Critere.identifiantpe' );
			$structureorientante_id = Set::extract( $criteres, 'Critere.structureorientante_id' );
			$referentorientant_id = Set::extract( $criteres, 'Critere.referentorientant_id' );

			/// Critères sur l'orientation - date d'orientation
			if( isset( $criteres['Critere']['date_valid'] ) && !empty( $criteres['Critere']['date_valid'] ) ) {
				$valid_from = ( valid_int( $criteres['Critere']['date_valid_from']['year'] ) && valid_int( $criteres['Critere']['date_valid_from']['month'] ) && valid_int( $criteres['Critere']['date_valid_from']['day'] ) );
				$valid_to = ( valid_int( $criteres['Critere']['date_valid_to']['year'] ) && valid_int( $criteres['Critere']['date_valid_to']['month'] ) && valid_int( $criteres['Critere']['date_valid_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Orientstruct.date_valid BETWEEN \''.implode( '-', array( $criteres['Critere']['date_valid_from']['year'], $criteres['Critere']['date_valid_from']['month'], $criteres['Critere']['date_valid_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteres['Critere']['date_valid_to']['year'], $criteres['Critere']['date_valid_to']['month'], $criteres['Critere']['date_valid_to']['day'] ) ).'\'';
				}
			}

			// Trouver la dernière orientation pour chacune des personnes du jeu de résultats
			if( isset( $criteres['Orientstruct']['derniere'] ) && $criteres['Orientstruct']['derniere'] ) {
				$conditions[] = 'Orientstruct.id IN (
					SELECT
						orientsstructs.id
					FROM
						orientsstructs
						WHERE
							orientsstructs.personne_id = Orientstruct.personne_id
						ORDER BY
							orientsstructs.date_valid DESC,
							orientsstructs.id DESC
						LIMIT 1
				)';
			}

			// Critères sur le dossier - date de demande
			if( isset( $criteres['Critere']['dtdemrsa'] ) && !empty( $criteres['Critere']['dtdemrsa'] ) ) {
				$valid_from = ( valid_int( $criteres['Critere']['dtdemrsa_from']['year'] ) && valid_int( $criteres['Critere']['dtdemrsa_from']['month'] ) && valid_int( $criteres['Critere']['dtdemrsa_from']['day'] ) );
				$valid_to = ( valid_int( $criteres['Critere']['dtdemrsa_to']['year'] ) && valid_int( $criteres['Critere']['dtdemrsa_to']['month'] ) && valid_int( $criteres['Critere']['dtdemrsa_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Dossier.dtdemrsa BETWEEN \''.implode( '-', array( $criteres['Critere']['dtdemrsa_from']['year'], $criteres['Critere']['dtdemrsa_from']['month'], $criteres['Critere']['dtdemrsa_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteres['Critere']['dtdemrsa_to']['year'], $criteres['Critere']['dtdemrsa_to']['month'], $criteres['Critere']['dtdemrsa_to']['day'] ) ).'\'';
				}
			}

			// Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
			$filtersPersonne = array();
			foreach( array( 'nom', 'prenom', 'nomnai', 'nir' ) as $criterePersonne ) {
				if( isset( $criteres['Critere'][$criterePersonne] ) && !empty( $criteres['Critere'][$criterePersonne] ) ) {
					$conditions[] = 'UPPER(Personne.'.$criterePersonne.') LIKE \''.$this->wildcard( strtoupper( replace_accents( $criteres['Critere'][$criterePersonne] ) ) ).'\'';
				}
			}

			// Critères sur une personne du foyer - date de naissance -> FIXME: seulement demandeur pour l'instant
			if( isset( $criteres['Critere']['dtnai'] ) && !empty( $criteres['Critere']['dtnai'] ) ) {
				if( valid_int( $criteres['Critere']['dtnai']['year'] ) ) {
					$conditions[] = 'EXTRACT(YEAR FROM Personne.dtnai) = '.$criteres['Critere']['dtnai']['year'];
				}
				if( valid_int( $criteres['Critere']['dtnai']['month'] ) ) {
					$conditions[] = 'EXTRACT(MONTH FROM Personne.dtnai) = '.$criteres['Critere']['dtnai']['month'];
				}
				if( valid_int( $criteres['Critere']['dtnai']['day'] ) ) {
					$conditions[] = 'EXTRACT(DAY FROM Personne.dtnai) = '.$criteres['Critere']['dtnai']['day'];
				}
			}

			// Localité adresse
			if( !empty( $locaadr ) ) {
				$conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
			}

			// Critères sur l'adresse - code insee
			if( isset( $criteres['Adresse']['numcomptt'] ) && !empty( $criteres['Adresse']['numcomptt'] ) ) {
				$conditions[] = "Adresse.numcomptt ILIKE '%".Sanitize::paranoid( $criteres['Adresse']['numcomptt'] )."%'";
			}

			/// Critères sur l'adresse - canton
			if( Configure::read( 'CG.cantons' ) ) {
				if( isset( $criteres['Canton']['canton'] ) && !empty( $criteres['Canton']['canton'] ) ) {
					$this->Canton = ClassRegistry::init( 'Canton' );
					$conditions[] = $this->Canton->queryConditions( $criteres['Canton']['canton'] );
				}
			}

			// ...
			if( !empty( $statut_orient ) ) {
				$conditions[] = 'Orientstruct.statut_orient = \''.Sanitize::clean( $statut_orient ).'\'';
				// INFO: nouvelle manière de générer les PDFs
				if( $statut_orient == 'Orienté' ) {
					$conditions[] = 'Orientstruct.id IN ( SELECT pdfs.fk_value FROM pdfs WHERE modele = \'Orientstruct\' )';
				}
			}

			// ...
			if( !empty( $natpf ) ) {
				$conditions[] = 'Detaildroitrsa.id IN (
									SELECT detailscalculsdroitsrsa.detaildroitrsa_id
										FROM detailscalculsdroitsrsa
											INNER JOIN detailsdroitsrsa ON (
												detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id
											)
										WHERE
											detailsdroitsrsa.dossier_id = Dossier.id
											AND detailscalculsdroitsrsa.natpf ILIKE \'%'.Sanitize::clean( $natpf ).'%\'
								)';
			}

			// Recherche par identifiant Pôle Emploi
			if( !empty( $identifiantpe ) ) {
				$conditions[] = ClassRegistry::init( 'Historiqueetatpe' )->conditionIdentifiantpe( $identifiantpe );
			}

			// ...
			if( !empty( $matricule ) ) {
				$conditions[] = 'Dossier.matricule = \''.Sanitize::clean( $matricule ).'\'';
			}

			// ...
			if( !empty( $typeorient_id ) ) {
				$conditions[] = 'Orientstruct.typeorient_id = \''.Sanitize::clean( $typeorient_id ).'\'';
			}

			// ...
			if( !empty( $structurereferente_id ) ) {
				$conditions[] = 'Orientstruct.structurereferente_id = \''.Sanitize::clean( $structurereferente_id ).'\'';
			}

			// Recherche sur la structureréférente ayant fait l'orientation
			if( !empty( $structureorientante_id ) ) {
				$conditions[] = 'Orientstruct.structureorientante_id = \''.Sanitize::clean( $structureorientante_id ).'\'';
			}

			// Recherche sur le référent ayant fait l'orientation
			if( !empty( $referentorientant_id ) ) {
				$conditions[] = 'Orientstruct.referentorientant_id = \''.Sanitize::clean( suffix( $referentorientant_id ) ).'\'';
			}
			
			// ... FIXME
			if( !empty( $serviceinstructeur_id ) ) {

				$conditions[] = 'Serviceinstructeur.id = \''.Sanitize::clean( $serviceinstructeur_id ).'\'';
			}

			if( !empty( $referent_id ) ) {
				$conditions[] = 'PersonneReferent.referent_id = \''.Sanitize::clean( $referent_id ).'\'';
			}

			
			$etatdossier = Set::extract( $criteres, 'Situationdossierrsa.etatdosrsa' );
			if( isset( $criteres['Situationdossierrsa']['etatdosrsa'] ) && !empty( $criteres['Situationdossierrsa']['etatdosrsa'] ) ) {
				$conditions[] = '( Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $etatdossier ).'\' ) )';
			}

			// Trouver la dernière demande RSA pour chacune des personnes du jeu de résultats
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $criteres );

			$hasContrat  = Set::extract( $criteres, 'Critere.hascontrat' );

			/// Statut contrat engagement reciproque
			if( !empty( $hasContrat ) && in_array( $hasContrat, array( 'O', 'N' ) ) ) {
				if( $hasContrat == 'O' ) {
					$conditions[] = '( SELECT COUNT(contratsinsertion.id) FROM contratsinsertion WHERE contratsinsertion.personne_id = "Personne"."id" ) > 0';
				}
				else {
					$conditions[] = '( SELECT COUNT(contratsinsertion.id) FROM contratsinsertion WHERE contratsinsertion.personne_id = "Personne"."id" ) = 0';
				}
			}

			if( isset( $criteres['Critere']['origine'] ) && !empty( $criteres['Critere']['origine'] ) ) {
				$conditions[] = 'Orientstruct.origine = \''.Sanitize::clean( $criteres['Critere']['origine'] ).'\'';
			}

			$hasReferent  = Set::extract( $criteres, 'Critere.hasreferent' );
			/// Statut contrat engagement reciproque
			if( !empty( $hasReferent ) && in_array( $hasReferent, array( 'O', 'N' ) ) ) {
				if( $hasReferent == 'O' ) {
					$conditions[] = '( SELECT COUNT(personnes_referents.id) FROM personnes_referents WHERE personnes_referents.personne_id = "Personne"."id" AND personnes_referents.dfdesignation IS NULL  ) > 0';
				}
				else {
					$conditions[] = '(
						( SELECT personnes_referents.dfdesignation
							FROM personnes_referents
							WHERE
								personnes_referents.personne_id = "Personne"."id"
							ORDER BY personnes_referents.dddesignation DESC,
									personnes_referents.id DESC
							LIMIT 1
						) IS NOT NULL
						OR
						( SELECT COUNT(personnes_referents.id)
							FROM personnes_referents
							WHERE personnes_referents.personne_id = "Personne"."id"
						) = 0
					)';
				}
			}
			
			/// Requête
			$Situationdossierrsa = ClassRegistry::init( 'Situationdossierrsa' );
			$dbo = $this->getDataSource( $this->useDbConfig );

			$query = array(
				'fields' => array(
					'"Orientstruct"."id"',
					'"Orientstruct"."personne_id"',
					'"Orientstruct"."typeorient_id"',
					'"Orientstruct"."structurereferente_id"',
					'"Orientstruct"."propo_algo"',
					'"Orientstruct"."valid_cg"',
					'"Orientstruct"."date_propo"',
					'"Orientstruct"."date_valid"',
					'"Orientstruct"."statut_orient"',
					'"Orientstruct"."date_impression"',
					'"Orientstruct"."origine"',
					'"Dossier"."id"',
					'"Dossier"."numdemrsa"',
					'"Dossier"."matricule"',
					'"Dossier"."dtdemrsa"',
					'"Personne"."id"',
					'"Personne"."nom"',
					'"Personne"."prenom"',
					'"Personne"."nir"',
					'"Personne"."dtnai"',
					'"Personne"."qual"',
					'"Personne"."nomcomnai"',
					'"Adresse"."numvoie"',
					'"Adresse"."nomvoie"',
					'"Adresse"."complideadr"',
					'"Adresse"."compladr"',
					'"Adresse"."typevoie"',
					'"Adresse"."codepos"',
					'"Adresse"."locaadr"',
					'"Adresse"."numcomptt"',
					'"Modecontact"."numtel"',
					'"Serviceinstructeur"."id"',
					'"Serviceinstructeur"."lib_service"',
					'"Situationdossierrsa"."etatdosrsa"',
					'"Calculdroitrsa"."toppersdrodevorsa"',
					'"PersonneReferent"."referent_id"',
					'Historiqueetatpe.identifiantpe',
					'"Prestation"."rolepers"'
				),
				'recursive' => -1,
				'joins' => array(
					array(
						'table'      => $dbo->fullTableName( ClassRegistry::init( 'Personne' ), false ), /// FIXME: performances -> à faire pour les autres ou pas ?
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.id = Orientstruct.personne_id' )
					),
					array(
						'table'      => 'prestations',
						'alias'      => 'Prestation',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Prestation.personne_id = Personne.id',
							'Prestation.natprest = \'RSA\'',
							'Prestation.rolepers IN ( \'DEM\', \'CJT\' )'
						)
					),
					ClassRegistry::init( 'Informationpe' )->joinPersonneInformationpe(),
					ClassRegistry::init( 'Historiqueetatpe' )->joinInformationpeHistoriqueetatpe(),
					array(
						'table'      => 'calculsdroitsrsa',
						'alias'      => 'Calculdroitrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Calculdroitrsa.personne_id = Personne.id' )
					),
					array(
						'table'      => 'foyers',
						'alias'      => 'Foyer',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Personne.foyer_id = Foyer.id' )
					),
					array(
						'table'      => 'dossiers',
						'alias'      => 'Dossier',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Foyer.dossier_id = Dossier.id' )
					),
					array(
						'table'      => 'modescontact',
						'alias'      => 'Modecontact',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Modecontact.foyer_id = Foyer.id',
							'Modecontact.id IN (
								'.ClassRegistry::init( 'Modecontact' )->sqDerniere('Modecontact.foyer_id').'
							)'
						)
					),
					array(
						'table'      => 'typesorients',
						'alias'      => 'Typeorient',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Typeorient.id = Orientstruct.typeorient_id' )
					),
					array(
						'table'      => 'structuresreferentes',
						'alias'      => 'Structurereferente',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Orientstruct.structurereferente_id = Structurereferente.id' )
					),
					array(
						'table'      => 'suivisinstruction',
						'alias'      => 'Suiviinstruction',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Suiviinstruction.dossier_id = Dossier.id',
							'Suiviinstruction.id IN (
								'.ClassRegistry::init( 'Suiviinstruction' )->sqDerniere('Suiviinstruction.dossier_id').'
							)'
						)
					),
					array(
						'table'      => 'servicesinstructeurs',
						'alias'      => 'Serviceinstructeur',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Suiviinstruction.numdepins = Serviceinstructeur.numdepins AND Suiviinstruction.typeserins = Serviceinstructeur.typeserins AND Suiviinstruction.numcomins = Serviceinstructeur.numcomins AND Suiviinstruction.numagrins = Serviceinstructeur.numagrins' )
					),
					array(
						'table'      => 'situationsdossiersrsa',
						'alias'      => 'Situationdossierrsa',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Situationdossierrsa.dossier_id = Dossier.id /*AND ( Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatOuvert() ).'\' ) )*/' )
					),
					array(
						'table'      => 'detailsdroitsrsa',
						'alias'      => 'Detaildroitrsa',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Detaildroitrsa.dossier_id = Dossier.id' )
					),
					array(
						'table'      => 'personnes_referents',
						'alias'      => 'PersonneReferent',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = PersonneReferent.personne_id',
							'PersonneReferent.id IN (
								'.ClassRegistry::init( 'PersonneReferent' )->sqDerniere('PersonneReferent.personne_id').'
							)'
						)
					),
					array(
						'table'      => 'referents',
						'alias'      => 'Referent',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Referent.id = PersonneReferent.referent_id' )
					),
				),
				'limit' => 10,
				'conditions' => $conditions
			);

			// Permet de voir les entrées qui n'ont pas d'adresse si on ne filtre
			// pas sur les codes INSEE pour l'utilisateur
			if( $filtre_zone_geo )
				$type = 'INNER';
			else
				$type = 'LEFT OUTER';

			$query['joins'][] = array(
				'table'      => 'adressesfoyers',
				'alias'      => 'Adressefoyer',
				'type'       => $type,
				'foreignKey' => false,
				'conditions' => array(
					'Foyer.id = Adressefoyer.foyer_id',
					'Adressefoyer.id IN (
						'.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id').'
					)'
				)
			);
			$query['joins'][] = array(
				'table'      => 'adresses',
				'alias'      => 'Adresse',
				'type'       => $type,
				'foreignKey' => false,
				'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
			);

			return $query;
		}
	}
?>