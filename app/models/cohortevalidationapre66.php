<?php
App::import( 'Sanitize' );
	class Cohortevalidationapre66 extends AppModel
	{
		public $name = 'Cohortevalidationapre66';

		public $useTable = false;

		/**
		*
		*/

		public function search( $statutValidation, $mesCodesInsee, $filtre_zone_geo, $criteresapres, $lockedDossiers ) {
			/// Conditions de base
			$conditions = array(
			);

			if( !empty( $statutValidation ) ) {
				if( $statutValidation == 'Validationapre::apresavalider' ) {
					$conditions[] = '( ( Apre66.etatdossierapre = \'COM\' ) AND ( Apre66.isdecision = \'N\' ) )';
				}
				else if( $statutValidation == 'Validationapre::validees' ) {
					$conditions[] = '( Apre66.etatdossierapre = \'VAL\' ) AND  ( Apre66.datenotifapre IS NULL )';
				}
				else if( $statutValidation == 'Validationapre::notifiees' ) {
					$conditions[] = '( Apre66.etatdossierapre = \'VAL\' ) AND  ( Apre66.datenotifapre IS NOT NULL )';
				}
				else if( $statutValidation == 'Validationapre::traitementcellule' ) {
					$conditions[] = '( Apre66.etatdossierapre = \'VAL\' ) AND  ( Apre66.datenotifapre IS NOT NULL ) AND ( Apre66.istraite = \'0\' )';
				}
			}

			$conditions[] = $this->conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee );

			/// Dossiers lockés
			if( !empty( $lockedDossiers ) ) {
				$conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
			}

			/// Critères
			$numeroapre = Set::extract( $criteresapres, 'Search.Apre66.numeroapre' );
			$referent = Set::extract( $criteresapres, 'Search.Apre66.referent_id' );
			$locaadr = Set::extract( $criteresapres, 'Search.Adresse.locaadr' );
			$numcomptt = Set::extract( $criteresapres, 'Search.Adresse.numcomptt' );
			$numdemrsa = Set::extract( $criteresapres, 'Search.Dossier.numdemrsa' );
			$matricule = Set::extract( $criteresapres, 'Search.Dossier.matricule' );
			$themeapre66_id = Set::extract( $criteresapres, 'Search.Aideapre66.themeapre66_id' );
			$typeaideapre66_id = Set::extract( $criteresapres, 'Search.Aideapre66.typeaideapre66_id' );

			// Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
			foreach( array( 'nom', 'prenom', 'nomnai', 'nir' ) as $criterePersonne ) {
				if( isset( $criteresapres['Search']['Personne'][$criterePersonne] ) && !empty( $criteresapres['Search']['Personne'][$criterePersonne] ) ) {
					$conditions[] = 'Personne.'.$criterePersonne.' ILIKE \''.$this->wildcard( $criteresapres['Search']['Personne'][$criterePersonne] ).'\'';
				}
			}

			
			/// Critères sur l'adresse - canton
			if( Configure::read( 'CG.cantons' ) ) {
				if( isset( $criteresapres['Search']['Canton']['canton'] ) && !empty( $criteresapres['Search']['Canton']['canton'] ) ) {
					$this->Canton = ClassRegistry::init( 'Canton' );
					$conditions[] = $this->Canton->queryConditions( $criteresapres['Search']['Canton']['canton'] );
				}
			}
			
			// Localité adresse
			if( !empty( $locaadr ) ) {
				$conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
			}

			// Commune au sens INSEE
			if( !empty( $numcomptt ) ) {
				$conditions[] = 'Adresse.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'';
			}

			// Référent lié à l'APRE
			if( !empty( $referent ) ) {
				$conditions[] = 'Apre66.referent_id = \''.Sanitize::clean( $referent ).'\'';
			}

			//Critères sur le dossier de l'allocataire - numdemrsa + matricule
			foreach( array( 'numdemrsa', 'matricule' ) as $critereDossier ) {
				if( isset( $criteresapres['Search']['Dossier'][$critereDossier] ) && !empty( $criteresapres['Search']['Dossier'][$critereDossier] ) ) {
					$conditions[] = 'Dossier.'.$critereDossier.' ILIKE \''.$this->wildcard( $criteresapres['Search']['Dossier'][$critereDossier] ).'\'';
				}
			}

			
			//Thème de l'aide
			if( !empty( $themeapre66_id ) ) {
				$conditions[] = 'Aideapre66.themeapre66_id = \''.Sanitize::clean( $themeapre66_id ).'\'';
			}

			//Type d'aide
			if( !empty( $typeaideapre66_id ) ) {
				$conditions[] = 'Aideapre66.typeaideapre66_id = \''.Sanitize::clean( suffix( $typeaideapre66_id ) ).'\'';
			}
			
			/// Requête
			$this->Dossier = ClassRegistry::init( 'Dossier' );

			$joins = array(
				array(
					'table'      => 'personnes',
					'alias'      => 'Personne',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Personne.id = Apre66.personne_id' ),
				),
				array(
					'table'      => 'aidesapres66',
					'alias'      => 'Aideapre66',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Aideapre66.apre_id = Apre66.id' ),
				),
				array(
					'table'      => 'typesaidesapres66',
					'alias'      => 'Typeaideapre66',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Typeaideapre66.id = Aideapre66.typeaideapre66_id' ),
				),
				array(
					'table'      => 'themesapres66',
					'alias'      => 'Themeapre66',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Themeapre66.id = Typeaideapre66.themeapre66_id' ),
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
                                        'table'      => 'dossiers',
                                        'alias'      => 'Dossier',
                                        'type'       => 'INNER',
                                        'foreignKey' => false,
                                        'conditions' => array( 'Foyer.dossier_id = Dossier.id' )
                                ),
                                array(
                                        'table'      => 'adressesfoyers',
                                        'alias'      => 'Adressefoyer',
                                        'type'       => 'INNER',
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
                                        'type'       => 'INNER',
                                        'foreignKey' => false,
                                        'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                                ),
                                array(
                                        'table'      => 'referents',
                                        'alias'      => 'Referent',
                                        'type'       => 'INNER',
                                        'foreignKey' => false,
                                        'conditions' => array( 'Referent.id = Apre66.referent_id' ),
                                )
			);

			$query = array(
				'fields' => array(
					'Apre66.id',
					'Apre66.personne_id',
					'Apre66.numeroapre',
					'Apre66.typedemandeapre',
					'Apre66.datedemandeapre',
					'Apre66.naturelogement',
					'Apre66.anciennetepoleemploi',
					'Apre66.activitebeneficiaire',
					'Apre66.etatdossierapre',
					'Apre66.dateentreeemploi',
					'Apre66.eligibiliteapre',
					'Apre66.typecontrat',
					'Apre66.statutapre',
					'Apre66.mtforfait',
					'Apre66.isdecision',
					'Apre66.istraite',
					'Apre66.nbenf12',
                                        'Apre66.referent_id',
					'Aideapre66.id',
					'Aideapre66.apre_id',
					'Aideapre66.decisionapre',
					'Aideapre66.montantaccorde',
					'Aideapre66.typeaideapre66_id',
					'Aideapre66.montantpropose',
					'Aideapre66.datedemande',
					'Aideapre66.datemontantaccorde',
					'Aideapre66.datemontantpropose',
					'Aideapre66.motifrejetequipe',
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
					'Adressefoyer.rgadr',
					'Adresse.numcomptt',
					'Typeaideapre66.name',
					'Themeapre66.name',
                                        'Referent.nom_complet'
				),
				'joins' => $joins,
				'contain' => false,
				'conditions' => $conditions
			);

			return $query;
		}
	}
?>