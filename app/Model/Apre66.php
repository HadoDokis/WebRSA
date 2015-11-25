<?php
	/**
	 * Code source de la classe Apre66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Apre66 ...
	 *
	 * @package app.Model
	 */
	class Apre66 extends AppModel
	{

		public $name = 'Apre66';
		public $displayField = 'numeroapre';
		public $useTable = 'apres';
		public $actsAs = array(
			'Allocatairelie',
			'Enumerable' => array(
				'fields' => array(
					'typedemandeapre' => array( 'type' => 'typedemandeapre', 'domain' => 'apre' ),
					'naturelogement' => array( 'type' => 'naturelogement', 'domain' => 'apre' ),
					'activitebeneficiaire' => array( 'type' => 'activitebeneficiaire', 'domain' => 'apre' ),
					'typecontrat' => array( 'type' => 'typecontrat', 'domain' => 'apre' ),
					'statutapre' => array( 'type' => 'statutapre', 'domain' => 'apre' ),
					'etatdossierapre' => array( 'type' => 'etatdossierapre', 'domain' => 'apre' ),
					'eligibiliteapre' => array( 'type' => 'eligibiliteapre', 'domain' => 'apre' ),
					'justificatif' => array( 'type' => 'justificatif', 'domain' => 'apre' ),
					'isdecision' => array( 'domain' => 'apre' ),
					'haspiecejointe' => array( 'domain' => 'apre' ),
					'istransfere' => array( 'domain' => 'apre' )
				)
			),
			'Frenchfloat' => array(
				'fields' => array(
					'montantaverser',
					'montantattribue',
					'montantdejaverse'
				)
			),
			'Formattable' => array(
				'suffix' => array( 'structurereferente_id', 'referent_id' ),
			),
			'Gedooo.Gedooo',
			'Conditionnable',
			'ModelesodtConditionnables' => array(
				66 => array(
					'APRE/apre66.odt',
					'APRE/accordaide.odt',
					'APRE/refusaide.odt',
				)
			)
		);
		public $validate = array(
			'activitebeneficiaire' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'typedemandeapre' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'avistechreferent' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'secteurprofessionnel' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'montantaverser' => array(
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.'
				),
			),
			'montantattribue' => array(
				array(
					'rule' => 'numeric',
					'message' => 'Veuillez entrer une valeur numérique.'
				),
			),
			'structurereferente_id' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'datedemandeapre' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			'referent_id' => array(
				array(
					'rule' => 'notEmpty',
					'message' => 'Champ obligatoire'
				)
			),
			//Partie activité bénéficiaire
			'typecontrat' => array(
				array(
					'rule' => array( 'notEmptyIf', 'activitebeneficiaire', true, array( 'E' ) ),
					'message' => 'Champ obligatoire',
					'required' => false
				)
			),
			'dureecontrat' => array(
				array(
					'rule' => array( 'notEmptyIf', 'activitebeneficiaire', true, array( 'E' ) ),
					'message' => 'Champ obligatoire'
				)
			),
			'nomemployeur' => array(
				array(
					'rule' => array( 'notEmptyIf', 'activitebeneficiaire', true, array( 'E' ) ),
					'message' => 'Champ obligatoire'
				)
			),
			'adresseemployeur' => array(
				array(
					'rule' => array( 'notEmptyIf', 'activitebeneficiaire', true, array( 'E' ) ),
					'message' => 'Champ obligatoire'
				)
			),
			'hascer' => array(
				array(
					'rule' => array( 'equalTo', '1' ),
					'message' => 'Champ obligatoire'
				)
			),
			'isbeneficiaire' => array(
				array(
					'rule' => array( 'equalTo', '1' ),
					'message' => 'Champ obligatoire'
				)
			),
			'respectdelais' => array(
				array(
					'rule' => array( 'equalTo', '1' ),
					'message' => 'Champ obligatoire'
				)
			)
		);
		public $hasOne = array(
			'Aideapre66' => array(
				'className' => 'Aideapre66',
				'foreignKey' => 'apre_id',
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
		);
		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
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
			'Referent' => array(
				'className' => 'Referent',
				'foreignKey' => 'referent_id',
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
					'Fichiermodule.modele = \'Apre66\'',
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
		 */
		public function numeroapre() {
			$numSeq = $this->query( "SELECT nextval('apres_numeroapre_seq');" );
			if( $numSeq === false ) {
				return null;
			}

			$numapre = date( 'Ym' ).sprintf( "%010s", $numSeq[0][0]['nextval'] );
			return $numapre;
		}

		/**
		 * Ajout de l'identifiant de la séance lors de la sauvegarde.
		 */
		public function beforeValidate( $options = array( ) ) {
			$primaryKey = Set::classicExtract( $this->data, "{$this->alias}.{$this->primaryKey}" );
			$numeroapre = Set::classicExtract( $this->data, "{$this->alias}.numeroapre" );

			if( empty( $primaryKey ) && empty( $numeroapre ) && empty( $this->{$this->primaryKey} ) ) {
				$this->data[$this->alias]['numeroapre'] = $this->numeroapre();
			}

			return true;
		}

		/**
		 *
		 * @param integer $apre_id
		 * @return string
		 */
		public function getNotificationAprePdf( $apre_id ) {
			$apre = $this->find(
				'first',
				array(
					'fields' => array_merge(
						$this->fields(),
						$this->Aideapre66->Themeapre66->fields(),
						$this->Aideapre66->Typeaideapre66->fields(),
						$this->Personne->fields(),
						$this->Structurereferente->fields(),
						$this->Referent->fields(),
						$this->Aideapre66->fields(),
						$this->Personne->Foyer->Adressefoyer->Adresse->fields(),
						$this->Personne->Foyer->fields(),
						$this->Personne->Foyer->Dossier->fields(),
						array(
							'( '.$this->Aideapre66->Pieceaide66->vfListePieces().' ) AS "Aideapre66__piecesaides66"',
							'( '.$this->Aideapre66->Typeaideapre66->Piececomptable66->vfListePieces().' ) AS "Aideapre66__piecescomptables66"',
							$this->Personne->Foyer->Adressefoyer->Adresse->sqVirtualField( 'localite' )
						)
					),
					'joins' => array(
						$this->join( 'Personne', array( 'type' => 'INNER' ) ),
						$this->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) ),
						$this->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ),
						$this->join( 'Aideapre66', array( 'type' => 'LEFT OUTER' ) ),
						$this->Aideapre66->join( 'Themeapre66', array( 'type' => 'LEFT OUTER' ) ),
						$this->Aideapre66->join( 'Typeaideapre66', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) )
					),
					'conditions' => array(
						"Apre66.id" => $apre_id,
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ( '.$this->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
						)
					),
					'contain' => false
				)
			);

			if( empty( $apre ) ) {
				return false;
			}

			// Options pour les traductions
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
			);

			// On sauvagarde la date de notification si ce n'est pas déjà fait.
			$recursive = $this->recursive;
			$this->recursive = -1;
			$this->updateAllUnBound(
				array( 'Apre66.datenotifapre' => date( "'Y-m-d'" ) ),
				array(
					'"Apre66"."id"' => $apre_id,
					'"Apre66"."datenotifapre" IS NULL'
				)
			);
			$this->recursive = $recursive;

			// Construction du champ virtuel Structurereferente.adresse
			$apre['Structurereferente']['adresse'] = implode(
					' ', array(
				Set::classicExtract( $apre, 'Structurereferente.num_voie' ),
				Set::enum( Set::classicExtract( $apre, 'Structurereferente.type_voie' ), $options['Structurereferente']['type_voie'] ),
				Set::classicExtract( $apre, 'Structurereferente.nom_voie' ),
				Set::classicExtract( $apre, 'Structurereferente.code_postal' ),
				Set::classicExtract( $apre, 'Structurereferente.ville' )
					)
			);

			// Choix du modèle de document
			if( $apre['Aideapre66']['decisionapre'] == 'ACC' ) {
				$modeleodt = 'APRE/accordaide.odt';
			}
			else {
				$modeleodt = 'APRE/refusaide.odt';
			}

			// Génération du PDF
			return $this->ged( $apre, $modeleodt, false, $options );
		}

