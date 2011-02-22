<?php
	/**
	* Saisines d'EP pour les bilans de parcours pour le conseil général du
	* département 66.
	*
	* Une saisine regoupe plusieurs thèmes des EPs pour le CG 66.
	*
	* PHP versions 5
	*
	* @package       app
	* @subpackage    app.app.models
	*/

	class Radiepoleemploiep93 extends AppModel
	{
		public $name = 'Radiepoleemploiep93';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
// 			'Formattable' => array(
// 				'suffix' => array(
// 					'structurereferente_id'
// 				)
// 			),
		);

		public $belongsTo = array(
			'Dossierep' => array(
				'className' => 'Dossierep',
				'foreignKey' => 'dossierep_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Historiqueetatpe' => array(
				'className' => 'Historiqueetatpe',
				'foreignKey' => 'historiqueetatpe_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);

		public $hasMany = array(
			'Decisionradiepoleemploiep93' => array(
				'className' => 'Decisionradiepoleemploiep93',
				'foreignKey' => 'radiepoleemploiep93_id',
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

		/**
		*
		*/
		/*public function containQueryData() {
			return array(
				'Defautinsertionep66' => array(
					'Nvsrepreorient66'=>array(
						'Typeorient',
						'Structurereferente'
					),
				)
			);
		}*/

		/**
		* FIXME: et qui n'ont pas de dossier EP en cours de traitement pour cette thématique
		* FIXME: et qui ne sont pas passés en EP pour ce motif dans un délai de moins de 1 mois (paramétrable)
		*/

		protected function _qdSelection() {
			$queryData = array(
				'fields' => array(
					'Personne.id',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Personne.nir',
					'Historiqueetatpe.id',
					'Historiqueetatpe.informationpe_id',
					'Historiqueetatpe.etat',
					'Historiqueetatpe.date',
				),
				'contain' => false,
				'joins' => array(
					array(
						'table'      => 'prestations', // FIXME:
						'alias'      => 'Prestation',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Prestation.personne_id',
							'Prestation.natprest' => 'RSA',
							'Prestation.rolepers' => array( 'DEM', 'CJT' ),
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
						'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
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
					array(
						'table'      => 'calculsdroitsrsa', // FIXME:
						'alias'      => 'Calculdroitrsa',
						'type'       => 'INNER',
						'foreignKey' => false,
						'conditions' => array(
							'Personne.id = Calculdroitrsa.personne_id',
							'Calculdroitrsa.toppersdrodevorsa' => '1',
						)
					),
				),
				'conditions' => array(
					'Personne.id NOT IN (
						SELECT
								dossierseps.personne_id
							FROM dossierseps
							WHERE
								dossierseps.personne_id = Personne.id
								AND dossierseps.etapedossierep IN ( \'cree\', \'seance\', \'decisionep\', \'decisioncg\' )
					)',
					'Personne.id NOT IN (
						SELECT
								dossierseps.personne_id
							FROM dossierseps
								INNER JOIN seanceseps ON (
									dossierseps.seanceep_id = seanceseps.id
								)
							WHERE
								dossierseps.personne_id = Personne.id
								AND dossierseps.etapedossierep = \'traite\'
								AND dossierseps.themeep = \'radiespoleemploieps93\'
								AND seanceseps.dateseance >= \''.date( 'Y-m-d', strtotime( '-2 mons' ) ).'\'
					)'
				) // FIXME: paramétrage
			);

			return $queryData;
		}

		/**
		*
		*/

		public function qdRadies() {
			// FIXME: et qui ne sont pas passés dans une EP pour ce motif depuis au moins 1 mois (?)
			$queryData = $this->_qdSelection();
			$qdRadies = $this->Historiqueetatpe->Informationpe->qdRadies();
			$queryData['joins'] = array_merge( $queryData['joins'] ,$qdRadies['joins'] );
			$queryData['conditions'] = array_merge( $queryData['conditions'] ,$qdRadies['conditions'] );
			$queryData['order'] = $qdRadies['order'];
			
			return $queryData;
		}
	}
?>