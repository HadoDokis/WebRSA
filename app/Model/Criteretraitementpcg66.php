<?php
	/**
	 * Code source de la classe Criteretraitementpcg66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Criteretraitementpcg66 ...
	 *
	 * @package app.Model
	 */
	class Criteretraitementpcg66 extends AppModel
	{
		public $name = 'Criteretraitementpcg66';

		public $useTable = false;

		public $actsAs = array( 'Conditionnable' );

		/**
		*
		*/

		public function search( $params, $mesCodesInsee,  $filtre_zone_geo ) {
			$conditions = array();
			$Traitementpcg66 = ClassRegistry::init( 'Traitementpcg66' );

			/// Filtre zone géographique
			$conditions[] = $this->conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee );

			$conditions = $this->conditionsAdresse( $conditions, $params, $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $params );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $params );
            $conditions = $this->conditionsDetailcalculdroitrsa( $conditions, $params );

			/// Critères
			$descriptionpdo = Set::extract( $params, 'Traitementpcg66.descriptionpdo_id' );
			$clos = Set::extract( $params, 'Traitementpcg66.clos' );
			$annule = Set::extract( $params, 'Traitementpcg66.annule' );
			$motifpersonnepcg66_id = Set::extract( $params, 'Traitementpcg66.situationpdo_id' );
			$statutpersonnepcg66_id = Set::extract( $params, 'Traitementpcg66.statutpdo_id' );
            $typetraitement = Set::extract( $params, 'Traitementpcg66.typetraitement' );

			$dateecheance = Set::extract( $params, 'Traitementpcg66.dateecheance' );
			$dateecheance_to = Set::extract( $params, 'Traitementpcg66.dateecheance_to' );
			$dateecheance_from = Set::extract( $params, 'Traitementpcg66.dateecheance_from' );

			// Gestionnaire du dossier gérant le traitement
			$gestionnaire = Set::extract( $params, 'Dossierpcg66.user_id' );
			$poledossierpcg66_id = Set::extract( $params, 'Dossierpcg66.poledossierpcg66_id' );

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
				$conditions[] = 'Traitementpcg66.descriptionpdo_id = \''.Sanitize::clean( $descriptionpdo, array( 'encode' => false ) ).'\'';
			}
			if( !empty( $clos ) ) {
				$conditions[] = 'Traitementpcg66.clos = \''.Sanitize::clean( $clos, array( 'encode' => false ) ).'\'';
			}
			if( !empty( $annule ) ) {
				$conditions[] = 'Traitementpcg66.annule = \''.Sanitize::clean( $annule, array( 'encode' => false ) ).'\'';
			}

			// Type de traitement
			if( !empty( $typetraitement ) ) {
				$conditions[] = 'Traitementpcg66.typetraitement = \''.Sanitize::clean( $typetraitement, array( 'encode' => false ) ).'\'';
			}
            
			// Gestionnaire de la PDO
			if( !empty( $gestionnaire ) ) {
				$conditions[] = 'Dossierpcg66.user_id IN ( \''.implode( '\', \'', $gestionnaire ).'\' )';
			}
			
            // Pôle chargé de la PDO
			if( !empty( $poledossierpcg66_id ) ) {
				$conditions[] = 'Dossierpcg66.poledossierpcg66_id IN ( \''.implode( '\', \'', $poledossierpcg66_id ).'\' )';
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

			// Statut de la personne
			if( !empty( $statutpersonnepcg66_id ) ) {
				$conditions[] = 'Personnepcg66.id IN ( '.
					ClassRegistry::init( 'Personnepcg66Statutpdo' )->sq(
						array(
							'fields' => array( 'personnespcgs66_statutspdos.personnepcg66_id' ),
							'alias' => 'personnespcgs66_statutspdos',
							'contain' => false,
							'conditions' => array(
								'personnespcgs66_statutspdos.personnepcg66_id = Personnepcg66.id',
								'personnespcgs66_statutspdos.statutpdo_id' => $statutpersonnepcg66_id
							)
						)
					)
				.' )';
			}

			// Conditions de base pour qu'un allocataire puisse passer en EP
			$conditions['Prestation.rolepers'] = array( 'DEM', 'CJT' );
            
            
            $sqDernierDetailcalculdroitrsa = $Traitementpcg66->Personnepcg66->Dossierpcg66->Foyer->Dossier->Detaildroitrsa->Detailcalculdroitrsa->sqDernier( 'Detaildroitrsa.id' );           
            
			$conditions[] = array(
                array(
                    array(
                        'OR' => array(
                            'Adressefoyer.id IS NULL',
                            'Adressefoyer.id IN ( '.$Traitementpcg66->Personnepcg66->Dossierpcg66->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
                        )
                    ),
                    array(
                        'OR' => array(
                            'Detailcalculdroitrsa.id IS NULL',
                            "Detailcalculdroitrsa.id IN ( {$sqDernierDetailcalculdroitrsa} )"
                        )
                    )
				)
			);
			$conditions[] = 'Personne.id IN ( '.$Traitementpcg66->Personnepcg66->Dossierpcg66->Foyer->Personne->sqResponsableDossierUnique('Foyer.id').' )';
			
			// Corbeille vide ?
			$sqNbFichierDansCorbeille = '( SELECT count( fichiersmodules.id ) FROM fichiersmodules WHERE fichiersmodules.modele = \'Foyer\' AND fichiersmodules.fk_value = "Foyer"."id" )';
			
			if( isset( $params['Dossierpcg66']['exists'] ) && ( $params['Dossierpcg66']['exists'] != '' ) ) {
				if( $params['Dossierpcg66']['exists'] ) {
					$conditions[] = "{$sqNbFichierDansCorbeille} > 0";
				}
				else {
					$conditions[] = "{$sqNbFichierDansCorbeille} = 0";
				}
			}


			$query = array(
                'fields' => array_merge(
                    $Traitementpcg66->fields(),
                    $Traitementpcg66->Personnepcg66->fields(),
                    $Traitementpcg66->Personnepcg66Situationpdo->fields(),
                    $Traitementpcg66->Personnepcg66Situationpdo->Situationpdo->fields(),
                    $Traitementpcg66->Personnepcg66->Dossierpcg66->fields(),
                    $Traitementpcg66->Personnepcg66->Dossierpcg66->Foyer->fields(),
                    $Traitementpcg66->Descriptionpdo->fields(),
                    $Traitementpcg66->Personnepcg66->Dossierpcg66->Foyer->Personne->fields(),
                    $Traitementpcg66->Personnepcg66->Dossierpcg66->Foyer->Personne->Prestation->fields(),
                    $Traitementpcg66->Personnepcg66->Dossierpcg66->Foyer->Adressefoyer->Adresse->fields(),
                    $Traitementpcg66->Personnepcg66->Dossierpcg66->Foyer->Dossier->fields(),
                    $Traitementpcg66->Personnepcg66->Dossierpcg66->Foyer->Dossier->Situationdossierrsa->fields(),
                    array(
                        ClassRegistry::init( 'Fichiermodule' )->sqNbFichiersLies( ClassRegistry::init( 'Foyer' ), 'nb_fichiers_lies'),
                        $Traitementpcg66->Personnepcg66->Dossierpcg66->User->sqVirtualField('nom_complet')
                    )
				),
				'recursive' => -1,
				'joins' => array(
					$Traitementpcg66->join( 'Personnepcg66', array( 'type' => 'INNER' ) ),
					$Traitementpcg66->join( 'Personnepcg66Situationpdo', array( 'type' => 'LEFT OUTER' ) ),
					$Traitementpcg66->Personnepcg66Situationpdo->join( 'Situationpdo', array( 'type' => 'LEFT OUTER' ) ),
					$Traitementpcg66->Personnepcg66->join( 'Dossierpcg66', array( 'type' => 'INNER' ) ),
					$Traitementpcg66->Personnepcg66->Dossierpcg66->join( 'Foyer', array( 'type' => 'INNER' ) ),
                    $Traitementpcg66->Personnepcg66->Dossierpcg66->join( 'User', array( 'type' => 'LEFT OUTER' ) ),
					$Traitementpcg66->join( 'Descriptionpdo', array( 'type' => 'LEFT OUTER' ) ),
					$Traitementpcg66->Personnepcg66->Dossierpcg66->Foyer->join( 'Personne', array( 'type' => 'INNER' ) ),
					$Traitementpcg66->Personnepcg66->Dossierpcg66->Foyer->Personne->join( 'Prestation', array( 'type' => 'INNER' ) ),
					$Traitementpcg66->Personnepcg66->Dossierpcg66->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
					$Traitementpcg66->Personnepcg66->Dossierpcg66->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					$Traitementpcg66->Personnepcg66->Dossierpcg66->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$Traitementpcg66->Personnepcg66->Dossierpcg66->Foyer->Dossier->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
                    $Traitementpcg66->Personnepcg66->Dossierpcg66->Foyer->Dossier->join( 'Detaildroitrsa', array( 'type' => 'LEFT OUTER' ) ),
                    $Traitementpcg66->Personnepcg66->Dossierpcg66->Foyer->Dossier->Detaildroitrsa->join( 'Detailcalculdroitrsa', array( 'type' => 'LEFT OUTER' ) )
				),
				'conditions' => $conditions
			);
			return $query;
		}
	}
?>