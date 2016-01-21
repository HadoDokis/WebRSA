<?php
	/**
	 * Code source de la classe Nonoriente66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Nonoriente66 ...
	 *
	 * @package app.Model
	 */
	class Nonoriente66 extends AppModel
	{
		public $name = 'Nonoriente66';

		public $actsAs = array(
			'Conditionnable',
			'Gedooo.Gedooo',
			'Enumerable' => array(
				'fields' => array(
					'reponseallocataire' => array( 'type' => 'no' ),
					'haspiecejointe'
				)
			),
			'Formattable'
		);

		public $belongsTo = array(
			'Historiqueetatpe' => array(
				'className' => 'Historiqueetatpe',
				'foreignKey' => 'historiqueetatpe_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Orientstruct' => array(
				'className' => 'Orientstruct',
				'foreignKey' => 'orientstruct_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
		);


		public $hasMany = array(
			'Fichiermodule' => array(
				'className' => 'Fichiermodule',
				'foreignKey' => false,
				'dependent' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Nonoriente66\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
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
		 * Liste des modeles odt utilisé par ce Modele
		 * 
		 * @var array
		 */
		public $modelesOdt = array(
			'default' => 'Orientation/questionnaireorientation66.odt'
		);
		
		/**
		 * Retourne les données nécessaires à l'impression du questionnaire pour les non orientés du CG66
		 * Les données contiennent les informations de la personne
		 *
		 * @param integer $id
		 * @param integer $user_id
		 * @return array
		 */
		public function getDataForPdf() {
			$querydata = array(
				'fields' => array_merge(
					$this->Personne->fields(),
					$this->Personne->Foyer->Adressefoyer->Adresse->fields(),
					$this->Personne->Foyer->fields(),
					$this->Personne->Foyer->Dossier->fields()
				),
				'joins' => array(
					$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					$this->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
					$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
					$this->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
				),
				'conditions' => array(
					'Adressefoyer.id IN ( '.$this->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
				),
				'contain' => false
			);
			return $querydata;
		}
		
		/**
		 * Retourne le PDF par défaut généré par les appels aux méthodes getDataForPdf, modeleOdt et
		 * à la méthode ged du behavior Gedooo
		 * Le courrier généré est le questionnaire à destination des allocataires non orientés et non inscrits au PE
		 *
		 * @param type $id Id de la personne
		 * @param type $user_id Id de l'utilisateur connecté
		 * @return string PDF
		 */
		public function getDefaultPdf( $id, $user_id ) {
			$options = array(
				'Personne' => array(
					'qual' => ClassRegistry::init( 'Option' )->qual()
				)
			);

			$querydata = $this->getDataForPdf();

			$querydata = Set::merge(
				$querydata,
				array(
					'conditions' => array(
						'Personne.id' => $id
					)
				)
			);
			$personne = $this->Personne->find( 'first', $querydata );

			/// Récupération de l'utilisateur
			$user = ClassRegistry::init( 'User' )->find(
				'first',
				array(
					'conditions' => array(
						'User.id' => $user_id
					),
					'contain' => false
				)
			);
			$personne['User'] = $user['User'];

			if( empty( $personne ) ) {
				$this->cakeError( 'error404' );
			}

			return $this->ged(
				$personne,
				$this->modelesOdt['default'],
				false,
				$options
			);
		}
		
		/**
		 * Fonction permettant d'enregistrer la date du jour de l'impression du courrier envoyé
		 * aux allocataires ne possédant pas encore d'orientation
		 * 
		 * @param integer $personne_id
		 * @param integer $user_id
		 * @return boolean
		 */
		public function saveImpression( $personne_id, $user_id ) {
			$nonoriente66 = array(
				'Nonoriente66' => array(
					'personne_id' => $personne_id,
					'dateimpression' => date( 'Y-m-d' ),
					'orientstruct_id' => null,
					'historiqueetatpe_id' => null,
					'origine' => 'notisemploi',
					'user_id' => $user_id
				)
			);

			$this->create( $nonoriente66 );
			return $this->save();
		}
		
		/**
		 * Renvoi le chemin vers le document odt en fonction de data
		 * 
		 * @param array $data
		 * @return string
		 */
		public function modeleOdt( $data = array() ) {
			return $this->modelesOdt['default'];
		}
	}
?>