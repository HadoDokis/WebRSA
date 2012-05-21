<?php
	App::import( 'Sanitize' );

	class Cohortenonoriente66 extends AppModel
	{
		public $name = 'Cohortenonoriente66';

		public $useTable = false;
		
		public $actsAs = array(
			'Conditionnable'
		);
		
		/**
		*
		*/

		public function search( $statutNonoriente, $mesCodesInsee, $filtre_zone_geo, $criteresnonorientes, $lockedDossiers ) {
			$Personne = ClassRegistry::init( 'Personne' );
			$Informationpe = ClassRegistry::init( 'Informationpe' );

			/// Conditions de base
			$conditions = array();
			$conditions[] = '( SELECT COUNT(orientsstructs.id) FROM orientsstructs WHERE orientsstructs.personne_id = "Personne"."id" ) = 0';


			if( !empty( $statutNonoriente ) ) {
				if( $statutNonoriente == 'Nonoriente::isemploi' ) {
					$conditions[] = 'Personne.id IN (
						SELECT
								personnes.id
							FROM informationspe
								INNER JOIN historiqueetatspe ON (
									informationspe.id = historiqueetatspe.informationpe_id
									AND historiqueetatspe.id IN (
												SELECT h.id
													FROM historiqueetatspe AS h
													WHERE h.informationpe_id = informationspe.id
													ORDER BY h.date DESC
													LIMIT 1
									)
								)
								INNER JOIN personnes ON (
									'.$Informationpe->sqConditionsJoinPersonne( 'informationspe', 'personnes' ).'
								)
							WHERE
								personnes.id = Personne.id
								AND historiqueetatspe.etat = \'inscription\'
					)';
				}
				else if( $statutNonoriente == 'Nonoriente::notisemploi' ) {
// 					$conditions[] = '( Personne.etatdossierapre = \'VAL\' ) AND  ( Personne.datenotifapre IS NULL )';
				}
				else if( $statutNonoriente == 'Nonoriente::oriente' ) {
// 					$conditions[] = '( Personne.etatdossierapre = \'VAL\' ) AND  ( Personne.datenotifapre IS NOT NULL )';
				}
			}

			$conditions[] = $this->conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsAdresse( $conditions, $criteresnonorientes['Search'], $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsDossier( $conditions, $criteresnonorientes['Search'] );
			$conditions = $this->conditionsPersonne( $conditions, $criteresnonorientes['Search'] );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $criteresnonorientes['Search'] );
			
// 			if( isset( $criteresnonorientes['Search']['Dossier']['dtdemrsa'] ) && !empty( $criteresnonorientes['Search']['Dossier']['dtdemrsa'] ) ) {
// 				$valid_from = ( valid_int( $criteresnonorientes['Search']['Dossier']['dtdemrsa_from']['year'] ) && valid_int( $criteresnonorientes['Search']['Dossier']['dtdemrsa_from']['month'] ) && valid_int( $criteresnonorientes['Search']['Dossier']['dtdemrsa_from']['day'] ) );
// 				$valid_to = ( valid_int( $criteresnonorientes['Search']['Dossier']['dtdemrsa_to']['year'] ) && valid_int( $criteresnonorientes['Search']['Dossier']['dtdemrsa_to']['month'] ) && valid_int( $criteresnonorientes['Search']['Dossier']['dtdemrsa_to']['day'] ) );
// 				if( $valid_from && $valid_to ) {
// 					$conditions[] = 'Dossier.dtdemrsa BETWEEN \''.implode( '-', array( $criteresnonorientes['Search']['Dossier']['dtdemrsa_from']['year'], $criteresnonorientes['Search']['Dossier']['dtdemrsa_from']['month'], $criteresnonorientes['Search']['Dossier']['dtdemrsa_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresnonorientes['Search']['Dossier']['dtdemrsa_to']['year'], $criteresnonorientes['Search']['Dossier']['dtdemrsa_to']['month'], $criteresnonorientes['Search']['Dossier']['dtdemrsa_to']['day'] ) ).'\'';
// 				}
// 			}
			
			
			/// Dossiers lockés
			if( !empty( $lockedDossiers ) ) {
				$conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
			}


			// Conditions pour les jointures
			$conditions['Prestation.rolepers'] = array( 'DEM', 'CJT' );
			$conditions['Calculdroitrsa.toppersdrodevorsa'] = 1;
			$conditions['Situationdossierrsa.etatdosrsa'] = $Personne->Orientstruct->Personne->Foyer->Dossier->Situationdossierrsa->etatOuvert();
			$conditions[] = 'Adressefoyer.id IN ( '
				.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id')
			.' )';

			$query = array(
				'fields' => array_merge(
					$Personne->fields(),
					$Personne->Foyer->fields(),
					$Personne->Foyer->Dossier->fields(),
					$Personne->Foyer->Adressefoyer->Adresse->fields(),
					$Personne->Foyer->Dossier->Situationdossierrsa->fields(),
					$Personne->Orientstruct->fields()
				),
				'joins' => array(
					$Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$Personne->join( 'Prestation', array( 'type' => 'INNER' ) ),
					$Personne->join( 'Orientstruct', array( 'type' => 'LEFT OUTER' ) ),
					$Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'INNER' ) ),
					$Personne->join( 'Calculdroitrsa', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->Dossier->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
					$Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'INNER' ) ),
				),
				'contain' => false,
				'conditions' => $conditions
			);

			return $query;
		}
	}
?>