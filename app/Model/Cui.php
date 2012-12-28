<?php
	/**
	 * Fichier source de la classe Cui.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */
	App::uses( 'Sanitize', 'Utility' );

	/**
	 * La classe Cui est la classe de base concernant les CUIs (CG 58, 66 et 93).
	 *
	 * @package app.Model
	 */
	class Cui extends AppModel
	{
		public $name = 'Cui';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'haspiecejointe' => array( 'domain' => 'cui' ),
					'secteur' => array( 'domain' => 'cui' ),
					'positioncui66' => array( 'domain' => 'cui' ),
					'isaci' => array( 'domain' => 'cui' ),
					'statutemployeur' => array( 'domain' => 'cui' ),
					'niveauformation' => array( 'domain' => 'cui' ),
					'avenant' => array( 'domain' => 'cui' ),
					'avenantcg' => array( 'domain' => 'cui' ),
					'orgrecouvcotis' => array( 'domain' => 'cui' ),
					'formation' => array( 'domain' => 'cui' ),
					'orgapayeur' => array( 'domain' => 'cui' ),
					'isadresse2' => array( 'domain' => 'cui' ),
					'iscie' => array( 'domain' => 'cui' ),
					'dureesansemploi' => array( 'domain' => 'cui' ),
					'isinscritpe' => array( 'domain' => 'cui' ),
					'dureeinscritpe' => array( 'type' => 'dureesansemploi', 'domain' => 'cui' ),
					'isbeneficiaire' => array( 'domain' => 'cui', 'type' => 'beneficiaire' ),
					'rsadeptmaj' => array( 'domain' => 'cui' ),
					'dureebenefaide' => array( 'type' => 'dureesansemploi', 'domain' => 'cui' ),
					'isbeneficiaire' => array( 'domain' => 'cui' ),
					'handicap' => array( 'domain' => 'cui' ),
					'typecontrat' => array( 'domain' => 'cui' ),
					'modulation' => array( 'domain' => 'cui' ),
					'isaas' => array( 'domain' => 'cui' ),
					'remobilisation' => array( 'domain' => 'cui', 'type' => 'initiative' ),
					'aidereprise' => array( 'domain' => 'cui', 'type' => 'initiative' ),
					'elaboprojetpro' => array( 'domain' => 'cui', 'type' => 'initiative' ),
					'evaluation' => array( 'domain' => 'cui', 'type' => 'initiative' ),
					'aiderechemploi' => array( 'domain' => 'cui', 'type' => 'initiative' ),
					'adaptation' => array( 'domain' => 'cui', 'type' => 'initiative' ),
					'remiseniveau' => array( 'domain' => 'cui', 'type' => 'initiative' ),
					'prequalification' => array( 'domain' => 'cui', 'type' => 'initiative' ),
					'nouvellecompetence' => array( 'domain' => 'cui', 'type' => 'initiative' ),
					'formqualif' => array( 'domain' => 'cui', 'type' => 'initiative' ),
					'isperiodepro' => array( 'domain' => 'cui' ),
					'validacquis' => array( 'domain' => 'cui' ),
					'iscae' => array( 'domain' => 'cui' ),
					'financementexclusif' => array( 'domain' => 'cui' ),
					'orgapayeur' => array( 'domain' => 'cui' ),
					'decisioncui' => array( 'domain' => 'cui' )
				)
			),
			'Formattable' => array(
				'suffix' => array( 'structurereferente_id', 'referent_id', 'prestataire_id', 'metieraffectation_id', 'metieremploipropose_id' ),
			),
