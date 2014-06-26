<?php
	/**
	 * Code source de la classe Decisioncui66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Decisioncui66 ...
	 *
	 * @package app.Model
	 */
	class Decisioncui66 extends AppModel
	{
		public $name = 'Decisioncui66';

		public $recursive = -1;

		public $actsAs = array(
			'Pgsqlcake.PgsqlAutovalidate',
			'Containable',
			'Enumerable',
			'Formattable',
			'Gedooo.Gedooo',
			'ModelesodtConditionnables' => array(
				66 => array(
					'CUI/%s/notifbenef_accord_cie.odt',
					'CUI/%s/notifbenef_accord_cae.odt',
					'CUI/%s/decisionelu_accord_cae.odt',
					'CUI/%s/decisionelu_accord_cie.odt',
					'CUI/%s/notifemployeur_accord_cie.odt',
					'CUI/%s/notifemployeur_accord_cae.odt',
					'CUI/%s/notifbenef_refus_cie.odt',
					'CUI/%s/notifbenef_refus_cae.odt',
					'CUI/%s/decisionelu_refus_cae.odt',
					'CUI/%s/decisionelu_refus_cie.odt',
					'CUI/%s/notifemployeur_refus_cie.odt',
					'CUI/%s/notifemployeur_refus_cae.odt'
				)
			),
			'StorablePdf' => array(
				'afterSave' => 'deleteAll'
			)
		);

        public $validate = array(
            'datedecisioncui' => array(
                'notEmptyIf' => array(
                    'rule' => array( 'notEmptyIf', 'decisioncui', false, array( 'enattente' ) ),
                    'message' => 'Veuillez saisir une date valide'
                )
            )
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

		public $hasMany = array(
			'Fichiermodule' => array(
				'className' => 'Fichiermodule',
				'foreignKey' => false,
				'dependent' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Decisioncui66\'',
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
		 *
		 * @param integer $id
		 * @param integer $user_id
		 * @return array
		 */
		public function getDataForPdf( $id, $user_id ) {
			$decisioncui66 = $this->find(
				'first',
				array(
					'fields' => array_merge(
						$this->fields(),
						$this->Cui->fields(),
// 						$this->Cui->Propodecisioncui66->fields(),
						$this->Cui->Personne->fields(),
						$this->Cui->Referent->fields(),
						$this->Cui->Structurereferente->fields(),
						$this->Cui->Personne->Foyer->fields(),
						$this->Cui->Personne->Foyer->Dossier->fields(),
						$this->Cui->Personne->Foyer->Adressefoyer->Adresse->fields()
					),
					'joins' => array(
						$this->join( 'Cui', array( 'type' => 'INNER' ) ),
// 						$this->Cui->join( 'Propodecisioncui66', array( 'type' => 'INNER' ) ),
						$this->Cui->join( 'Personne', array( 'type' => 'INNER' ) ),
						$this->Cui->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ),
						$this->Cui->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) ),
						$this->Cui->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$this->Cui->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
						$this->Cui->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$this->Cui->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					),
					'conditions' => array(
						'Decisioncui66.id' => $id,
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
			$decisioncui66 = Set::merge( $decisioncui66, $user );

			$dernierePropodecisioncui66 = $this->Cui->Propodecisioncui66->find(
				'first',
				array(
					'conditions' => array(
						'Propodecisioncui66.cui_id' => $decisioncui66['Cui']['id']
					),
					'contain' => false,
					'order' => array( 'Propodecisioncui66.datepropositioncui DESC' ),
					'limit' => 1
				)
			);
			$decisioncui66 = Set::merge( $decisioncui66, $dernierePropodecisioncui66 );


			return $decisioncui66;
		}

		/**
		 * Retourne le PDF de notification du CUI.
		 *
		 * @param integer $id L'id du CUI pour lequel on veut générer l'impression
		 * @return string
		 */
		public function getDefaultPdf( $id, $destinataire, $user_id ) {

			$decisioncui66 = $this->getDataForPdf( $id, $user_id );
			///Traduction pour les données de la Personne/Contact/Partenaire/Référent
			$Option = ClassRegistry::init( 'Option' );
			$options = array(
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
				'type' => array(
					'voie' => $Option->typevoie()
				),
			);

			// Type de cui
			$secteurcui = Set::classicExtract( $decisioncui66, 'Cui.secteur' );
			$typedecision = Set::classicExtract( $decisioncui66, 'Decisioncui66.decisioncui' );

			$modeleodt = '';
			if( $destinataire == 'elu' ) {
				$modeleodt = 'decisionelu';
			}
			else if( $destinataire == 'benef' ) {
				$modeleodt = 'notifbenef';
			}
			else if( $destinataire == 'employeur' ) {
				$modeleodt = 'notifemployeur';
			}

			if( !empty( $modeleodt ) && !empty( $typedecision ) && !empty( $secteurcui ) ) {
				$modeleDocument = "{$modeleodt}_{$typedecision}_{$secteurcui}";
			}
			else{
				return false;
			}
// debug($modeleDocument);
// debug($decisioncui66);
//
// die();
			return $this->ged(
				$decisioncui66,
				"CUI/Decisioncui66/{$modeleDocument}.odt",
				false,
				$options
			);
		}

		/**
		 * Retourne l'id de la personne à laquelle est lié un enregistrement.
		 *
		 * @param integer $id L'id de l'enregistrement
		 * @return integer
		 */
		public function personneId( $id ) {
			$querydata = array(
				'fields' => array( "Cui.personne_id" ),
				'joins' => array(
					$this->join( 'Cui', array( 'type' => 'INNER' ) )
				),
				'conditions' => array(
					"{$this->alias}.id" => $id
				),
				'recursive' => -1
			);

			$result = $this->find( 'first', $querydata );

			if( !empty( $result ) ) {
				return $result['Cui']['personne_id'];
			}
			else {
				return null;
			}
		}
	}
?>