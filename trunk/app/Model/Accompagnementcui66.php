<?php	
	/**
	 * Code source de la classe Accompagnementcui66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Accompagnementcui66 ...
	 *
	 * @package app.Model
	 */
	class Accompagnementcui66 extends AppModel
	{
		public $name = 'Accompagnementcui66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate2',
			'Containable',
			'Enumerable' => array(
				'fields' => array(
					'typeaccompagnementcui66',
					'objectifimmersion'
				)
			),
			'Formattable' => array(
				'suffix' => array( 'structurereferente_id', 'referent_id', 'prestataire_id', 'metieraffectation_id' ),
			),
			'Gedooo.Gedooo'
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
				'foreignKey' => 'user_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
		
		public $validate = array(
			'typeaccompagnementcui66' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'nomentaccueil' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'typeaccompagnementcui66', true, array( 'immersion' ) ),
					'message' => 'Champ obligatoire',
				)
			),
			'objectifimmersion' => array(
				'notEmptyIf' => array(
					'rule' => array( 'notEmptyIf', 'typeaccompagnementcui66', true, array( 'immersion' ) ),
					'message' => 'Champ obligatoire',
				)
			),
		);
		
		
		/**
		* Chemin relatif pour les modèles de documents .odt utilisés lors des
		* impressions. Utiliser %s pour remplacer par l'alias.
		*/
		public $modelesOdt = array(
			'CUI/periodeimmersion.odt',
		);

		/**
		 *
		 * @param integer $id
		 * @param integer $user_id
		 * @return array
		 */
		public function getDataForPdf( $id, $user_id ) {
			$accompagnementcui66 = $this->find(
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
						'Accompagnementcui66.id' => $id,
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
			$accompagnementcui66 = Set::merge( $accompagnementcui66, $user );
			
			return $accompagnementcui66;
		}
		
		/**
		 * Retourne le PDF de notification du CUI.
		 *
		 * @param integer $id L'id du CUI pour lequel on veut générer l'impression
		 * @return string
		 */
		public function getDefaultPdf( $id, $user_id ) {
			
			$accompagnementcui66 = $this->getDataForPdf( $id, $user_id );
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

			return $this->ged(
				$accompagnementcui66,
				'CUI/periodeimmersion.odt',
				false,
				$options
			);
		}
	}
?>