// 			'Autovalidate2',
			'Gedooo.Gedooo',
			'Pgsqlcake.PgsqlAutovalidate'
		);

		/**
		* Chemin relatif pour les modèles de documents .odt utilisés lors des
		* impressions. Utiliser %s pour remplacer par l'alias.
		*/
		public $modelesOdt = array(
			'CUI/cui.odt',
		);



		public $validate = array(
			'secteur' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),/*
			'numconvention' => array(
				array(
					'rule' => 'isUnique',
					'message' => 'Ce numéro existe déjà'
				),
				array(
					'rule' => 'between',
					'message' => 'Le numéro SIRET est composé de 14 chiffres'
				)
			),*/
			'datedebprisecharge' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'datefinprisecharge' => array(
				'notEmpty' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			/*'nomemployeur' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'statutemployeur' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),

			'orgrecouvcotis' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'typevoieemployeur' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'numvoieemployeur' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'nomvoieemployeur' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'codepostalemployeur' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'villeemployeur' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'numtelemployeur' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				),
				array(
					'rule' => array( 'between', 10, 14 ),
					'message' => 'Le numéro de téléphone est composé de 10 chiffres'
				)
			),
			'emailemployeur' => array(
				'rule' => 'email',
				'message' => 'Email non valide',
				'allowEmpty' => true
			),
			'niveauformation' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'dureesansemploi' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'isisncritpe' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'typecontrat' => array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
			),
			'ass' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'rsadept' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'rsadeptmaj' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'aah' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'ata' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'dureebenefaide' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'handicap'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'dateembauche'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'codeemploi'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'salairebrut'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'dureehebdosalarieheure'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'dureehebdosalarieminute'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'modulation'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'fonctiontuteur'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'isaas'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'dureecollhebdominute'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'dureecollhebdoheure'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'remobilisation'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'aidereprise'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'elaboprojetpro'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'evaluation'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'aiderechemploi'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'adaptation'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'remiseniveau'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'prequalification'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'nouvellecompetence'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'formqualif'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'formation'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'isperiodepro'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'niveauqualif' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'validacquis' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'datedebprisecharge' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'datefinprisecharge' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'dureehebdoretenueheure' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'dureehebdoretenueminute' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'opspeciale' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'tauxfixe' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'orgapayeur' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			)*/
		);

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Referent' => array(
				'className' => 'Referent',
				'foreignKey' => 'referent_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Structurereferente' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'structurereferente_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Orgsuivi' => array(
				'className' => 'Structurereferente',
				'foreignKey' => 'orgsuivi_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Prestataire' => array(
				'className' => 'Referent',
				'foreignKey' => 'prestataire_id',
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
			'Serviceinstructeur' => array(
				'className' => 'Serviceinstructeur',
				'foreignKey' => 'serviceinstructeur_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasMany = array(
			'Periodeimmersion' => array(
				'className' => 'Periodeimmersion',
				'foreignKey' => 'cui_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Fichiermodule' => array(
				'className' => 'Fichiermodule',
				'foreignKey' => false,
				'dependent' => false,
				'conditions' => array(
					'Fichiermodule.modele = \'Cui\'',
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
			'Propodecisioncui66' => array(
				'className' => 'Propodecisioncui66',
				'foreignKey' => 'cui_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Decisioncui66' => array(
				'className' => 'Decisioncui66',
				'foreignKey' => 'cui_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Suspensioncui66' => array(
				'className' => 'Suspensioncui66',
				'foreignKey' => 'cui_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Accompagnementcui66' => array(
				'className' => 'Accompagnementcui66',
				'foreignKey' => 'cui_id',
				'dependent' => false,
				'conditions' => '',
				'fields' => '',
				'order' => '',
				'limit' => '',
				'offset' => '',
				'exclusive' => '',
				'finderQuery' => '',
				'counterQuery' => ''
			),
			'Bilanparcours66' => array(
				'className' => 'Bilanparcours66',
				'foreignKey' => 'cui_id',
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
			'Defautinsertionep66' => array(
				'className' => 'Defautinsertionep66',
				'foreignKey' => 'cui_id',
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
		*   Précondition: La personne est-elle bien en Rsa Socle ?
		*   @default: false --> si Rsa Socle pas de msg d'erreur
		*/

		public function _prepare( $personne_id = null ) {
			$vfRsaSocle = $this->Personne->Foyer->Dossier->Detaildroitrsa->vfRsaSocle();
			$result = $this->Personne->find(
				'first',
				array(
					'fields' => array(
						"( {$vfRsaSocle} ) AS \"Dossier__rsasocle\""
					),
					'joins' => array(
						$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->Dossier->join( 'Detaildroitrsa' )
					),
					'conditions' => array(
						'Personne.id' => $personne_id
					),
					'recursive' => -1
				)
			);
			return !$result['Dossier']['rsasocle'];
		}


		/**
		*   BeforeValidate
		*/
		public function beforeValidate( $options = array() ) {
			$return = parent::beforeValidate( $options );

			foreach( array( 'iscie' ) as $key ) {
				if( isset( $this->data[$this->name][$key] ) ) {
					$this->data[$this->name][$key] = Set::enum( $this->data[$this->name][$key], array( '1' => 'O', '0' => 'N' ) );
				}
			}

			return $return;
		}

		/**
		 * Recalcul de la position du CUI avant l'enregistrement (CG 66).
		 *
		 * @param array $options
		 * @return boolean
		 */
		public function beforeSave( $options = array() ) {
			$return = parent::beforeSave( $options );

			//  Calcul de la position du CUI
			if( Configure::read( 'Cg.departement' ) == '66' ) {
				$this->data[$this->alias]['positioncui66'] = $this->calculPosition( $this->data );
				//On unset pour prendre la valeur par défaut de la base de données
				if( is_null( $this->data[$this->alias]['positioncui66'] ) ) {
					unset( $this->data[$this->alias]['positioncui66'] );
				}
			}
			return $return;
		}

		/**
		* Recalcul des rangs des CUIs pour une personne donnée ou pour l'ensemble des personnes.
		 *
		 * @param integer $personne_id
		 * @return boolean
		 */
		protected function _updateRangsCuis( $personne_id = null ) {
			$condition = ( is_null( $personne_id ) ? "" : "cuis.personne_id = {$personne_id}" );

			$sql = "UPDATE cuis
						SET rangcui = NULL".( !empty( $condition ) ? " WHERE {$condition}" : "" ).";";
			$success = ( $this->query( $sql ) !== false );

			$sql = "UPDATE cuis
						SET rangcui = (
							SELECT ( COUNT(cuispcd.id) + 1 )
								FROM cuis AS cuispcd
								WHERE cuispcd.personne_id = cuis.personne_id
									AND cuispcd.id <> cuis.id
									AND cuispcd.decisioncui = 'V'
									AND cuispcd.datedebprisecharge IS NOT NULL
									AND cuispcd.datedebprisecharge < cuis.datedebprisecharge
									AND (
										cuis.positioncui66 IS NULL
										OR cuis.positioncui66 <> 'annule'
									)
						)
						WHERE
							cuis.datedebprisecharge IS NOT NULL
							".( !empty( $condition ) ? " AND {$condition}" : "" )."
							AND cuis.decisioncui = 'V'
							AND (
								cuis.positioncui66 IS NULL
								OR cuis.positioncui66 <> 'annule'
							);";

			$success = ( $this->query( $sql ) !== false ) && $success;

			return $success;
		}

		/**
		 * Recalcul des rangs des CUIs pour une personne donnée.
		 * afterSave, afterDelete, valider, annuler
		 *
		 * @return boolean
		 */
		public function updateRangsCuisPersonne( $personne_id ) {
			return $this->_updateRangsCuis( $personne_id  );
		}

		/**
		 * Recalcul des rangs des CUIs pour l'ensemble des personnes.
		 *
		 * @return boolean
		 */
		public function updateRangsCuis() {
			return $this->_updateRangsCuis();
		}

		/**
		 * Calcul de la position du CUI (CG 66).
		 *
		 * @param array $data
		 * @return string
		 */
		public function calculPosition( $data ) {
			$decisioncui = Set::classicExtract( $data, 'Cui.decisioncui' );
			$positioncui66 = Set::classicExtract( $data, 'Cui.positioncui66' );
			$datenotif = Set::classicExtract( $data, 'Cui.datenotification' );
			$id = Set::classicExtract( $data, 'Cui.id' );
			$personne_id = Set::classicExtract( $data, 'Cui.personne_id' );

			// Données dernier CUI
			$conditions = array( 'Cui.personne_id' => $personne_id, );
			if( !empty( $id ) ) {
				$conditions['Cui.id <>'] = $id;
			}

			$dernierCui = $this->find(
				'first',
				array(
					'conditions' => $conditions,
					'order' => 'Cui.rangcui DESC',
					'contain' => false
				)
			);
			$decisionprecedente = Set::classicExtract( $dernierCui, 'Cui.decisioncui' );
			$positioncui66Precedent = Set::classicExtract( $dernierCui, 'Cui.positioncui66' );



			if ( ( is_null( $positioncui66 ) || in_array( $positioncui66 , array( 'attdecision', 'perime' ) ) ) && !empty( $decisioncui ) ) {
				if ( $decisioncui == 'V' ){
					if( empty( $datenotif ) ) {
						$positioncui66 = 'valid';
					}
					else {
						$positioncui66 = 'validnotifie';
					}
				}
				else if ( $decisioncui == 'N' ){
					if( empty( $datenotif ) ) {
						$positioncui66 = 'nonvalid';
					}
					else {
						$positioncui66 = 'nonvalidnotifie';
					}
				}
			}

			// Lors de l'ajout d'un nouveau CUI, on passe la position du précédent à fin de contrat, sauf pour les non validés
			if( !empty( $dernierCui ) && ( $decisionprecedente != 'N' ) && ( $positioncui66Precedent != 'annule' ) ) {
				$this->updateAll(
					array( 'Cui.positioncui66' => '\'fincontrat\'' ),
					array(
						'"Cui"."personne_id"' => $personne_id,
						'"Cui"."id"' => $dernierCui['Cui']['id']
					)
				);
			}

			return $positioncui66;
		}

		/**
		 * Mise à jour de la position du cui selon la proposition de décision émise
		 *
		 * @param $propodeicsioncui66_id, identifiant de la proposition de décision du CUI
		 * @return string
		 */
		public function updatePositionFromPropodecisioncui66( $propodecisioncui66_id ) {
			$propodecisioncui66 = $this->Propodecisioncui66->find(
				'first',
				array(
					'fields' => array_merge(
						$this->Propodecisioncui66->fields(),
						array(
							'Cui.id',
							'Cui.positioncui66'
						)
					),
					'conditions' => array(
						'Propodecisioncui66.id' => $propodecisioncui66_id
					),
					'contain' => array(
						'Cui'
					)
				)
			);

			$isaviselu = Set::classicExtract( $propodecisioncui66, 'Propodecisioncui66.isaviselu' );
			$isavisreferent = Set::classicExtract( $propodecisioncui66, 'Propodecisioncui66.isavisreferent' );
			$positioncui66 = Set::classicExtract( $propodecisioncui66, 'Cui.positioncui66' );

			// Mise à jour de la position du CUI
			if( !empty( $positioncui66 ) ) {
				if( ( $isaviselu == '0' ) && ( $isavisreferent == '0' ) && $positioncui66 == 'attavismne' ) {
					$positioncui66 = 'attaviselu';
				}
				else if( ( $isaviselu == '1' ) && ( $isavisreferent == '0' ) && $positioncui66 =='attaviselu' ) {
					$positioncui66 = 'attavisreferent';
				}
				else if( ( $isaviselu == '1' ) && ( $isavisreferent == '1' ) && $positioncui66 =='attavisreferent' ) {
					$positioncui66 = 'attdecision';
				}
				else if( ( $positioncui66 =='attdecision' ) ) {
					$positioncui66 = 'attdecision';
				}
			}

			$this->id = $propodecisioncui66['Cui']['id'];
			$return = $this->saveField( 'positioncui66', $positioncui66 );

			return $return;
		}

		/**
		 * Récupération des données servant à construire le PDF de notification du CUI.
		 *
		 * @see Cui::getDefaultPdf
		 *
		 * @param integer $id
		 * @param integer $user_id
		 * @return array
		 */
		public function getDataForPdf( $id, $user_id ) {
			$cui = $this->find(
				'first',
				array(
					'fields' => array_merge(
						$this->fields(),
						$this->Personne->fields(),
						$this->Referent->fields(),
						$this->Structurereferente->fields(),
						$this->Personne->Foyer->fields(),
						$this->Personne->Foyer->Dossier->fields(),
						$this->Personne->Foyer->Adressefoyer->Adresse->fields()
					),
					'joins' => array(
						$this->join( 'Personne', array( 'type' => 'INNER' ) ),
						$this->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ),
						$this->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					),
					'conditions' => array(
						'Cui.id' => $id,
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ( '.$this->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
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
			$cui = Set::merge( $cui, $user );


			return $cui;
		}

		/**
		 * Retourne le PDF de notification du CUI.
		 *
		 * @see Cui::getDataForPdf
		 *
		 * @param integer $id L'id du CUI pour lequel on veut générer l'impression
		 * @return string
		 */
		public function getDefaultPdf( $id, $user_id ) {
			$cui = $this->getDataForPdf( $id, $user_id );
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
				$cui,
				'CUI/cui.odt',
				false,
				$options
			);
		}

		/**
		 * Retourne l'id du dossier à partir de l'id du CUI
		 *
		 * @param integer $id
		 * @return integer
		 */
		public function dossierId( $id ) {
			$cui = $this->find(
				'first',
				array(
					'fields' => array(
						'Foyer.dossier_id'
					),
					'joins' => array(
						$this->join( 'Personne', array( 'type' => 'INNER' ) ),
						$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
					),
					'conditions' => array(
						'Cui.id' => $id
					),
					'contain' => false
				)
			);

			if( !empty( $cui ) ) {
				return $cui['Foyer']['dossier_id'];
			}
			else {
				return null;
			}
		}
		
		/**
		 * Sous-requête permettant de récupérer le dernier contrat d'un allocataire.
		 *
		 * @param string $personneIdFied Le champ où trouver l'id de la personne.
		 * @return string
		 */
		public function sqDernierContrat( $personneIdFied = 'Personne.id' ) {
			return $this->sq(
				array(
					'fields' => array(
						'cuis.id'
					),
					'alias' => 'cuis',
					'conditions' => array(
						"cuis.personne_id = {$personneIdFied}"
					),
					'order' => array( 'cuis.datedebprisecharge DESC' ),
					'limit' => 1
				)
			);
		}
	}
?>