// 		public function autorisationPlafondAideapre66( $aideapre66_id, $personne_id ){
//
// 		}

		/**
		 * Retourne le chemin vers le modèle odt utilisé pour l'APRE 66
		 *
		 * @param array $data
		 * @return string
		 */
		public function modeleOdt( $data ) {
			return 'APRE/apre66.odt';
		}

		/**
		 * Retourne les données nécessaires à l'impression d'une APRE pour le CG 66.
		 * Les données contiennent l'APRE à l'index 0 et une section "oldapres".
		 *
		 * @param integer $id
		 * @param integer $user_id
		 * @return array
		 */
		public function getDataForPdf( $id, $user_id ) {
			$typesvoies = ClassRegistry::init( 'Option' )->typevoie();

			$apre = $this->find(
				'first',
				array(
					'fields' => array_merge(
							$this->fields(), $this->Personne->fields(), $this->Structurereferente->fields(), $this->Referent->fields(), $this->Aideapre66->fields(), $this->Aideapre66->Fraisdeplacement66->fields(), $this->Aideapre66->Themeapre66->fields(), $this->Aideapre66->Themeapre66->Typeaideapre66->fields(), $this->Personne->Foyer->Adressefoyer->Adresse->fields(), $this->Personne->Foyer->fields(), $this->Personne->Foyer->Dossier->fields(), array(
						'( '.$this->Aideapre66->Pieceaide66->vfListePieces().' ) AS "Aideapre66__piecesaides66"',
						'( '.$this->Aideapre66->Typeaideapre66->Piececomptable66->vfListePieces().' ) AS "Aideapre66__piecescomptables66"',
						$this->Personne->Foyer->sqVirtualField( 'enerreur' ),
						$this->Personne->Foyer->sqVirtualField( 'sansprestation' ),
						$this->Personne->Foyer->Adressefoyer->Adresse->sqVirtualField( 'localite' )
							)
					),
					'joins' => array(
						$this->join( 'Personne', array( 'type' => 'INNER' ) ),
						$this->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) ),
						$this->join( 'Referent', array( 'type' => 'LEFT OUTER' ) ),
						$this->join( 'Aideapre66', array( 'type' => 'LEFT OUTER' ) ),
						$this->Aideapre66->join( 'Fraisdeplacement66', array( 'type' => 'LEFT OUTER' ) ),
						$this->Aideapre66->join( 'Themeapre66', array( 'type' => 'LEFT OUTER' ) ),
						$this->Aideapre66->join( 'Typeaideapre66', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->join( 'Foyer', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
						$this->Personne->Foyer->join( 'Dossier', array( 'type' => 'INNER' ) ),
						$this->Personne->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
					),
					'conditions' => array(
						"Apre66.id" => $id,
						'OR' => array(
							'Adressefoyer.id IS NULL',
							'Adressefoyer.id IN ( '.$this->Personne->Foyer->Adressefoyer->sqDerniereRgadr01( 'Foyer.id' ).' )'
						)
					),
					'contain' => false
				)
			);

			if( empty( $apre ) ) {
				return $apre;
			}

			// Récupération de l'utilisateur connecté
			$user = $this->Personne->Contratinsertion->User->find(
					'first', array(
				'conditions' => array(
					'User.id' => $user_id
				),
				'contain' => false
					)
			);
			$apre = Set::merge( $apre, $user );

			// Construction du champ virtuel Structurereferente.adresse
			$apre['Structurereferente']['adresse'] = implode(
					' ', array(
				Set::classicExtract( $apre, 'Structurereferente.num_voie' ),
				Set::enum( Set::classicExtract( $apre, 'Structurereferente.type_voie' ), $typesvoies ),
				Set::classicExtract( $apre, 'Structurereferente.nom_voie' ),
				Set::classicExtract( $apre, 'Structurereferente.code_postal' ),
				Set::classicExtract( $apre, 'Structurereferente.ville' )
					)
			);

			// Le lieu de résidence correspond à l'adresse normale s'il n'est pas explicitement renseigné
			if( !empty( $apre['Fraisdeplacement66']['id'] ) && empty( $apre['Fraisdeplacement66']['lieuresidence'] ) ) {
				$apre['Fraisdeplacement66']['lieuresidence'] = implode(
						' ', array(
					Set::extract( $apre, 'Adresse.numvoie' ),
					Set::extract( $apre, 'Adresse.libtypevoie' ),
					Set::extract( $apre, 'Adresse.nomvoie' ),
					Set::extract( $apre, 'Adresse.codepos' ),
					Set::extract( $apre, 'Adresse.nomcom' )
						)
				);
			}

			// Le passif des demandes d'APRE attribuées
			$listeApres = $this->find(
				'all',
				array(
					'fields' => array(
						'Apre66.id',
						'Aideapre66.datedemande',
						'Aideapre66.montantaccorde',
						'Typeaideapre66.name',
						'Themeapre66.name'
					),
					'conditions' => array(
						"Apre66.personne_id" => $apre['Personne']['id'],
						"Apre66.id <>" => $id,
						'Aideapre66.id IS NOT NULL',
						'Aideapre66.decisionapre' => 'ACC',
						'Apre66.datenotifapre IS NOT NULL',
						'Aideapre66.datedemande <=' => $apre['Aideapre66']['datedemande'],
					),
					'joins' => array(
						$this->join( 'Aideapre66' ),
						$this->Aideapre66->join( 'Typeaideapre66'),
						$this->Aideapre66->join( 'Themeapre66' )
					),
					'order' => array( 'Aideapre66.datedemande DESC' ),
					'recursive' => -1
				)
			);

			/// INFO: pour éviter d'écraser les valeurs de la partie principale avec la valeur de la dernière itération lorsque la section précède l'affichage de la valeur principale.
			foreach( $listeApres as $i => $oldapre ) {
				$listeApres[$i] = array( 'oldapre' => $oldapre );
			}

			return array( $apre, 'oldapres' => $listeApres );
		}

		/**
		 * Retourne le PDF par défaut généré par les appels aux méthodes getDataForPdf, modeleOdt et
		 * à la méthode ged du behavior Gedooo
		 *
		 * @param type $id Id de l'APRE
		 * @param type $user_id Id de l'utilisateur connecté
		 * @return string
		 */
		public function getDefaultPdf( $id, $user_id ) {
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
			);

			$apre = $this->getDataForPdf( $id, $user_id );

			if( empty( $apre ) ) {
				$this->cakeError( 'error404' );
			}

			return $this->ged(
				$apre,
				$this->modeleOdt( $apre ),
				true,
				$options
			);
		}
		
		/**
		 * Utilise Correspondancepersonne pour trouver le montant total d'apre pris dans l'année.
		 * @param integer $personne_id
		 * @param boolean $anomalie
		 * @return integer
		 */
		public function getMontantApreEnCours( $personne_id, $anomalie = null ){
			$dateDebut = date( 'Y' ).'-01-01';
			$dateFin = (date( 'Y' ) + Configure::read( 'Apre.periodeMontantMaxComplementaires' ) - 1).'-12-31';
			
			return $this->getMontantAprePeriode($dateDebut, $dateFin, $personne_id, $anomalie);
		}
		
		/**
		 * Utilise Correspondancepersonne pour trouver le montant total d'apre pour une période donnée.
		 * @param string $dateDebut au format SQL
		 * @param string $dateFin au format SQL
		 * @param integer $personne_id
		 * @param boolean $anomalie
		 * @return integer
		 */
		public function getMontantAprePeriode( $dateDebut, $dateFin, $personne_id, $anomalie = null ){
			$queryCorrespondances = array(
				'fields' => 'Correspondancepersonne.personne2_id',
				'conditions' => array( 
					'Correspondancepersonne.personne1_id' => $personne_id,
				),
			);
			
			if ( $anomalie !== null ) {
				$queryCorrespondances['conditions']['Correspondancepersonne.anomalie'] = $anomalie;
			}
			
			$personne_idSearch = $this->Personne->Correspondancepersonne->find( 'all', $queryCorrespondances );
			
			$personne_idList = array();
			foreach ($personne_idSearch as $value) {
				$personne_idList[] = $value['Correspondancepersonne']['personne2_id'];
			}
			
			$query = array(
				'fields' => array(
					'SUM(Aideapre66.montantaccorde) AS "Aideapre66__montantaccorde"',
				),
				'joins' => array(
					$this->join( 'Aideapre66', array( 'type' => 'INNER' ) )
				),
				'contain' => false,
				'conditions' => array(
					"{$this->alias}.personne_id" => array_merge(
						array($personne_id),
						$personne_idList
					),
					"{$this->alias}.statutapre" => 'C',
					"Aideapre66.decisionapre" => 'ACC',
					"Aideapre66.datemontantpropose BETWEEN '{$dateDebut}' AND '{$dateFin}'",
					"{$this->alias}.etatdossierapre <>" => 'ANN',
					'Aideapre66.montantaccorde IS NOT NULL'
				),
			);
			$results = $this->find( 'all', $query );
			
			$montantaccorde = Hash::get($results, '0.Aideapre66.montantaccorde');
			
			return $montantaccorde;
		}
	}
?>