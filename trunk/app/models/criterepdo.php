<?php
	class Criterepdo extends AppModel
	{
		public $name = 'Criterepdo';

		public $useTable = false;

		/**
		*
		*/

		public function listeDossierPDO( $mesCodesInsee, $filtre_zone_geo, $criterespdos, $lockedDossiers ) {
			$Situationdossierrsa = ClassRegistry::init( 'Situationdossierrsa' );
			/// Conditions de base
			$conditions = array();

			$conditions[] = 'Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatAttente() ).'\' ) ';

			/// Filtre zone géographique
			if( $filtre_zone_geo ) {
				$mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
				$conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
			}

			/// Dossiers lockés
			if( !empty( $lockedDossiers ) ) {
				$conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
			}

			/// Critères

			$decisionpdo = Set::extract( $criterespdos, 'Search.Decisionpropopdo.decisionpdo_id' );
			$motifpdo = Set::extract( $criterespdos, 'Search.Propopdo.motifpdo' );
			$originepdo = Set::extract( $criterespdos, 'Search.Propopdo.originepdo_id' );
            $etatdossierpdo = Set::extract( $criterespdos, 'Search.Propopdo.etatdossierpdo' );
			$nir = Set::extract( $criterespdos, 'Search.Personne.nir' );
			$nom = Set::extract( $criterespdos, 'Search.Personne.nom' );
			$prenom = Set::extract( $criterespdos, 'Search.Personne.prenom' );
			$matricule = Set::extract( $criterespdos, 'Search.Dossier.matricule' );
			$numdemrsa = Set::extract( $criterespdos, 'Search.Dossier.numdemrsa' );

			$dtdemrsa = Set::extract( $criterespdos, 'Search.Dossier.dtdemrsa' );
			$dtdemrsa_from = Set::extract( $criterespdos, 'Search.Dossier.dtdemrsa_from' );
			$dtdemrsa_to = Set::extract( $criterespdos, 'Search.Dossier.dtdemrsa_to' );

			/// Critères sur les PDOs - date de decisonde la PDO
			if( isset( $criterespdos['Propopdo']['datedecisionpdo'] ) && !empty( $criterespdos['Propopdo']['datedecisionpdo'] ) ) {
				$valid_from = ( valid_int( $criterespdos['Propopdo']['datedecisionpdo_from']['year'] ) && valid_int( $criterespdos['Propopdo']['datedecisionpdo_from']['month'] ) && valid_int( $criterespdos['Propopdo']['datedecisionpdo_from']['day'] ) );
				$valid_to = ( valid_int( $criterespdos['Propopdo']['datedecisionpdo_to']['year'] ) && valid_int( $criterespdos['Propopdo']['datedecisionpdo_to']['month'] ) && valid_int( $criterespdos['Propopdo']['datedecisionpdo_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Decisionpropopdo.datedecisionpdo BETWEEN \''.implode( '-', array( $criterespdos['Propopdo']['datedecisionpdo_from']['year'], $criterespdos['Propopdo']['datedecisionpdo_from']['month'], $criterespdos['Propopdo']['datedecisionpdo_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterespdos['Propopdo']['datedecisionpdo_to']['year'], $criterespdos['Propopdo']['datedecisionpdo_to']['month'], $criterespdos['Propopdo']['datedecisionpdo_to']['day'] ) ).'\'';
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
			// Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
			if( !empty( $nom ) ) {
				$conditions[] = 'UPPER(Personne.nom) LIKE \''.$this->wildcard( strtoupper( replace_accents( $nom ) ) ).'\'';
			}
			if( !empty( $prenom ) ) {
				$conditions[] = 'UPPER(Personne.prenom) LIKE \''.$this->wildcard( strtoupper( replace_accents( $prenom ) ) ).'\'';
			}

			// Localité adresse
			if( !empty( $locaadr ) ) {
				$conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
			}

			// ...
			if( !empty( $dtdemrsa ) ) {
				$dtdemrsa_from = "{$dtdemrsa_from['year']}-{$dtdemrsa_from['month']}-{$dtdemrsa_from['day']}";
				$dtdemrsa_to = "{$dtdemrsa_to['year']}-{$dtdemrsa_to['month']}-{$dtdemrsa_to['day']}";
				$conditions[] = "Dossier.dtdemrsa BETWEEN '{$dtdemrsa_from}' AND '{$dtdemrsa_to}'";
			}

			// ...
			if( !empty( $matricule ) ) {
				$conditions[] = 'Dossier.matricule = \''.Sanitize::clean( $matricule ).'\'';
			}
			// ...
			if( !empty( $numdemrsa ) ) {
				$conditions[] = 'Dossier.numdemrsa = \''.Sanitize::clean( $numdemrsa ).'\'';
			}

			/// Critères sur l'adresse - canton
			if( Configure::read( 'CG.cantons' ) ) {
				if( isset( $criterespdos['Canton']['canton'] ) && !empty( $criterespdos['Canton']['canton'] ) ) {
					$this->Canton = ClassRegistry::init( 'Canton' );
					$conditions[] = $this->Canton->queryConditions( $criterespdos['Canton']['canton'] );
				}
			}

			// NIR
			if( !empty( $nir ) ) {
				$conditions[] = 'Personne.nir ILIKE \'%'.Sanitize::clean( $nir ).'%\'';
			}

			// Commune au sens INSEE
			if( !empty( $numcomptt ) ) {
				$conditions[] = 'Adresse.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'';
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


            // Trouver la dernière demande RSA pour chacune des personnes du jeu de résultats
            if( $criterespdos['Dossier']['dernier'] ) {
                $conditions[] = 'Dossier.id IN (
                    SELECT
                            dossiers.id
                        FROM personnes
                            INNER JOIN prestations ON (
                                personnes.id = prestations.personne_id
                                AND prestations.natprest = \'RSA\'
                            )
                            INNER JOIN foyers ON (
                                personnes.foyer_id = foyers.id
                            )
                            INNER JOIN dossiers ON (
                                dossiers.id = foyers.dossier_id
                            )
                        WHERE
                            prestations.rolepers IN ( \'DEM\', \'CJT\' )
                            AND (
                                (
                                    nir_correct( Personne.nir )
                                    AND nir_correct( personnes.nir )
                                    AND personnes.nir = Personne.nir
                                    AND personnes.dtnai = Personne.dtnai
                                )
                                OR
                                (
                                    personnes.nom = Personne.nom
                                    AND personnes.prenom = Personne.prenom
                                    AND personnes.dtnai = Personne.dtnai
                                )
                            )
                        ORDER BY dossiers.dtdemrsa DESC
                        LIMIT 1
                )';
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
//                             '( Prestation.natprest = \'RSA\' OR Prestation.natprest = \'PFA\' )',
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
			if( $filtre_zone_geo ) {
				$mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
				$conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
			}

			/// Dossiers lockés
			if( !empty( $lockedDossiers ) ) {
				$conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
			}

			/// Critères

			$decisionpdo = Set::extract( $criterespdos, 'Search.Decisionpropopdo.decisionpdo_id' );
			$motifpdo = Set::extract( $criterespdos, 'Search.Propopdo.motifpdo' );
			$originepdo = Set::extract( $criterespdos, 'Search.Propopdo.originepdo_id' );
			$nir = Set::extract( $criterespdos, 'Search.Propopdo.nir' );
			$nom = Set::extract( $criterespdos, 'Search.Personne.nom' );
			$prenom = Set::extract( $criterespdos, 'Search.Personne.prenom' );
			$matricule = Set::extract( $criterespdos, 'Search.Dossier.matricule' );
			$numdemrsa = Set::extract( $criterespdos, 'Search.Dossier.numdemrsa' );
			$gestionnaire = Set::extract( $criterespdos, 'Search.Propopdo.user_id' );
            $etatdossierpdo = Set::extract( $criterespdos, 'Search.Propopdo.etatdossierpdo' );

			/// Critères sur les PDOs - date de decisonde la PDO
			if( isset( $criterespdos['Propopdo']['datedecisionpdo'] ) && !empty( $criterespdos['Propopdo']['datedecisionpdo'] ) ) {
				$valid_from = ( valid_int( $criterespdos['Propopdo']['datedecisionpdo_from']['year'] ) && valid_int( $criterespdos['Propopdo']['datedecisionpdo_from']['month'] ) && valid_int( $criterespdos['Propopdo']['datedecisionpdo_from']['day'] ) );
				$valid_to = ( valid_int( $criterespdos['Propopdo']['datedecisionpdo_to']['year'] ) && valid_int( $criterespdos['Propopdo']['datedecisionpdo_to']['month'] ) && valid_int( $criterespdos['Propopdo']['datedecisionpdo_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Decisionpropopdo.datedecisionpdo BETWEEN \''.implode( '-', array( $criterespdos['Propopdo']['datedecisionpdo_from']['year'], $criterespdos['Propopdo']['datedecisionpdo_from']['month'], $criterespdos['Propopdo']['datedecisionpdo_from']['day'] ) ).'\' AND \''.implode( '-', array( $criterespdos['Propopdo']['datedecisionpdo_to']['year'], $criterespdos['Propopdo']['datedecisionpdo_to']['month'], $criterespdos['Propopdo']['datedecisionpdo_to']['day'] ) ).'\'';
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
			// Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
			if( !empty( $nom ) ) {
				$conditions[] = 'Personne.nom ILIKE \''.$this->wildcard( $nom ).'\'';
			}
			if( !empty( $prenom ) ) {
				$conditions[] = 'Personne.prenom ILIKE \''.$this->wildcard( $prenom ).'\'';
			}

			// Localité adresse
			if( !empty( $locaadr ) ) {
				$conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
			}

			// ...
			if( !empty( $matricule ) ) {
				$conditions[] = 'Dossier.matricule = \''.Sanitize::clean( $matricule ).'\'';
			}
			// ...
			if( !empty( $numdemrsa ) ) {
				$conditions[] = 'Dossier.numdemrsa = \''.Sanitize::clean( $numdemrsa ).'\'';
			}

			/// Critères sur l'adresse - canton
			if( Configure::read( 'CG.cantons' ) ) {
				if( isset( $criterespdos['Canton']['canton'] ) && !empty( $criterespdos['Canton']['canton'] ) ) {
					$this->Canton = ClassRegistry::init( 'Canton' );
					$conditions[] = $this->Canton->queryConditions( $criterespdos['Canton']['canton'] );
				}
			}

			// NIR
			if( !empty( $nir ) ) {
				$conditions[] = 'Personne.nir ILIKE \'%'.Sanitize::clean( $nir ).'%\'';
			}

			// Commune au sens INSEE
			if( !empty( $numcomptt ) ) {
				$conditions[] = 'Adresse.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'';
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


			/// Requête
//             $this->Dossier = ClassRegistry::init( 'Dossier' );

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
					'"Adresse"."numcomptt"'
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
//                             '( Prestation.natprest = \'RSA\' OR Prestation.natprest = \'PFA\' )',
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
