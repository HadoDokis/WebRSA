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
		*
		*/

		public function conditionsPersonneFoyerDossier( &$model, $conditions, $search ) {
			foreach( array( 'nom', 'prenom', 'nomnai', 'nir' ) as $criterePersonne ) {
				if( isset( $search['Personne'][$criterePersonne] ) && !empty( $search['Personne'][$criterePersonne] ) ) {
					$conditions[] = 'UPPER(Personne.'.$criterePersonne.') LIKE \''.$model->wildcard( strtoupper( replace_accents( $search['Personne'][$criterePersonne] ) ) ).'\'';
				}
			}

			foreach( array( 'numdemrsa', 'matricule' ) as $critereDossier ) {
				if( isset( $search['Dossier'][$critereDossier] ) && !empty( $search['Dossier'][$critereDossier] ) ) {
					$conditions[] = 'Dossier.'.$critereDossier.' ILIKE \''.$model->wildcard( $search['Dossier'][$critereDossier] ).'\'';
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

		/**
		*
		*/

		public function conditionsDernierDossierAllocataire( &$model, $conditions, $search ) {
			if( $search['Dossier']['dernier'] ) {
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

			return $conditions;
		}
	}
?>