<?php
	class Dossiercov58 extends AppModel
	{
		public $name = 'Dossiercov58';

		public $actsAs = array(
			'Autovalidate2',
			'Containable',
			'Enumerable' => array(
				'fields' => array(
					'themecov58'
				)
			)
		);

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Themecov58' => array(
				'className' => 'Themecov58',
				'foreignKey' => 'themecov58_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasOne = array(
			'Propoorientationcov58' => array(
				'className' => 'Propoorientationcov58',
				'foreignKey' => 'dossiercov58_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Propoorientsocialecov58' => array(
				'className' => 'Propoorientsocialecov58',
				'foreignKey' => 'dossiercov58_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Propocontratinsertioncov58' => array(
				'className' => 'Propocontratinsertioncov58',
				'foreignKey' => 'dossiercov58_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Propononorientationprocov58' => array(
				'className' => 'Propononorientationprocov58',
				'foreignKey' => 'dossiercov58_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
		);


		public $hasMany = array(
			'Passagecov58' => array(
				'className' => 'Passagecov58',
				'foreignKey' => 'dossiercov58_id',
				'dependent' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			)
		);
		/**
		* FIXME -> aucun dossier en cours, pour certains thèmes:
		*		- CG 93
		*			* Nonrespectsanctionep93 -> ne débouche pas sur une orientation: '1reduction', '1maintien', '1sursis', '2suspensiontotale', '2suspensionpartielle', '2maintien'
		*			* Reorientationep93 -> peut déboucher sur une réorientation
		*		- CG 66
		*			* Defautinsertionep66 -> peut déboucher sur une orientation: 'suspensionnonrespect', 'suspensiondefaut', 'maintien', 'reorientationprofverssoc', 'reorientationsocversprof'
		*			* Saisinebilanparcoursep66 -> peut déboucher sur une réorientation
		*			* Saisinepdoep66 -> 'CAN', 'RSP' -> ne débouche pas sur une orientation
		* FIXME -> CG 93: s'il existe une procédure de relance, on veut faire signer un contrat,
					mais on veut peut-être aussi demander une réorientation.
		* FIXME -> doit-on vérifier si:
		* 			- la personne est soumise à droits et devoirs (oui)
		*			- la personne est demandeur ou conjoint RSA (oui) ?
		*			- le dossier est dans un état ouvert (non) ?
		*/

		public function ajoutPossible( $personne_id ) {
			$nbDossierscov = $this->find(
				'count',
				array(
// 					'conditions' => array(
// 						'Dossiercov58.personne_id' => $personne_id,
// 						'Cov58.etatcov' => array( 'cree', 'associe' )
// 					),
					'conditions' => array(
						'Dossiercov58.personne_id' => $personne_id,
						'OR' => array(
							'Passagecov58.etatdossiercov NOT' => array( 'traite', 'annule' ),
							'Passagecov58.etatdossiercov IS NULL'
						)
					),
					'joins' => array(
						$this->join( 'Passagecov58' )
					)
				)
			);
// debug($nbDossierscov);
			$nbPersonnes = $this->Personne->find(
				'count',
				array(
					'conditions' => array(
						'Personne.id' => $personne_id,
					),
					'joins' => array(
						array(
							'table'      => 'prestations',
							'alias'      => 'Prestation',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Personne.id = Prestation.personne_id',
								'Prestation.natprest = \'RSA\'',
								'Prestation.rolepers' => array( 'DEM', 'CJT' )
							)
						),
						array(
							'table'      => 'calculsdroitsrsa',
							'alias'      => 'Calculdroitrsa',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array(
								'Personne.id = Calculdroitrsa.personne_id',
								'Calculdroitrsa.toppersdrodevorsa' => '1'
							)
						),
						array(
							'table'      => 'foyers',
							'alias'      => 'Foyer',
							'type'       => 'INNER',
							'foreignKey' => false,
							'conditions' => array( 'Foyer.id = Personne.foyer_id' )
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
							'conditions' => array(
								'Situationdossierrsa.dossier_id = Dossier.id',
								'Situationdossierrsa.etatdosrsa' => array( 'Z', '2', '3', '4' )
							)
						),
					),
					'contain' => false
				)
			);

			return ( ( $nbDossierscov == 0 ) && ( $nbPersonnes == 1 ) );
		}

		/**
		 * Retourne un querydata permettant de trouver les dossiers COV en cours.
		 *
		 * @param integer $personne_id
		 * @param string|array $themes
		 * @return array
		 */
		public function qdDossiersNonFinalises( $personne_id, $themes = null ) {
			$qdSubquery = array(
				'fields' => array(
					'passagescovs58.dossiercov58_id'
				),
				'alias' => 'passagescovs58',
				'conditions' => array(
					'dossierscovs58.personne_id' => $personne_id,
					'passagescovs58.etatdossiercov' => array( 'traite', 'annule' )
				),
				'joins' => array(
					array(
						'table' => 'dossierscovs58',
						'alias' => 'dossierscovs58',
						'type' => 'INNER',
						'conditions' => array(
							'passagescovs58.dossiercov58_id = dossierscovs58.id'
						)
					),
					array(
						'table' => 'covs58',
						'alias' => 'covs58',
						'type' => 'INNER',
						'conditions' => array(
							'passagescovs58.cov58_id = covs58.id'
						)
					)
				)
			);

			$themes = (array)$themes;

			if( !empty( $themes ) ) {
				$qdSubquery['conditions']['dossierscovs58.themecov58'] = $themes;
			}

			$querydata = array(
				'conditions' => array(
					'Dossiercov58.id NOT IN ( '.$this->Passagecov58->sq( $qdSubquery ).' )',
					'Dossiercov58.personne_id' => $personne_id
				),
				'joins' => array(
					$this->join( 'Passagecov58', array( 'type' => 'LEFT OUTER' ) )
				),
				'contain' => false
			);

			if( !empty( $themes ) ) {
				$querydata['conditions']['Dossiercov58.themecov58'] = $themes;

				foreach( $themes as $theme ) {
					$modelDecisionTheme = Inflector::classify( Inflector::singularize( "decisions{$theme}" ) );
					$querydata['joins'][] = $this->Passagecov58->join( $modelDecisionTheme, array( 'type' => 'LEFT OUTER' ) );
				}
			}

			return $querydata;
		}
	}
?>