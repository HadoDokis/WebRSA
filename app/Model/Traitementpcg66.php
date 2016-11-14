<?php
	/**
	 * Code source de la classe Traitementpcg66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'AppModel', 'Model' );

	/**
	 * La classe Traitementpcg66 ...
	 *
	 * @package app.Model
	 */
	class Traitementpcg66 extends AppModel
	{
		public $name = 'Traitementpcg66';

		/**
		* Chemin relatif pour les modèles de documents .odt utilisés lors des
		* impressions. Utiliser %s pour remplacer par l'alias.
		*/
		public $modelesOdt = array(
			'PCG66/fichecalcul.odt',
		);

		public $actsAs = array(
//			'Autovalidate2',
//			'ValidateTranslate',
			'Formattable',
			'Enumerable' => array(
				'fields' => array(
					'hascourrier',
					'hasrevenu',
					'haspiecejointe',
					'hasficheanalyse',
					'eplaudition',
					'regime',
					'saisonnier',
					'aidesubvreint',
					'dureeecheance',
					'dureefinprisecompte',
					'recidive',
					'propodecision',
					'clos',
					'annule'
				)
			),
			'Gedooo.Gedooo',
            'Postgres.PostgresAutovalidate'
		);

		public $validate = array(
			'typecourrierpcg66_id' => array(
				array(
					'rule' => array( 'notEmptyIf', 'typetraitement', true, array( 'courrier' ) ),
					'message' => 'Champ obligatoire'
				)
			),
			'situationpdo_id' => array(
				array(
					'rule' => array( 'notEmptyIf', 'typetraitement', false, array( 'revenu' ) ),
					'message' => 'Champ obligatoire'
				)
			),
			'datereception' => array(
				'date' => array(
					'rule' => 'date',
					'required' => false,
					'allowEmpty' => true,
					'message' => 'Merci de rentrer une date valide'
				)
			),
			'datedepart' => array(
				'date' => array(
					'rule' => 'date',
					'required' => false,
					'allowEmpty' => true,
					'message' => 'Merci de rentrer une date valide'
				)
			),
			'daterevision' => array(
				'date' => array(
					'rule' => 'date',
					'required' => false,
					'allowEmpty' => true,
					'message' => 'Merci de rentrer une date valide'
				)
			),
			'dateecheance' => array(
				'date' => array(
					'rule' => 'date',
					'required' => false,
					'allowEmpty' => true,
					'message' => 'Merci de rentrer une date valide'
				)
			),
			'regime' => array(
				array(
					'rule' => 'notEmpty',
					'required' => false,
					'allowEmpty' => false,
					'message' => 'Champ obligatoire'
				)
			),
			'dtdebutactivite' => array(
				'date' => array(
					'rule' => 'date',
					'required' => false,
					'allowEmpty' => false,
					'message' => 'Merci de rentrer une date valide'
				)
			),
			'nrmrcs' => array(
				array(
					'rule' => 'alphaNumeric',
					'message' => 'Merci de saisir des valeurs alphanumériques',
					'required' => false,
					'allowEmpty' => false
				)
			),
			'dtdebutperiode' => array(
				'date' => array(
					'rule' => 'date',
					'required' => false,
					'allowEmpty' => false,
					'message' => 'Merci de rentrer une date valide'
				)
			),
			'datefinperiode' => array(
				'date' => array(
					'rule' => 'date',
					'required' => false,
					'allowEmpty' => false,
					'message' => 'Merci de rentrer une date valide'
				)
			),
			'dtdebutprisecompte' => array(
				'date' => array(
					'rule' => 'date',
					'required' => false,
					'allowEmpty' => false,
					'message' => 'Merci de rentrer une date valide'
				),
                'compareDates' => array(
                    'rule' => array( 'compareDates', 'datefinprisecompte', '<' ),
					'message' => 'La date de début de prise en compte doit être strictement inférieure à la date de fin'
                )
			),
			'datefinprisecompte' => array(
				'date' => array(
					'rule' => 'date',
					'required' => false,
					'allowEmpty' => false,
					'message' => 'Merci de rentrer une date valide'
				),
                'compareDates' => array(
					'rule' => array( 'compareDates', 'dtdebutprisecompte', '>' ),
					'message' => 'La date de fin de prise en compte doit être strictement supérieure à la date de début'
				)
			),
			'dtecheance' => array(
				'date' => array(
					'rule' => 'date',
					'required' => false,
					'allowEmpty' => false,
					'message' => 'Merci de rentrer une date valide'
				)
			),
			'chaffvnt' => array(
				array(
					'rule' => 'numeric',
					'required' => false,
					'allowEmpty' => false
				)
			),
			'chaffsrv' => array(
				array(
					'rule' => 'numeric',
					'required' => false,
					'allowEmpty' => false
				)
			),
			'benefoudef' => array(
				array(
					'rule' => 'numeric',
					'required' => false,
					'allowEmpty' => false
				)
			),
			'amortissements' => array(
				array(
					'rule' => 'numeric',
					'required' => false,
					'allowEmpty' => false
				)
			),
			'dureeecheance' => array(
				array(
					'rule' => 'notEmpty',
					'required' => false,
					'allowEmpty' => false
				)
			),
			'dureedepart' => array(
				array(
					'rule' => 'notEmpty',
					'required' => false,
					'allowEmpty' => false
				)
			),
			'compofoyerpcg66_id' => array(
				array(
					'rule' => array( 'notEmptyIf', 'eplaudition', true, array( '1' ) ),
					'message' => 'Champ obligatoire'
				)
			),
			'recidive' => array(
				array(
					'rule' => array( 'notEmptyIf', 'eplaudition', true, array( '1' ) ),
					'message' => 'Champ obligatoire'
				)
			),
			'propodecision' => array(
				array(
					'rule' => 'notEmpty',
					'required' => false,
					'allowEmpty' => false
				)
			),
			'commentairepropodecision' => array(
				array(
					'rule' => 'notEmpty',
					'required' => false,
					'allowEmpty' => false
				)
			),
            'typetraitement' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            )
		);

		public $belongsTo = array(
			'Personnepcg66' => array(
				'className' => 'Personnepcg66',
				'foreignKey' => 'personnepcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Descriptionpdo' => array(
				'className' => 'Descriptionpdo',
				'foreignKey' => 'descriptionpdo_id',
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
			),
			'Compofoyerpcg66' => array(
				'className' => 'Compofoyerpcg66',
				'foreignKey' => 'compofoyerpcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Situationpdo' => array(
				'className' => 'Situationpdo',
				'foreignKey' => 'situationpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Typecourrierpcg66' => array(
				'className' => 'Typecourrierpcg66',
				'foreignKey' => 'typecourrierpcg66_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Serviceinstructeur' => array(
				'className' => 'Serviceinstructeur',
				'foreignKey' => 'serviceinstructeur_id',
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
					'Fichiermodule.modele = \'Traitementpcg66\'',
					'Fichiermodule.fk_value = {$__cakeID__$}'
				),
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Dataimpression' => array(
				'className' => 'Dataimpression',
				'foreignKey' => 'fk_value',
				'dependent' => false,
				'conditions' => array(
					'Dataimpression.modele = \'Traitementpcg66\'',
				),
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Decisiontraitementpcg66' => array(
				'className' => 'Decisiontraitementpcg66',
				'foreignKey' => 'traitementpcg66_id',
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

		public $hasOne = array(
			'Saisinepdoep66' => array(
				'className' => 'Saisinepdoep66',
				'foreignKey' => 'traitementpcg66_id',
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
			'Modeletraitementpcg66' => array(
				'className' => 'Modeletraitementpcg66',
				'foreignKey' => 'traitementpcg66_id',
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

		public $hasAndBelongsToMany = array(
			'Courrierpdo' => array(
				'className' => 'Courrierpdo',
				'joinTable' => 'courrierspdos_traitementspcgs66',
				'foreignKey' => 'traitementpcg66_id',
				'associationForeignKey' => 'courrierpdo_id',
				'unique' => true,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'finderQuery' => '',
				'deleteQuery' => '',
				'insertQuery' => '',
				'with' => 'CourrierpdoTraitementpcg66'
			),
		);

		/**
		 * Modèles utilisés par ce modèle.
		 *
		 * @var array
		 */
		public $uses = array(
			'WebrsaTraitementpcg66'
		);

		/**
		 * Retourne l'id de la personne à laquelle est lié un enregistrement.
		 *
		 * @param integer $id L'id de l'enregistrement
		 * @return integer
		 */
		public function personneId( $id ) {
			$querydata = array(
				'fields' => array( "Personnepcg66.personne_id" ),
				'joins' => array(
					$this->join( 'Personnepcg66', array( 'type' => 'INNER' ) )
				),
				'conditions' => array(
					"{$this->alias}.id" => $id
				),
				'recursive' => -1
			);

			$result = $this->find( 'first', $querydata );

			if( !empty( $result ) ) {
				return $result['Personnepcg66']['personne_id'];
			}
			else {
				return null;
			}
		}

		/**
		 * Retourne l'id de la personne à laquelle est lié un enregistrement.
		 *
		 * @param integer $id L'id de l'enregistrement
		 * @return integer
		 */
		public function dossierpcg66Id( $id ) {
			$querydata = array(
				'fields' => array( "Personnepcg66.dossierpcg66_id" ),
				'joins' => array(
					$this->join( 'Personnepcg66', array( 'type' => 'INNER' ) )
				),
				'conditions' => array(
					"{$this->alias}.id" => $id
				),
				'recursive' => -1
			);

			$result = $this->find( 'first', $querydata );

			if( !empty( $result ) ) {
				return $result['Personnepcg66']['dossierpcg66_id'];
			}
			else {
				return null;
			}
		}

		/**
		 * Nettoyage des données en fonction du type de traitement, et si le type
		 * de traitement est "Fiche de calcul", en fonction du régime.
		 *
		 * @param array $options
		 * @return boolean
		 */
		public function beforeValidate( $options = array() ) {
			$return = parent::beforeValidate( $options );

			// Si le type de traitement est "Fiche de calcul"
			if( Hash::get( $this->data, "{$this->alias}.typetraitement" ) == 'revenu' ) {
				// Suppression des règles de validation des champs chaffvnt, chaffsrv, benefoudef; mise à null des champs lorsque c'est nécessaire en fonction du régime
				$regime = Hash::get( $this->data, "{$this->alias}.regime" );
				// Si 'fagri', on n'en garde aucun
				if( $regime == 'fagri' ) {
					unset( $this->validate['chaffvnt'], $this->validate['chaffsrv'], $this->validate['benefoudef'] );
					$this->data[$this->alias]['chaffvnt'] = null;
					$this->data[$this->alias]['chaffsrv'] = null;
					$this->data[$this->alias]['benefoudef'] = null;
				}
				// Si 'ragri' ou 'reel', on garde tout (chaffvnt, chaffsrv, benefoudef)
				// Si 'microbic' ou 'microbicauto', on garde chaffvnt, chaffsrv
				else if( in_array( $regime, array( 'microbic', 'microbicauto' ) ) ) {
					unset( $this->validate['benefoudef'] );
					$this->data[$this->alias]['benefoudef'] = null;
				}
				// Si 'microbnc', on garde chaffsrv
				else if( $regime == 'microbnc' ) {
					unset( $this->validate['chaffvnt'], $this->validate['benefoudef'] );
					$this->data[$this->alias]['chaffvnt'] = null;
					$this->data[$this->alias]['benefoudef'] = null;
				}
			}
			// Si le type de traitement est autre que "Fiche de calcul"
			else {
				unset( $this->validate['chaffvnt'], $this->validate['chaffsrv'], $this->validate['benefoudef'] );
				$this->data[$this->alias]['chaffvnt'] = null;
				$this->data[$this->alias]['chaffsrv'] = null;
				$this->data[$this->alias]['benefoudef'] = null;
			}

			return $return;
		}

		/**
		 * Effectue une jointure sur la personne en couple avec la Personne concernée par le traitement.
		 *
		 * @param array $query
		 * @return array
		 */
		public function joinCouple( $query ) {
			$replacements = array( 'Personne' => 'Personne2', 'Prestation' => 'prestations' );

			$query['fields'] = array_merge(
				$query['fields'],
				array_words_replace(
					$this->Personnepcg66->Personne->Foyer->Personne->fields(),
					$replacements
				)
			);

			$sq = $this->Personnepcg66->Personne->Prestation->sq(
				array(
					'fields' => array( 'prestations.personne_id' ),
					'conditions' => array(
						'Prestation.personne_id = Personne.id',
						'Prestation.rolepers' => array( 'DEM', 'CJT' ),
					),
					'contain' => false,
				)
			);

			$join = array_words_replace(
				$this->Personnepcg66->Personne->Foyer->join(
					'Personne',
					array(
						'type' => 'LEFT OUTER',
						'conditions' => array(
							"\"Personne\".\"id\" IN ( {$sq} )"
						)
					)
				),
				$replacements
			);
			$join['conditions'] = array(
				$join['conditions'],
				'Personne.id <> Personne2.id'
			);
			$query['joins'][] = $join;

			return $query;
		}
	}
?>