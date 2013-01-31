<?php
	/**
	 * Fichier source de la classe Critererdv.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Sanitize', 'Utility' );

	/**
	 * La classe Critererdv s'occupe du moteur de recherche par rendez-vous (CG 58, 66 et 93).
	 *
	 * @package app.Model
	 */
	class Critererdv extends AppModel
	{
		public $name = 'Critererdv';

		public $useTable = false;

		public $actsAs = array( 'Conditionnable' );

		/**
		 * Traitement du formulaire de recherche par rendez-vous.
		 *
		 * @param array $mesCodesInsee La liste des codes INSEE à laquelle est lié l'utilisateur
		 * @param boolean $filtre_zone_geo L'utilisateur est-il limité au niveau des zones géographiques ?
		 * @param array $criteresrdv Critères du formulaire de recherche
		 * @return string
		 */
		public function search( $mesCodesInsee, $filtre_zone_geo, $criteresrdv, $conditionStructure = array() ) { //FIXME Arnaud
			/// Conditions de base
			$conditions = array();

			/// Critères
			$statutrdv_id = Set::extract( $criteresrdv, 'Critererdv.statutrdv_id' );

			$typerdv_id = Set::extract( $criteresrdv, 'Critererdv.typerdv_id' );
			$structurereferente_id = Set::extract( $criteresrdv, 'Critererdv.structurereferente_id' );
			$referent_id = Set::extract( $criteresrdv, 'Critererdv.referent_id' );
			$permanence_id = Set::extract( $criteresrdv, 'Critererdv.permanence_id' );


			/// Filtre zone géographique
			$conditions[] = $this->conditionsZonesGeographiques( $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsAdresse( $conditions, $criteresrdv, $filtre_zone_geo, $mesCodesInsee );
			$conditions = $this->conditionsPersonneFoyerDossier( $conditions, $criteresrdv );
			$conditions = $this->conditionsDernierDossierAllocataire( $conditions, $criteresrdv );

			/// Critères sur le RDV - date de demande
			if( isset( $criteresrdv['Critererdv']['daterdv'] ) && !empty( $criteresrdv['Critererdv']['daterdv'] ) ) {
				$valid_from = ( valid_int( $criteresrdv['Critererdv']['daterdv_from']['year'] ) && valid_int( $criteresrdv['Critererdv']['daterdv_from']['month'] ) && valid_int( $criteresrdv['Critererdv']['daterdv_from']['day'] ) );
				$valid_to = ( valid_int( $criteresrdv['Critererdv']['daterdv_to']['year'] ) && valid_int( $criteresrdv['Critererdv']['daterdv_to']['month'] ) && valid_int( $criteresrdv['Critererdv']['daterdv_to']['day'] ) );
				if( $valid_from && $valid_to ) {
					$conditions[] = 'Rendezvous.daterdv BETWEEN \''.implode( '-', array( $criteresrdv['Critererdv']['daterdv_from']['year'], $criteresrdv['Critererdv']['daterdv_from']['month'], $criteresrdv['Critererdv']['daterdv_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresrdv['Critererdv']['daterdv_to']['year'], $criteresrdv['Critererdv']['daterdv_to']['month'], $criteresrdv['Critererdv']['daterdv_to']['day'] ) ).'\'';
				}
			}
			/// Statut RDV
			if( !empty( $statutrdv_id ) ) {
				$conditions[] = 'Rendezvous.statutrdv_id = \''.Sanitize::clean( $statutrdv_id, array( 'encode' => false ) ).'\'';
			}


			/// Structure référente
			if( !empty( $structurereferente_id ) ) {
				$conditions[] = 'Rendezvous.structurereferente_id = \''.Sanitize::clean( $structurereferente_id, array( 'encode' => false ) ).'\'';
			}

			/// Référent
			if( !empty( $referent_id ) ) {
				$conditions[] = 'Rendezvous.referent_id = \''.Sanitize::clean( suffix( $referent_id ), array( 'encode' => false ) ).'\'';
			}

			/// Permanence
			if( !empty( $permanence_id ) ) {
				$conditions[] = 'Rendezvous.permanence_id = \''.Sanitize::clean( $permanence_id, array( 'encode' => false ) ).'\'';
			}


			/// Objet du rendez vous
			if( !empty( $typerdv_id ) ) {
				$conditions[] = 'Rendezvous.typerdv_id = \''.Sanitize::clean( $typerdv_id, array( 'encode' => false ) ).'\'';
			}
			/// Requête
			$this->Dossier = ClassRegistry::init( 'Dossier' );
			
			
			// On conditionne l'affichage des RDVs selon la structure référente liée au RDV
			// Si la structure de l'utilisateur connecté est différente de celle du RDV, on ne l'affiche pas.
			if( Configure::read( 'Cg.departement' ) == 93 ) {
				$conditions[] = $conditionStructure;
			}
			// Arnaud

			$querydata = array(
				'fields' => array(
					'"Rendezvous"."id"',
					'"Rendezvous"."personne_id"',
					'"Rendezvous"."referent_id"',
					'"Rendezvous"."permanence_id"',
					'"Rendezvous"."statutrdv_id"',
					'"Rendezvous"."structurereferente_id"',
					'"Rendezvous"."typerdv_id"',
					'"Rendezvous"."daterdv"',
					'"Rendezvous"."heurerdv"',
					'"Rendezvous"."objetrdv"',
					'"Rendezvous"."commentairerdv"',
					'"Dossier"."numdemrsa"',
					'"Dossier"."matricule"',
					'"Adresse"."locaadr"',
					'"Adresse"."numcomptt"',
					'"Personne"."nom"',
					'"Personne"."prenom"',
					'"Personne"."nir"',
					'"Personne"."nomcomnai"',
					'"Personne"."dtnai"',
					'"Referent"."qual"',
					'"Referent"."nom"',
					'"Referent"."prenom"',
					'"Structurereferente"."lib_struc"',
					'"Prestation"."rolepers"'
				),
				'recursive' => -1,
				'joins' => array(
					array(
						'table'      => 'personnes',
						'alias'      => 'Personne',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Rendezvous.personne_id = Personne.id' ),
					),
					array(
						'table'      => 'structuresreferentes',
						'alias'      => 'Structurereferente',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array( 'Structurereferente.id = Rendezvous.structurereferente_id' ),
					),
					array(
						'table'      => 'typesrdv',
						'alias'      => 'Typerdv',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Typerdv.id = Rendezvous.typerdv_id' ),
					),
					array(
						'table'      => 'statutsrdvs',
						'alias'      => 'Statutrdv',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Statutrdv.id = Rendezvous.statutrdv_id' ),
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
							// FIXME: c'est un hack pour n'avoir qu'une seule adresse de range 01 par foyer!
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
					$this->Dossier->join( 'Situationdossierrsa', array( 'type' => 'INNER' ) ),
					array(
						'table'      => 'referents',
						'alias'      => 'Referent',
						'type'       => 'LEFT OUTER',
						'foreignKey' => false,
						'conditions' => array( 'Referent.id = Rendezvous.referent_id' )
					)
				),
				'order' => array( '"Rendezvous"."daterdv" ASC' ),
				'conditions' => $conditions
			);

			return $querydata;
		}
	}
?>