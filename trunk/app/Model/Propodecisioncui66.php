<?php	
	/**
	 * Code source de la classe Propodecisioncui66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Propodecisioncui66 ...
	 *
	 * @package app.Model
	 */
	class Propodecisioncui66 extends AppModel
	{
		public $name = 'Propodecisioncui66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate2',
			'Containable',
			'Enumerable' => array(
				'fields' => array(
					'propositioncui',
					'propositioncuielu',
					'propositioncuireferent',
					'isaviselu',
					'isavisreferent'
				)
			),
			'Formattable'
		);
		
		public $validate = array(
			'propositioncui' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'datepropositioncui' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'propositioncuielu' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'isaviselu', true, array( '1' ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'datepropositioncuielu' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'isaviselu', true, array( '1' ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'propositioncuireferent' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'isavisreferent', true, array( '1' ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'datepropositioncuireferent' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'isavisreferent', true, array( '1' ) ),
					'message' => 'Champ obligatoire',
				)
			),
		);
		
		public $belongsTo = array(
			'Cui' => array(
				'className' => 'Cui',
				'foreignKey' => 'cui_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'User' => array(
				'className' => 'User',
				'foreignKey' => 'cui_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
		
	
		/**
		* Chemin relatif pour les modèles de documents .odt utilisés lors des
		* impressions. Utiliser %s pour remplacer par l'alias.
		*/
		public $modelesOdt = array(
			'CUI/notifelucui.odt',
		);
		
		
		/**
		 *
		 * @param integer $id
		 * @param integer $user_id
		 * @return array
		 */
		public function getDataForPdf( $id, $user_id ) {
			$propodecisioncui = $this->find(
				'first',
				array(
					'fields' => array_merge(
						$this->fields(),
						$this->Cui->fields(),
						$this->Cui->Personne->fields(),
						$this->Cui->Referent->fields(),
						$this->Cui->Structurereferente->fields(),
						$this->Cui->Personne->Foyer->fields(),
						$this->Cui->Personne->Foyer->Dossier->fields(),
						$this->Cui->Personne->Foyer->Adressefoyer->Adresse->fields()
					),
					'joins' => array(
						$this->join( 'Cui', array( 'type' => 'INNER' ) ),
						$this->Cui->join( 'Personne', array( 'type' => 'INNER' ) ),
						$this->Cui->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ),
						$this->Cui->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) ),
						$this->Cui->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$this->Cui->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
						$this->Cui->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$this->Cui->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					),
					'conditions' => array(
						'Propodecisioncui66.id' => $id,
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ( '.$this->Cui->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
						)
					),
					'contain' => false
				)
			);
			
			$user = $this->User->find(
				'first',
				array(
					'conditions' => array(
						'User.id' => $user_id
					),
					'contain' => false
				)
			);
			$propodecisioncui = Set::merge( $propodecisioncui, $user );

			return $propodecisioncui;
		}
		
		/**
		 * Retourne le PDF de notification du CUI.
		 *
		 * @param integer $id L'id du CUI pour lequel on veut générer l'impression
		 * @param integer $user_id L'id de l'utilisateur générant l'impression
		 * @return string
		 */
		public function getNotifelucuiPdf( $id, $user_id ) {
		
			$propodecisioncui = $this->getDataForPdf( $id, $user_id );

			///Traduction pour les données de la Personne/Contact/Partenaire/Référent
			$Option = ClassRegistry::init( 'Option' );
			$options = array(
				'Adresse' => array(
					'typevoie' => $Option->typevoie()
				),
				'Personne' => array(
					'qual' => $Option->qual()
				),
				'Referent' => array(
					'qual' => $Option->qual()
				),
				'Structurereferente' => array(
					'type_voie' => $Option->typevoie()
				),
				'Type' => array(
					'voie' => $Option->typevoie()
				),
			);

// debug( $propodecisioncui );
// die();
			return $this->ged(
				$propodecisioncui,
				$modelesOdt,
				false,
				$options
			);
		}
	}
?>