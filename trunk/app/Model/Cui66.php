<?php
	/**
	 * Fichier source de la classe Cui66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Cui66 est la classe contenant les informations additionnelles du CUI pour le CG 66.
	 *
	 * @package app.Model
	 */
	class Cui66 extends AppModel // TODO : Passage en En attente d'avis technique avant meme l'envoi d'un e-mail
	{
		/**
		 * Alias de la table et du model
		 * @var string
		 */
		public $name = 'Cui66';

		/**
		 * Recurcivité du model 
		 * @var integer
		 */
		public $recursive = -1;
		
		/**
		 * Possède des clefs étrangères vers d'autres models
		 * @var array
		 */
        public $belongsTo = array(
			'Cui' => array(
				'className' => 'Cui',
				'foreignKey' => 'cui_id',
				'dependent' => true,
			),
			'Partenaire' => array(
				'className' => 'Partenaire',
				'foreignKey' => 'partenaire_id',
				'dependent' => true,
			),
        );
		
		/**
		 * Ces models possèdent une clef étrangère vers ce model
		 * @var array
		 */
		public $hasMany = array(
			'Propositioncui66' => array(
				'className' => 'Propositioncui66',
				'foreignKey' => 'cui66_id',
				'dependent' => true,
			),
			'Decisioncui66' => array(
				'className' => 'Decisioncui66',
				'foreignKey' => 'cui66_id',
				'dependent' => true,
			),
			'Accompagnementcui66' => array(
				'className' => 'Accompagnementcui66',
				'foreignKey' => 'cui66_id',
				'dependent' => true,
			),
			'Suspensioncui66' => array(
				'className' => 'Suspensioncui66',
				'foreignKey' => 'cui66_id',
				'dependent' => true,
			),
			'Rupturecui66' => array(
				'className' => 'Rupturecui66',
				'foreignKey' => 'cui66_id',
				'dependent' => true,
			),
			'Historiquepositioncui66' => array(
				'className' => 'Historiquepositioncui66',
				'foreignKey' => 'cui66_id',
				'dependent' => true,
			),
		);
				
		/**
		 * Behaviors utilisés par le modèle.
		 *
		 * @var array
		 */
		public $actsAs = array(
			'Formattable',
			'Postgres.PostgresAutovalidate',
			'Validation2.Validation2Formattable',
		);
		
		/**
		* Chemin relatif pour les modèles de documents .odt utilisés lors des
		* impressions. Utiliser %s pour remplacer par l'alias.
		*/
		public $modelesOdt = array(
			'ficheLiaison' => 'CUI/synthesecui66.odt',
			'default' => 'CUI/impression.odt',
		);
		
		/**
		 * Permet de savoir si un ajout est possible à partir des messages
		 * renvoyés par la méthode messages.
		 *
		 * @param array $messages
		 * @return boolean
		 */
		public function addEnabled( array $messages ) {
			return !in_array( 'error', $messages );
		}
		
		/**
		 * @param integer $personne_id
		 * @return array
		 */
		public function messages( $personne_id ) {
			$messages = array();

			return $messages;
		}

		/**
		 * Récupère les donnés par defaut dans le cas d'un ajout, ou récupère les données stocké en base dans le cas d'une modification
		 * 
		 * @param integer $personne_id
		 * @param integer $id
		 * @return array
		 */
		public function prepareFormDataAddEdit( $personne_id, $id = null ) {
			// Ajout
			if( empty( $id ) ) {
				$sqDernierTitresejour = $this->Cui->Personne->Titresejour->sqDernier();
				$sqNbEnfants = $this->Cui->Personne->Foyer->vfNbEnfants();
				$sqNbBeneficiaires = $this->Cui->Personne->Foyer->sqNombreBeneficiaires();
				$sqLastCodepartenaire = $this->Cui->Partenaire->sqGetLastCodePartenaire();

				$query = array(
					'fields' => array(
						'Titresejour.dftitsej',
						"( {$sqNbEnfants} ) AS \"Foyer__nb_enfants\"",
						"( {$sqNbBeneficiaires} ) AS \"Foyer__nb_beneficiaires\"",
						"( {$sqLastCodepartenaire} ) AS \"Partenairecui__codepartenaire\"",
					),
					'recursive' => -1,
					'conditions' => array(
						'Personne.id' => $personne_id
					),
					'joins' => array(
						$this->Cui->Personne->join( 'Titresejour',
							array(
								'type' => 'LEFT OUTER', 
								'conditions' => "Titresejour.id IN ( {$sqDernierTitresejour} )"
							)
						),
						$this->Cui->Personne->join( 'Foyer', array( 'type' => 'INNER' ) )
					)
				);
				$record = $this->Cui->Personne->find( 'first', $query );

				$record['Partenairecui']['codepartenaire'] = str_pad( ($record['Partenairecui']['codepartenaire'] +1), 3, '0', STR_PAD_LEFT );

				/** 
				 * INFO: si one ne met pas le modèle Adressecui dans le $this->request->data, il n'est
				 * pas instancié dans la vue, donc pas d'astérisque ni validation javascript...
				 */
				$result = array(
					'Cui' => array(
						'personne_id' => $personne_id,
						'numconventionobjectif' => Configure::read( 'Cui.Numeroconvention' ),
					),
					'Cui66' => array(
						'encouple' => $record['Foyer']['nb_beneficiaires'] >= 2 ? 1 : 0,
						'avecenfant' => $record['Foyer']['nb_enfants'] >= 1 ? 1 : 0,
						'demandeenregistree' => date_format(new DateTime(), 'Y-m-d'),
						'datefinsejour' => Hash::get( $record, 'Titresejour.dftitsej' ),
						'etatdossiercui66' => 'attentemail',
						'notifie' => 0
					),
					'Partenairecui66' => array(
						'nbcontratsaidescg' => '0',
						'codepartenaire' => $record['Partenairecui']['codepartenaire']
					),
					'Adressecui' => array()
				);
			}
			// Mise à jour
			else {
				$query = $this->queryView($id);
				$result = $this->find( 'first', $query );

				$result = $this->Cui->Entreeromev3->prepareFormDataAddEdit( $result );
			}

			return $result;
		}
		
		/**
		 * Change la valeur de Cui66.etatdossiercui66 en annule
		 * 
		 * @param array $data
		 * @return boolean
		 */
		public function annule( $data ){
			$data['Cui66']['etatdossiercui66'] = 'annule';
			return $this->save( $data );
		}
		
		/**
		 * Revoi la requete pour récuperer toutes les données pour l'affichage d'un CUI (Hors modules)
		 * 
		 * @param integer $id
		 * @return array
		 */
		public function queryView( $id = null ){
			$query = array(
				'fields' => array_merge(
					$this->fields(),
					$this->Cui->Partenairecui->fields(),
					$this->Cui->Entreeromev3->fields(),
					$this->Cui->Partenairecui->Adressecui->fields(),
					$this->Cui->Partenairecui->Partenairecui66->fields(),
					$this->Cui->fields()
				),
				'recursive' => -1,
				'conditions' => array(),
				'joins' => array(
					$this->join( 'Cui', array( 'type' => 'INNER' ) ),
					$this->Cui->join( 'Partenairecui' ),
					$this->Cui->join( 'Entreeromev3' ),
					$this->Cui->Partenairecui->join( 'Partenairecui66' ),
					$this->Cui->Partenairecui->join( 'Adressecui' ),						
				)
			);
			
			if( $id !== null ) {
				$query['conditions']['Cui.id'] = $id;
			}
			
			return $query;
		}
		
		/**
		 * Revoi la requete pour récuperer toutes les données pour l'affichage de l'index du CUI
		 * 
		 * @param integer $personne_id
		 * @return array
		 */
		public function queryIndex($personne_id){
			// Utile pour l'affichage des dates de relance par email
			$sqRelanceQuery = array(
				'alias' => 'emailscuis',
				'fields' => 'emailscuis.id',
				'conditions' => array(
					'emailscuis.dateenvoi IS NOT NULL',
					'UPPER(textsmailscuis66.name) LIKE \'%RELANCE%\'',
					'emailscuis.cui_id = Cui66.cui_id'
				),
				'joins' => array( 
					array_words_replace(
						$this->Cui->Emailcui->join( 'Textmailcui66', array( 'type' => 'INNER' ) ), 
						array( 'Emailcui' => 'emailscuis', 'Textmailcui66' => 'textsmailscuis66' )
					)
				),
				'order' => 'emailscuis.dateenvoi DESC',
				'limit' => 1
			);
			$sqRelanceMail = $this->Cui->Emailcui->sq( $sqRelanceQuery );
			
			// Utile pour l'affichage des changements de positions du CUI
			$sqDateChangementQuery = array(
				'alias' => 'historiquepositionscuis66',
				'fields' => 'historiquepositionscuis66.id',
				'conditions' => array(
					'historiquepositionscuis66.cui66_id = Cui66.id'
				),
				'order' => 'historiquepositionscuis66.created DESC',
				'limit' => 1
			);
			$sqDateChangementPosition = $this->Cui->Cui66->Historiquepositioncui66->sq( $sqDateChangementQuery );

			$query = array(
				'fields' => array_merge(
					$this->Cui->fields(),
					array('Cui.dureecontrat', 'Emailcui.dateenvoi', 'Historiquepositioncui66.created'),
					$this->Cui->Cui66->fields(),
					$this->Cui->Partenairecui->fields(),
					$this->Cui->Cui66->Decisioncui66->fields(),
					$this->Cui->Cui66->Suspensioncui66->fields(),
					$this->Cui->Cui66->Rupturecui66->fields(),
					array(
						$this->Cui->Fichiermodule->sqNbFichiersLies( $this->Cui, 'nombre' ),
					)
				),
				'conditions' => array(
					'Cui.personne_id' => $personne_id
				),
				'joins' => array(
					$this->Cui->join( 'Cui66', array( 'type' => 'INNER' ) ),
					$this->Cui->join( 'Partenairecui' ),
					$this->Cui->join( 'Emailcui', array( 'conditions' => "Emailcui.id IN ({$sqRelanceMail})" ) ),
					$this->Cui->Cui66->join( 'Decisioncui66' ),
					$this->Cui->Cui66->join( 'Suspensioncui66' ),
					$this->Cui->Cui66->join( 'Rupturecui66' ),
					$this->Cui->Cui66->join( 'Historiquepositioncui66', array( 'conditions' => "Historiquepositioncui66.id IN ({$sqDateChangementPosition})") ),
				),
				'order' => array( 'Cui.created DESC' )
			);
			
			return $query;
		}
		
		/**
		 * Requète d'impression
		 * 
		 * @param type $cui_id
		 * @return type
		 */
		public function queryImpression( $cui_id = null ){
			$queryView = $this->queryView( $cui_id );
			$queryPersonne = $this->queryPersonne( 'Cui.personne_id' );
			
			$query['fields'] = array_merge( $queryView['fields'], $queryPersonne['fields'] );
			$query['joins'] = array_merge( $queryView['joins'], array( $this->Cui->join( 'Personne' ) ), $queryPersonne['joins'] );
			$query['conditions'] = $queryView['conditions'];
			
			return $query;
		}

		/**
		 * Permet d'obtenir les informations lié à un Allocataire d'un Cui
		 * 
		 * @param integer $id
		 * @return array
		 */
		public function queryPersonne( $personne_id ){
			$query = ClassRegistry::init( 'Allocataire' )->searchQuery();

			$query['fields'] = array_merge(
				$query['fields'],
				array(
					'Titresejour.dftitsej',
					'Departement.name',
					'( '.$this->Cui->Personne->Foyer->vfNbEnfants().' ) AS "Foyer__nb_enfants"'
				)
			);

			$query['joins'][] = $this->Cui->Personne->Foyer->Adressefoyer->Adresse->join( 'Departement', array( 'type' => 'LEFT OUTER' ) );
			$query['joins'][] = $this->Cui->Personne->join( 'Titresejour', array( 'type' => 'LEFT OUTER' ) );
			
			$query['conditions']['Personne.id'] = $personne_id;
			
			return $query;
		}

		/**
		 * Sauvegarde d'un CUI
		 * 
		 * @param array $data
		 * @return boolean
		 */
		public function saveAddEdit( array $data, $user_id = null ) {
			// INFO: champ non obligatoire
			unset( $this->Cui->Entreeromev3->validate['familleromev3_id']['notEmpty'] );
			$success = true;

			$data['Cui']['user_id'] = $user_id;
			
			// Si un code famille (rome v3) est vide, on ne sauvegarde pas le code rome
			if ( !isset($data['Entreeromev3']['familleromev3_id']) || $data['Entreeromev3']['familleromev3_id'] === '' ){ 
				$data['Cui']['entreeromev3_id'] = null;
				
				// Si le code rome avait un id, on supprime l'entreeromev3 correspondant
				if ( isset($data['Entreeromev3']['id']) && $data['Entreeromev3']['id'] !== '' ){
					$this->Cui->Entreeromev3->id = $data['Entreeromev3']['id'];
					$success = $success && $this->Cui->Entreeromev3->delete();
				}
			}
			// Dans le cas contraire, on enregistre le tout
			else{
				$this->Cui->Entreeromev3->create($data);
				$success = $success && $this->Cui->Entreeromev3->save();
				$data['Cui']['entreeromev3_id'] = $this->Cui->Entreeromev3->id;
			}
			
			// Si le contrat est un CDI, on s'assure que la date de fin soit nulle
			if ( $data['Cui']['typecontrat'] === 'CDI' ){
				$data['Cui']['findecontrat'] = null;
			}
			
			// Partenairecui possède une Adressecui, on commence par cette dernière
			$this->Cui->Partenairecui->Adressecui->create($data);
			$success = $success && $this->Cui->Partenairecui->Adressecui->save();
			$data['Partenairecui']['adressecui_id'] = $this->Cui->Partenairecui->Adressecui->id;
			
			// Cui et Partenairecui66 possèdent un Partenairecui, il nous faut son id
			$this->Cui->Partenairecui->create($data);
			$success = $success && $this->Cui->Partenairecui->save();
			$data['Cui']['partenairecui_id'] = $this->Cui->Partenairecui->id;
			$data['Partenairecui66']['partenairecui_id'] = $this->Cui->Partenairecui->id;
			
			// On peut ensuite enregistrer Partenairecui66
			$this->Cui->Partenairecui->Partenairecui66->create($data);
			$success = $success && $this->Cui->Partenairecui->Partenairecui66->save();

			// Dans le cas d'un ajout, on met à jour les parametrages des partenaires
			if ( empty($data['Cui']['id']) ){
				$data = $this->Cui->Partenairecui->Partenairecui66->addPartenaireData( $data );
				$this->Cui->Partenaire->create($data['Partenaire']);
				$success = $success && $this->Cui->Partenaire->save();
				$data['Cui']['partenaire_id'] = $this->Cui->Partenaire->id;
			}
			
			if ( empty($data['Cui']['tauxfixeregion']) && empty($data['Cui']['priseenchargeeffectif']) && empty($data['Cui']['tauxcg']) ){
				$Tauxcgcui66 = ClassRegistry::init( 'Tauxcgcui66' );
				$query = array(
					'conditions' => array(
						'Tauxcgcui66.typeformulaire' => $data['Cui66']['typeformulaire'],
						'Tauxcgcui66.secteurmarchand' => $data['Cui']['secteurmarchand'],
						'Tauxcgcui66.typecontrat' => $data['Cui66']['typecontrat'],
					),
					'order' => array(
						'created' => 'DESC'
					)
				);
				$result = $Tauxcgcui66->find( 'first', $query );
				
				if ( !empty($result) ){
					$data['Cui']['tauxfixeregion'] = $result['Tauxcgcui66']['tauxfixeregion'];
					$data['Cui']['priseenchargeeffectif'] = $result['Tauxcgcui66']['priseenchargeeffectif'];
					$data['Cui']['tauxcg'] = $result['Tauxcgcui66']['tauxcg'];
				}
			}
			
			// Cui66 possède un Cui
			$this->Cui->create($data);
			$success = $success && $this->Cui->save();
			$data['Cui66']['cui_id'] = $this->Cui->id;
			
			// On termine par le Cui66
			$this->create($data);
			$success = $success && $this->save();
			

			
			return $success;
		}
		
		/**
		 * Retourne les options nécessaires au formulaire de recherche, au formulaire,
		 * aux impressions, ...
		 *
		 * @param array $params <=> array( 'allocataire' => true, 'find' => false, 'autre' => false, 'pdf' => false )
		 * @return array
		 */
		public function options( array $params = array() ) {
			$options = array();
			$params = $params + array( 'allocataire' => true, 'find' => false, 'autre' => false, 'pdf' => false );


			if( Hash::get( $params, 'allocataire' ) ) {
				$Allocataire = ClassRegistry::init( 'Allocataire' );

				$options = $Allocataire->options();
			}
			
			if( $params['find'] ) {
				$options = Hash::merge(
					$options,
					$this->Cui->Entreeromev3->options()
				);
			}

			$datebutoir_select = array(
				'Cui66' => array(
					'datebutoir_select' => array(
						1 => __d( 'cui66', 'ENUM::DATEBUTOIR_SELECT::1' ),
						2 => __d( 'cui66', 'ENUM::DATEBUTOIR_SELECT::2' ),
						3 => __d( 'cui66', 'ENUM::DATEBUTOIR_SELECT::3' ),
					)
				)
			);
			
			$options = Hash::merge(
				$options,
				$this->enums(),
				$this->Decisioncui66->enums(),
				$this->Cui->enums(),
				$this->Cui->Partenairecui->enums(),
				$datebutoir_select
			);

			return $options;
		}
		
		/**************************************************************************************************************/
		
		/**
		 * Retourne les positions et les conditions CakePHP/SQL dans l'ordre dans
		 * lequel elles doivent être traitées pour récupérer la position actuelle.
		 *
		 * @return array
		 */
		protected function _getConditionsPositionsCuis() {			
			// L'e-mail post décision à été envoyé (dateenvoi du dernier e-mail > datededécision)
			$emailDecision = 'EXISTS(
				SELECT emailcui_sq.id
				FROM emailscuis AS emailcui_sq
				INNER JOIN decisionscuis66 AS decisioncui66_sq ON ( decisioncui66_sq.id = (
					SELECT decisioncui66_sq2.id FROM decisionscuis66 AS decisioncui66_sq2
					WHERE decisioncui66_sq2.cui66_id = Cui66.id
					ORDER BY decisioncui66_sq2.datedecision DESC
					LIMIT 1
				))
				WHERE emailcui_sq.dateenvoi IS NOT NULL
				AND decisioncui66_sq.datedecision IS NOT NULL
				AND (emailcui_sq.dateenvoi::date > decisioncui66_sq.datedecision::date
				OR emailcui_sq.dateenvoi > decisioncui66_sq.created)
				AND emailcui_sq.cui_id = Cui66.cui_id
				LIMIT 1
			)'; // FIXME Bouton action notification à la place
			
			// Le CUI possède une décision favorable
			$decisionFavorable = 'EXISTS(
				SELECT decisioncui66_sq3.id
				FROM decisionscuis66 AS decisioncui66_sq3
				WHERE decisioncui66_sq3.cui66_id = Cui66.id
				AND decisioncui66_sq3.decision IS NOT NULL
				AND decisioncui66_sq3.decision = \'accord\'
				LIMIT 1
			)';
			
			// Le CUI possède une décision ajourné
			$decisionAjourne = 'EXISTS(
				SELECT decisioncui66_sq3.id
				FROM decisionscuis66 AS decisioncui66_sq3
				WHERE decisioncui66_sq3.cui66_id = Cui66.id
				AND decisioncui66_sq3.decision IS NOT NULL
				AND decisioncui66_sq3.decision = \'ajourne\'
				LIMIT 1
			)';
			
			// Le CUI possède une décision de refus
			$decisionRefus = 'EXISTS(
				SELECT decisioncui66_sq3.id
				FROM decisionscuis66 AS decisioncui66_sq3
				WHERE decisioncui66_sq3.cui66_id = Cui66.id
				AND decisioncui66_sq3.decision IS NOT NULL
				AND (decisioncui66_sq3.decision = \'refus\'
				OR decisioncui66_sq3.decision = \'sanssuite\')
				LIMIT 1
			)';
			
			// Le CUI possède une décision
			$decision = 'EXISTS(
				SELECT decisioncui66_sq3.id
				FROM decisionscuis66 AS decisioncui66_sq3
				WHERE decisioncui66_sq3.cui66_id = Cui66.id
				AND decisioncui66_sq3.decision IS NOT NULL
				LIMIT 1
			)';
			
			// Le CUI possède une rupture
			$rupture = 'EXISTS(
				SELECT rupurecui66_sq.id
				FROM rupturescuis66 AS rupurecui66_sq
				WHERE rupurecui66_sq.cui66_id = Cui66.id
				LIMIT 1
			)';
			
			// Le CUI possède une suspension avec une datedefin < NOW() > datedebut
			$suspensionEnCours = 'EXISTS(
				SELECT Cui66.id
				FROM suspensionscuis66 AS suspensioncui66_sq
				WHERE suspensioncui66_sq.cui66_id = Cui66.id
				AND suspensioncui66_sq.datedebut IS NOT NULL
				AND suspensioncui66_sq.datefin IS NOT NULL
				AND NOW()::date BETWEEN suspensioncui66_sq.datedebut AND suspensioncui66_sq.datefin
				LIMIT 1
			)';
			
			// Le CUI possède un avis technique PRE
			$avisTechniquePre = 'EXISTS(
				SELECT propositioncui66_sq.id
				FROM propositionscuis66 AS propositioncui66_sq
				WHERE propositioncui66_sq.cui66_id = Cui66.id
				AND propositioncui66_sq.donneuravis = \'PRE\'
				AND propositioncui66_sq.avis != \'attentedecision\'
				LIMIT 1
			)';
			
			// Les cases importantes ont toute été rempli
			$formulaireRempli = 'Cui.dateembauche IS NOT NULL';
			
			// Le CUI possède un e-mail avec une dateenvoi not null
			$emailInitial = 'EXISTS(
				SELECT emailcui_sq.id
				FROM emailscuis AS emailcui_sq
				WHERE emailcui_sq.dateenvoi IS NOT NULL
				AND emailcui_sq.cui_id = Cui66.cui_id
				LIMIT 1
			)';
			
			// /!\ Attention, le mot relance doit être placé dans le textsmailscuis66.name pour être pris en compte
			$emailRelance = 'EXISTS(
				SELECT emailcui_sq.id
				FROM emailscuis AS emailcui_sq
				INNER JOIN textsmailscuis66 ON (textsmailscuis66.id = emailcui_sq.textmailcui66_id)
				WHERE emailcui_sq.dateenvoi IS NOT NULL
				AND UPPER(textsmailscuis66.name) LIKE \'%RELANCE%\'
				AND emailcui_sq.cui_id = Cui66.cui_id
				LIMIT 1
			)';
			
			$return = array(
				// 1. Annulé
				'annule' => array(
					array(
						$this->alias.'.etatdossiercui66' => 'annule',
					)
				),
				
				// 2. Traité (Décision sans suite)
				'decisionsanssuite' => array(
					'OR' => array(
						array(
							$this->alias.'.notifie' => 1,
							$decisionRefus
						),
						array(
							$this->alias.'.dossierrecu IS NOT NULL',
							$this->alias.'.dossierrecu' => 0
						),
						array(
							$this->alias.'.etatdossiercui66' => array(
								'attentemail',
								'attentepiece',
								'dossiereligible',
								'dossierrecu',
								'formulairecomplet',
								'attenteavis',
								'attentedecision'
							),
							$this->alias.'.datebutoir IS NOT NULL',
							$this->alias.'.datebutoir <= NOW()::DATE'
						)
					)
				),
				
				// 3. Traité (Dossier non éligible)
				'nonvalide' => array(
					array(
						$this->alias.'.dossiereligible IS NOT NULL',
						$this->alias.'.dossiereligible' => 0
					)
				),
				
				// 4. Rupture du CUI depuis le
				'rupturecontrat' => array(
					array(
						$rupture
					)
				), 
				
				// 5. Suspendu jusqu'au
				'contratsuspendu' => array(
					array(
						$suspensionEnCours
					)
				),
				
				// 6. Fin de contrat
				'perime' => array(
					array(
						'Cui.findecontrat IS NOT NULL',
						'Cui.findecontrat <= NOW()::DATE'
					)
				),
				
				// 7. En cours
				'encours' => array(
					array(
						$this->alias.'.notifie' => 1,
						$decisionFavorable
					)
				),
				
				// 8. En attente de notification
				'attentenotification' => array(
					array(
						$decision
					)
				),
				
				// 9. En attente de décision
				'attentedecision' => array(
					'OR' => array(
						$avisTechniquePre,
						$decisionAjourne
					)
				),
							
				// 12. En attente d'avis techniques
				'attenteavis' => array(
					array(
						$emailInitial,
						$formulaireRempli
					)
				),				
				
				// X. Relance le %s (Dossier non reçu) TODO SQL et date
				'dossierrelance' => array(
					array(
						$emailRelance,
						$this->alias.'.dossiercomplet IS NOT NULL',
						$this->alias.'.dossiercomplet' => 0
					)
				),
				
				// X. En attente de relance (Dossier non reçu) TODO SQL
				'dossiernonrecu' => array(
					array(
						$emailInitial,
						$this->alias.'.dossiercomplet IS NOT NULL',
						$this->alias.'.dossiercomplet' => 0
					)
				),
				
				// 11. En attente d'informations complémentaires
				'formulairecomplet' => array(
					array(
						$emailInitial,
						$this->alias.'.dossiercomplet IS NOT NULL',
						$this->alias.'.dossiercomplet' => 1
					)
				),
					
				// 14. En attente de pièces (Verification éligibilité)
				'dossierrecu' => array(
					array(
						$emailInitial,
						$this->alias.'.dossierrecu IS NOT NULL',
						$this->alias.'.dossierrecu' => 1
					)
				),
				
				// 13. En attente de pièces
				'dossiereligible' => array(
					array(
						$emailInitial,
						$this->alias.'.dossiereligible IS NOT NULL',
						$this->alias.'.dossiereligible' => 1
					)
				),
				
				'attentepiece' => array(
					array(
						$emailInitial,
					)
				),
			);

			return $return;
		}

		/**
		 * Retourne les conditions permettant de cibler les CUI qui devraient être
		 * dans une cuitaine position.
		 *
		 * @param string $etatdossiercui66
		 * @return array
		 */
		public function getConditionsPositioncui( $etatdossiercui66 ) {
			$conditions = array();

			foreach( $this->_getConditionsPositionsCuis() as $keyPosition => $conditionsPosition ) {
				if ( $keyPosition === $etatdossiercui66 ) {
					$conditions[] = array( $conditionsPosition );
					break;
				}
			}

			return $conditions;
		}

		/**
		 * Retourne une CASE (PostgreSQL) pemettant de connaître la position que
		 * devrait avoir un CUI (au CG 66).
		 *
		 * A utiliser par exemple en tant que chmap virtuel, à partir du moment
		 * où le modèle Contratinsertion (ou un alias) est présent dans la requête
		 * de base.
		 *
		 * @return string
		 */
		public function getCasePositionCui() {
			$return = '';
			$Dbo = $this->getDataSource();

			foreach( array_keys( $this->_getConditionsPositionsCuis() ) as $etatdossiercui66 ) {
				$conditions = $this->getConditionsPositioncui( $etatdossiercui66 );
				$conditions = $Dbo->conditions( $conditions, true, false, $this );
				$return .= "WHEN {$conditions} THEN '{$etatdossiercui66}' ";
			}

			// Position par defaut : En attente d'envoi de l'e-mail pour l'employeur
			$return = "( CASE {$return} ELSE 'attentemail' END )";

			return $return;
		}

		/**
		 * Mise à jour des positions des CUI suivant des conditions données.
		 *
		 * @param array $conditions
		 * @return boolean
		 */
		public function updatePositionsCuisByConditions( array $conditions ) {
			$query = array( 
				'fields' => array( "{$this->alias}.{$this->primaryKey}", "{$this->alias}.etatdossiercui66" ), 
				'conditions' => $conditions, 
				'joins' => array( $this->join( 'Cui' ) )
			);
			$datas = $this->find( 'all', $query );
			
			if ( empty( $datas ) ){
				return true;
			}

			$Dbo = $this->getDataSource();
			$DboCui = $this->Cui->getDataSource();

			$tableName = $Dbo->fullTableName( $this, true, true );
			$tableNameCui = $DboCui->fullTableName( $this->Cui, true, true );
			$case = $this->getCasePositionCui();

			$sq = $Dbo->startQuote;
			$eq = $Dbo->endQuote;

			$conditionsSql = $Dbo->conditions( $conditions, true, true, $this );
			
			$sql = "UPDATE {$tableName} AS {$sq}{$this->alias}{$eq} SET {$sq}etatdossiercui66{$eq} = {$case} FROM {$tableNameCui} AS {$sq}{$this->Cui->alias}{$eq} {$conditionsSql} AND {$sq}{$this->Cui->alias}{$eq}.{$sq}id{$eq} = {$sq}{$this->alias}{$eq}.{$sq}cui_id{$eq};";

			$result = $Dbo->query( $sql ) !== false;
			
			// On regarde si des valeurs ont changés
			$query2 = array( 
				'fields' => array( "{$this->alias}.{$this->primaryKey}", "{$this->alias}.etatdossiercui66" ), 
				'conditions' => $conditions, 
				'joins' => array( $this->join( 'Cui' ) )
			);
			$datas2 = $this->find( 'all', $query2 );
			
			// On génère une requete si il y a eu changement
			$different = false;
			$updateValues = array();
			foreach( $datas as $data ){
				foreach( $datas2 as $data2 ){ // Logiquement, ne tournera qu'une seule fois
					if ( $data['Cui66']['id'] === $data2['Cui66']['id'] && $data['Cui66']['etatdossiercui66'] !== $data2['Cui66']['etatdossiercui66'] ){
						$different = true;
						$updateValues[] = array( 'cui66_id' => $data2['Cui66']['id'], 'etatdossiercui66' => $data2['Cui66']['etatdossiercui66'] );
					}
				}
			}
			if ( $different ){
				$result = $result && $this->Historiquepositioncui66->saveMany( $updateValues );
			}
		
			return $result;
		}

		/**
		 * Mise à jour des positions des CUI qui devraient se trouver dans une
		 * position donnée.
		 *
		 * @param integer $etatdossiercui66
		 * @return boolean
		 */
		public function updatePositionsCuisByPosition( $etatdossiercui66 ) {
			$conditions = $this->getConditionsPositioncui( $etatdossiercui66 );

			$query = array( 
				'fields' => array( "{$this->alias}.{$this->primaryKey}" ), 
				'conditions' => $conditions, 
				'joins' => array( $this->join( 'Cui' ) )
			);
			$sample = $this->find( 'first', $query );

			return (
				empty( $sample )
				|| $this->updateAllUnBound(
					array( "{$this->alias}.etatdossiercui66" => "'{$etatdossiercui66}'" ),
					$conditions
				)
			);
		}

		/**
		 * Permet de mettre à jour les positions des CUI d'un allocataire retrouvé
		 * grâce à la clé primaire d'un CUI en particulier.
		 *
		 * @param integer $id La clé primaire d'un CUI.
		 * @return boolean
		 */
		public function updatePositionsCuisById( $id ) {
			$return = $this->updatePositionsCuisByConditions(
				array( "Cui.id" => $id )
			);

			return $return;
		}

		/**
		 * FIXME: doc
		 * 
		 * @param array $data
		 * @return type
		 */
		public function completeDataImpression( array $data ) {
			$cui66_id = Hash::get( $data, 'Cui66.id' );
			$data = array( $data );

			$modeles = array(
				'Propositioncui66',
				'Decisioncui66',
				'Accompagnementcui66',
				'Suspensioncui66',
				'Rupturecui66'
			);
			
			foreach( $modeles as $modele ) {
				$query = $this->{$modele}->getCompleteDataImpressionQuery( $cui66_id );
				$data[$modele] = $this->{$modele}->find( 'all', $query );
			}

			return $data;

		}

	}
?>