<?php
	/**
	* Behavior permettant de transformer les critères d'un filtre de recherche en
	* conditions pour les queryData d'une requête CakePHP pour le projet WebRSA
	*
	* @package app
	* @subpackage app.models.behaviors
	*/

	App::import( 'Sanitize' );

	class ConditionnableBehavior extends ModelBehavior
	{
		/**
		* Filtre par adresse pour les moteurs de recherche
		*/
		public function conditionsAdresse( &$model, $conditions, $search, $filtre_zone_geo, $mesCodesInsee ) {
			$CantonModel = ClassRegistry::init( 'Canton' );

			/// Critères sur l'adresse - nom de commune
			if( isset( $search['Adresse']['locaadr'] ) && !empty( $search['Adresse']['locaadr'] ) ) {
				$conditions[] = "Adresse.locaadr ILIKE '%".Sanitize::clean( $search['Adresse']['locaadr'] )."%'";
			}

			/// Critères sur l'adresse - code insee
			if( isset( $search['Adresse']['numcomptt'] ) && !empty( $search['Adresse']['numcomptt'] ) ) {
				$conditions[] = "Adresse.numcomptt ILIKE '%".Sanitize::clean( trim( $search['Adresse']['numcomptt'] ) )."%'";
			}

			/// Critères sur l'adresse - canton
			if( Configure::read( 'CG.cantons' ) ) {
				if( isset( $search['Canton']['canton'] ) && !empty( $search['Canton']['canton'] ) ) {
					$conditions[] = $CantonModel->queryConditions( $search['Canton']['canton'] );
				}
			}

			/// Filtre zone géographique de l'utilisateur
			if( $filtre_zone_geo ) {
				// Si on utilise la table des cantons plutôt que la table zonesgeographiques
				if( Configure::read( 'CG.cantons' ) ) { // FIXME: est-ce bien la signification de la variable ?
					$conditions[] = $CantonModel->queryConditionsByZonesgeographiques( array_keys( $mesCodesInsee ) );
				}
				else {
					$mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : array( null ) );
					$conditions[] = '( Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' ) )';
				}
			}

			return $conditions;
		}

		/**
		* Filtres sur le dossier:
		*	- Dossier: numdemrsa, matricule, dtdemrsa
		*/
		public function conditionsDossier( &$model, $conditions, $search ) {
			foreach( array( 'numdemrsa', 'matricule' ) as $critereDossier ) {
				if( isset( $search['Dossier'][$critereDossier] ) && !empty( $search['Dossier'][$critereDossier] ) ) {
					$conditions[] = 'Dossier.'.$critereDossier.' ILIKE \''.$model->wildcard( "*{$search['Dossier'][$critereDossier]}*" ).'\'';
				}
			}

			foreach( array( 'dtdemrsa' ) as $critereDossier ) {
				if( isset( $search['Dossier'][$critereDossier] ) && !empty( $search['Dossier'][$critereDossier]['day'] ) && !empty( $search['Dossier'][$critereDossier]['month'] ) && !empty( $search['Dossier'][$critereDossier]['year'] ) ) {
					$conditions["Dossier.{$critereDossier}"] = "{$search['Dossier'][$critereDossier]['year']}-{$search['Dossier'][$critereDossier]['month']}-{$search['Dossier'][$critereDossier]['day']}";
				}
			}

			return $conditions;
		}

		/**
		* Filtres sur le foyer:
		*	- Foyer: sitfam, ddsitfam
		*/
		public function conditionsFoyer( &$model, $conditions, $search ) {
			foreach( array( 'sitfam' ) as $critereFoyer ) {
				if( isset( $search['Foyer'][$critereFoyer] ) && !empty( $search['Foyer'][$critereFoyer] ) ) {
					$conditions["Foyer.{$critereFoyer}"] = $search['Foyer'][$critereFoyer];
				}
			}

			foreach( array( 'ddsitfam' ) as $critereFoyer ) {
				if( isset( $search['Foyer'][$critereFoyer] ) && !empty( $search['Foyer'][$critereFoyer]['day'] ) && !empty( $search['Foyer'][$critereFoyer]['month'] ) && !empty( $search['Foyer'][$critereFoyer]['year'] ) ) {
					$conditions["Foyer.{$critereFoyer}"] = "{$search['Foyer'][$critereFoyer]['year']}-{$search['Foyer'][$critereFoyer]['month']}-{$search['Foyer'][$critereFoyer]['day']}";
				}
			}

			return $conditions;
		}

		/**
		* Filtres sur le foyer:
		*	- Situationdossierrsa: etatdosrsa
		*/
		public function conditionsSituationdossierrsa( &$model, $conditions, $search ) {
			$etatdossier = Set::extract( $search, 'Situationdossierrsa.etatdosrsa' );
			if( isset( $search['Situationdossierrsa']['etatdosrsa'] ) && !empty( $search['Situationdossierrsa']['etatdosrsa'] ) ) {
				$conditions[] = '( Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $etatdossier ).'\' ) )';
			}

			return $conditions;
		}

		/**
		* Filtres sur la personne:
		*	- Personne: nom, prenom, nomnai, nir, dtnai
		*/
		public function conditionsPersonne( &$model, $conditions, $search ) {
			foreach( array( 'nom', 'prenom', 'nomnai', 'nir' ) as $criterePersonne ) {
				if( isset( $search['Personne'][$criterePersonne] ) && !empty( $search['Personne'][$criterePersonne] ) ) {
					$conditions[] = 'UPPER(Personne.'.$criterePersonne.') LIKE \''.$model->wildcard( strtoupper( replace_accents( $search['Personne'][$criterePersonne] ) ) ).'\'';
				}
			}

			/// Critères sur une personne du foyer - date de naissance
			if( isset( $search['Personne']['dtnai'] ) && !empty( $search['Personne']['dtnai'] ) ) {
				if( valid_int( $search['Personne']['dtnai']['year'] ) ) {
					$conditions[] = 'EXTRACT(YEAR FROM Personne.dtnai) = '.$search['Personne']['dtnai']['year'];
				}
				if( valid_int( $search['Personne']['dtnai']['month'] ) ) {
					$conditions[] = 'EXTRACT(MONTH FROM Personne.dtnai) = '.$search['Personne']['dtnai']['month'];
				}
				if( valid_int( $search['Personne']['dtnai']['day'] ) ) {
					$conditions[] = 'EXTRACT(DAY FROM Personne.dtnai) = '.$search['Personne']['dtnai']['day'];
				}
			}

			return $conditions;
		}

		// TODO - à bouger - Situationdossierrsa.etatdosrsa, Detailcalculdroitrsa.natpf
		// TODO - à bouger - Personne.XXXXX

		/**
		*
		*/
		public function conditionsPersonneFoyerDossier( &$model, $conditions, $search ) {
			$conditions = $this->conditionsDossier( $model, $conditions, $search );
			$conditions = $this->conditionsPersonne( $model, $conditions, $search );
			$conditions = $this->conditionsSituationdossierrsa( $model, $conditions, $search );

			/// Nature de la prestation
			if( isset( $search['Detailcalculdroitrsa']['natpf'] ) && !empty( $search['Detailcalculdroitrsa']['natpf'] ) ) {
				$conditions[] = 'Detaildroitrsa.id IN (
									SELECT detailscalculsdroitsrsa.detaildroitrsa_id
										FROM detailscalculsdroitsrsa
											INNER JOIN detailsdroitsrsa ON (
												detailscalculsdroitsrsa.detaildroitrsa_id = detailsdroitsrsa.id
											)
										WHERE
											detailsdroitsrsa.dossier_id = Dossier.id
											AND detailscalculsdroitsrsa.natpf ILIKE \'%'.Sanitize::clean( $search['Detailcalculdroitrsa']['natpf'] ).'%\'
								)';
			}

			return $conditions;
		}

		/**
		*
		*/

		public function conditionsDernierDossierAllocataire( &$model, $conditions, $search ) {
			if( isset( $search['Dossier']['dernier'] ) && $search['Dossier']['dernier'] ) {
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
									nir_correct13( Personne.nir )
									AND nir_correct13( personnes.nir )
									AND SUBSTRING( TRIM( BOTH \' \' FROM personnes.nir ) FROM 1 FOR 13 ) = SUBSTRING( TRIM( BOTH \' \' FROM Personne.nir ) FROM 1 FOR 13 )
									AND personnes.dtnai = Personne.dtnai
								)
								OR
								(
									UPPER(personnes.nom) = UPPER(Personne.nom)
									AND UPPER(personnes.prenom) = UPPER(Personne.prenom)
									AND personnes.dtnai = Personne.dtnai
								)
							)
						ORDER BY dossiers.dtdemrsa DESC
						LIMIT 1
				)';
			}

			return $conditions;
		}
	}
?>