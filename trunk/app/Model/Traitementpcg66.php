<?php
	/**
	 * Code source de la classe Traitementpcg66.
	 *
	 * PHP 5.3
	 *
	 * @package app.Model
	 * @license CeCiLL V2 (http://www.cecill.info/licences/Licence_CeCILL_V2-fr.html)
	 */

	/**
	 * La classe Traitementpcg66 ...
	 *
	 * @package app.Model
	 */
	class Traitementpcg66 extends AppModel
	{
		public $name = 'Traitementpcg66';

		public $recursive = -1;

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
            'Pgsqlcake.PgsqlAutovalidate'
		);

		public $validate = array(
			'typecourrierpcg66_id' => array(
				array(
					'rule' => array( 'notEmptyIf', 'typetraitement', true, array( 'courrier' ) ),
					'message' => 'Champ obligatoire'
				)
			),
			'personnepcg66_situationpdo_id' => array(
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
//				array(
//					'rule' => array( 'notEmptyIf', 'dureeecheance', false, array( '0' ) ),
//					'message' => 'Champ obligatoire'
//				),
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
//			'dureefinprisecompte' => array(
//				array(
//					'rule' => 'notEmpty',
//					'required' => false,
//					'allowEmpty' => false
//				)
//			),
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
			'Personnepcg66Situationpdo' => array(
				'className' => 'Personnepcg66Situationpdo',
				'foreignKey' => 'personnepcg66_situationpdo_id',
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
		 * FIXME: faire une étape de validation
		 */
		public function sauvegardeTraitement($data) {
			$passageEpd = false;

			$dossierep = 0;
			if (isset($data['Traitementpcg66']['id']) && !empty($data['Traitementpcg66']['id']))
				$dossierep = $this->Saisinepdoep66->find(
					'count',
					array(
						'conditions'=>array(
							'Saisinepdoep66.traitementpcg66_id'=>$data['Traitementpcg66']['id']
						)
					)
				);


			$success = true;

			$has = array('hascourrier', 'hasrevenu', 'haspiecejointe', 'hasficheanalyse');
			foreach ($has as $field) {
				if (empty($data['Traitementpcg66'][$field]))
					unset($data['Traitementpcg66'][$field]);
			}

			$dataTraitementpcg66 = array( 'Traitementpcg66' => $data['Traitementpcg66'] );
			if( $data['Traitementpcg66']['typetraitement'] == 'revenu' ) {
				$dataTraitementpcg66['Traitementpcg66']['dateecheance'] = $data['Traitementpcg66']['daterevision'];
			}
			else {
				unset( $dataTraitementpcg66['Traitementpcg66']['chaffvnt'] );
				unset( $dataTraitementpcg66['Traitementpcg66']['chaffsrv'] );
			}

			$this->create( $dataTraitementpcg66 );
			$success = $this->save() && $success;

			$traitementpcg66_id = $this->id;

			if ( $success && isset( $data['Traitementpcg66']['traitmentpdoIdClore'] ) && !empty( $data['Traitementpcg66']['traitmentpdoIdClore'] ) ) {
				foreach( $data['Traitementpcg66']['traitmentpdoIdClore'] as $id => $clore ) {
					if ( $clore == 'O' ) {
						$success = $this->updateAllUnBound( array( 'Traitementpcg66.clos' => '\'O\'' ), array( '"Traitementpcg66"."id"' => $id ) ) && $success;
					}
				}
			}

			// Sauvegarde des modèles liés au courrier pour un traitement donné
			if( $success && $data['Traitementpcg66']['typetraitement'] == 'courrier' ) {
				// Liste des pièces par modèle de courrier
				$listesPieces = $this->Typecourrierpcg66->Modeletypecourrierpcg66->Piecemodeletypecourrierpcg66->find(
					'list',
					array(
						'conditions' => array(
							'Piecemodeletypecourrierpcg66.modeletypecourrierpcg66_id' => $data['Modeletraitementpcg66']['modeletypecourrierpcg66_id']
						),
						'contain' => false
					)
				);


				$dataModelTraitementpcg66 = array( 'Modeletraitementpcg66' => $data['Modeletraitementpcg66'] );
				$dataModelTraitementpcg66['Modeletraitementpcg66']['traitementpcg66_id'] = $traitementpcg66_id;

				$this->Modeletraitementpcg66->create( $dataModelTraitementpcg66 );
				$success = $this->Modeletraitementpcg66->save() && $success;

				$modeletraitementpcg66_id = $this->Modeletraitementpcg66->id;

				if( !empty( $listesPieces ) ) {
					if( $success ) {
						foreach( array( 'piecesmodelestypescourrierspcgs66' ) as $tableliee ) {
							$modelelie = Inflector::classify( $tableliee );
							$modeleliaison = Inflector::classify( "mtpcgs66_pmtcpcgs66" );
							$foreignkey = Inflector::singularize( $tableliee ).'_id';
							$records = $this->Modeletraitementpcg66->{$modeleliaison}->find(
								'list',
								array(
									'fields' => array( "{$modeleliaison}.id", "{$modeleliaison}.{$foreignkey}" ),
									'conditions' => array(
										"{$modeleliaison}.modeletraitementpcg66_id" => $modeletraitementpcg66_id
									)
								)
							);

							$oldrecordsids = array_values( $records );
							$nouveauxids = Hash::filter( (array)Set::extract( "/{$modelelie}/{$modelelie}", $data ) );


							if ( empty( $nouveauxids ) ) {
								$this->Modeletraitementpcg66->{$modelelie}->invalidate( $modelelie, 'Merci de cocher au moins une case' );
								$success = false;
							}
							else {
								// En moins -> Supprimer
								$idsenmoins = array_diff( $oldrecordsids, $nouveauxids );
								$idsenmoins = array_filter( $idsenmoins );
								if( !empty( $idsenmoins ) ) {
									$success = $this->Modeletraitementpcg66->{$modeleliaison}->deleteAll(
										array(
											"{$modeleliaison}.modeletraitementpcg66_id" => $modeletraitementpcg66_id,
											"{$modeleliaison}.{$foreignkey}" => $idsenmoins
										)
									) && $success;
								}

								// En plus -> Ajouter
								$idsenplus = array_diff( $nouveauxids, $oldrecordsids );
								$idsenplus = array_filter( $idsenplus );

								if( !empty( $idsenplus ) ) {
									foreach( $idsenplus as $idenplus ) {
										$record = array(
											$modeleliaison => array(
												"modeletraitementpcg66_id" => $modeletraitementpcg66_id,
												"{$foreignkey}" => $idenplus
											)
										);

										$this->Modeletraitementpcg66->{$modeleliaison}->create( $record );
										$success = $this->Modeletraitementpcg66->{$modeleliaison}->save() && $success;
									}
								}
							}
						}
					}
				}
				else {
					$success = true;
				}
			}

			// Si aucune date d'échéance, on clôture le traitement automatiquement
			if( $success && !isset( $data['Traitementpcg66']['dateecheance'] ) && $data['Traitementpcg66']['typetraitement'] != 'revenu' ) {
				$success = $this->updateAllUnBound( array( 'Traitementpcg66.clos' => '\'O\'' ), array( '"Traitementpcg66"."id"' => $this->id ) ) && $success;
			}

			// Si la date d'échéance vaut 0 (= aucune), on passe la date à NULL
			if( $success ) {
				if( ( isset ($data['Traitementpcg66']['dureeecheance'] ) && $data['Traitementpcg66']['dureeecheance'] == 0 && $data['Traitementpcg66']['typetraitement'] != 'revenu' ) || ( isset ($data['Traitementpcg66']['dureefinprisecompte'] ) && $data['Traitementpcg66']['dureefinprisecompte'] == 0 && $data['Traitementpcg66']['typetraitement'] == 'revenu' ) ) {
					$success = $this->updateAllUnBound( array( 'Traitementpcg66.dateecheance' => NULL ), array( '"Traitementpcg66"."id"' => $this->id ) ) && $success;
				}
			}

            // Mise à jour de l'état du dossier PCG selon le type de traitement enregistré
            // Soit un traitement de type Document arrivé
            // Soit un traitement de description = Courrier à l'allocataire
            $corbeillepcgDescriptionId = Configure::read( 'Corbeillepcg.descriptionpdoId' ); // Traiteement de description courrier à l'allocataire
            if( $success ) {
				if( ( $data['Traitementpcg66']['typetraitement'] == 'documentarrive' ) || in_array( $data['Traitementpcg66']['descriptionpdo_id'], $corbeillepcgDescriptionId ) ) {
                    $success = $this->Personnepcg66->Dossierpcg66->updateEtatDossierViaTraitement( $this->id ) && $success;
				}
            }

			return $success;
		}


		/**
		* Récupère les données pour le PDf
		*/

		public function getPdfFichecalcul( $id ) {
			// TODO: error404/error500 si on ne trouve pas les données
			$optionModel = ClassRegistry::init( 'Option' );
			$qual = $optionModel->qual();
			$typevoie = $optionModel->typevoie();
			$services = $this->Personnepcg66Situationpdo->Personnepcg66->Dossierpcg66->Serviceinstructeur->find( 'list' );
			$decisionspdos = $this->Personnepcg66Situationpdo->Decisionpersonnepcg66->Decisionpdo->find( 'list' );
			$situationspdos = $this->Personnepcg66Situationpdo->Situationpdo->find( 'list' );
			$conditions = array( 'Traitementpcg66.id' => $id );

			$joins = array(
				array(
					'table'      => 'personnespcgs66',
					'alias'      => 'Personnepcg66',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Personnepcg66.id = Traitementpcg66.personnepcg66_id' )
				),
				array(
					'table'      => 'personnespcgs66_situationspdos',
					'alias'      => 'Personnepcg66Situationpdo',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Personnepcg66.id = Personnepcg66Situationpdo.personnepcg66_id' )
				),
				array(
					'table'      => 'dossierspcgs66',
					'alias'      => 'Dossierpcg66',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Dossierpcg66.id = Personnepcg66.dossierpcg66_id' )
				),
				array(
					'table'      => 'users',
					'alias'      => 'User',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'User.id = Dossierpcg66.user_id' )
				),
				array(
					'table'      => 'personnes',
					'alias'      => 'Personne',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Personne.id = Personnepcg66.personne_id' )
				),
				array(
					'table'      => 'foyers',
					'alias'      => 'Foyer',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Foyer.id = Dossierpcg66.foyer_id' )
				),
				array(
					'table'      => 'dossiers',
					'alias'      => 'Dossier',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Dossier.id = Foyer.dossier_id' )
				),
				array(
					'table'      => 'adressesfoyers',
					'alias'      => 'Adressefoyer',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array(
						'Foyer.id = Adressefoyer.foyer_id',
						'Adressefoyer.rgadr' => '01'
					)
				),
				array(
					'table'      => 'adresses',
					'alias'      => 'Adresse',
					'type'       => 'INNER',
					'foreignKey' => false,
					'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
				),
				array(
					'table'      => 'pdfs',
					'alias'      => 'Pdf',
					'type'       => 'LEFT OUTER',
					'foreignKey' => false,
					'conditions' => array(
						'Pdf.modele' => $this->alias,
						'Pdf.fk_value = Traitementpcg66.id'
					)
				),
			);

			$queryData = array(
				'fields' => array(
					'Adresse.numvoie',
					'Adresse.typevoie',
					'Adresse.nomvoie',
					'Adresse.complideadr',
					'Adresse.compladr',
					'Adresse.lieudist',
					'Adresse.numcomrat',
					'Adresse.numcomptt',
					'Adresse.codepos',
					'Adresse.locaadr',
					'Adresse.pays',
					'Dossier.numdemrsa',
					'Dossier.dtdemrsa',
					'Dossier.matricule',
					'Personne.qual',
					'Personne.nom',
					'Personne.prenom',
					'Personne.dtnai',
					'Personne.nir',


				),
				'joins' => $joins,
				'conditions' => $conditions,
				'contain' => false
			);

			$data = $this->find( 'first', $queryData );

			$data['Personne']['qual'] = Set::enum( $data['Personne']['qual'], $qual );
			$data['Adresse']['typevoie'] = Set::enum( $data['Adresse']['typevoie'], $typevoie );
// 			$data['Dossierpcg66']['serviceinstructeur_id'] = Set::classicExtract( $services, $data['Dossierpcg66']['serviceinstructeur_id'] );


// debug($data);
// die();
			return $this->ged(
				$data,
				"PCG66/fichecalcul.odt",
				true,
				array()
			);
		}


		/**
		* Récupère les données pour le PDf
		*/

		public function getPdfModeleCourrier( $id, $user_id) {

			$joins = array(
				$this->join( 'Personnepcg66' ),
				$this->Personnepcg66->join( 'Personnepcg66Situationpdo' ),
				$this->Personnepcg66->join( 'Dossierpcg66' ),
				$this->Personnepcg66->join( 'Personne' ),
				$this->Personnepcg66->Personne->join( 'Bilanparcours66', array( 'type' => 'LEFT OUTER' ) ),
				$this->Personnepcg66->Personne->join( 'Orientstruct', array( 'type' => 'LEFT OUTER' ) ),
				$this->Personnepcg66->Personne->Orientstruct->join( 'Structurereferente', array( 'type' => 'LEFT OUTER' ) ),
				$this->Personnepcg66->Dossierpcg66->join( 'Foyer' ),
				$this->Personnepcg66->Dossierpcg66->Foyer->join( 'Dossier' ),
				$this->Personnepcg66->Dossierpcg66->Foyer->join( 'Adressefoyer', array( 'type' => 'LEFT OUTER' ) ),
				$this->Personnepcg66->Dossierpcg66->Foyer->Adressefoyer->join( 'Adresse', array( 'type' => 'LEFT OUTER' ) ),
				$this->join( 'Modeletraitementpcg66' ),
				$this->Modeletraitementpcg66->join( 'Modeletypecourrierpcg66' ),
				$this->join( 'Serviceinstructeur' )
			);

			$conditions = array(
				'Traitementpcg66.id' => $id,
				'OR' => array(
					'Orientstruct.id IS NULL',
					'Orientstruct.id IN ( '.$this->Personnepcg66->Personne->Orientstruct->sqDerniere( 'Orientstruct.personne_id' ).' )'
				),
				'OR' => array(
					'Adressefoyer.id IS NULL',
					'Adressefoyer.id IN ( '.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01( 'Adressefoyer.foyer_id' ).' )'
				)
			);

			$queryData = array(
				'fields' => array_merge(
					$this->fields(),
					$this->Modeletraitementpcg66->fields(),
					$this->Modeletraitementpcg66->Modeletypecourrierpcg66->fields(),
					$this->Personnepcg66->Personne->Bilanparcours66->fields(),
					$this->Personnepcg66->Personne->Orientstruct->fields(),
					$this->Personnepcg66->Personne->Orientstruct->Structurereferente->fields(),
					$this->Personnepcg66->Personne->Bilanparcours66->fields(),
					array(
						'Adresse.numvoie',
						'Adresse.typevoie',
						'Adresse.nomvoie',
						'Adresse.complideadr',
						'Adresse.compladr',
						'Adresse.lieudist',
						'Adresse.numcomrat',
						'Adresse.numcomptt',
						'Adresse.codepos',
						'Adresse.locaadr',
						'Adresse.pays',
						'Dossier.numdemrsa',
						'Dossier.dtdemrsa',
						'Dossier.matricule',
						'Personne.qual',
						'Personne.nom',
						'Personne.prenom',
						'Dossierpcg66.user_id',
						'Dossierpcg66.orgpayeur',
						'Personne.dtnai',
						'Personne.nir',
					),
					$this->Serviceinstructeur->fields()
				),
				'joins' => $joins,
				'conditions' => $conditions,
				'contain' => false
			);

			$data = $this->find( 'first', $queryData );

			$user = $this->User->find(
				'first',
				array(
					'conditions' => array(
						'User.id' => $user_id
					),
					'contain' => false
				)
			);
			$data = Set::merge( $data, $user );

			$modeleodtname = Set::classicExtract( $data, 'Modeletypecourrierpcg66.modeleodt' );

			// Ajout Florent CG66
			$gestionnaire['Dossierpcg66'] = $this->User->find(
				'first',
				array(
					'fields' => array(
                        $this->User->sqVirtualField( 'nom_complet' )
                    ),
					'conditions' => array(
						'User.id' => $data['Dossierpcg66']['user_id']
					),
					'contain' => false
					)
				);

			$data = Set::merge( $data, $gestionnaire );
			// Fin Ajout Florent CG66

			$options = array(
				'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ),
				'Adresse' => array( 'typevoie' => ClassRegistry::init( 'Option' )->typevoie() ),
				'type' => array( 'voie' => ClassRegistry::init( 'Option' )->typevoie() )
			);
			$options = Set::merge( $options, $this->enums() );

			$modeletraitementpcg66_id = Set::classicExtract( $data, 'Modeletraitementpcg66.id' );
			$piecesmanquantes = $this->Modeletraitementpcg66->find(
				'all',
				array(
					'fields' => array_merge(
						$this->Modeletraitementpcg66->Piecemodeletypecourrierpcg66->fields()
					),
					'contain' => false,
					'joins' => array(
						$this->Modeletraitementpcg66->join( 'Mtpcg66Pmtcpcg66', array( 'type' => 'INNER' ) ),
						$this->Modeletraitementpcg66->Mtpcg66Pmtcpcg66->join( 'Piecemodeletypecourrierpcg66', array( 'type' => 'INNER' ) )
					),
					'conditions' => array(
						'Mtpcg66Pmtcpcg66.modeletraitementpcg66_id' => $modeletraitementpcg66_id
					)
				)
			);

			// Dates calculées sur les 3 mois suivants la date de début de prise en compte du courrier
			$datedebutCourrier = $data['Modeletraitementpcg66']['montantdatedebut'];
			if( !empty( $datedebutCourrier ) ) {
				$datedebutCourrier = strtotime( $datedebutCourrier );
				foreach( array( '0', '1', '2' ) as $i ) {
					$data['Modeletraitementpcg66']["moisprisencompte$i"] = date("Y-m-d", strtotime("+". $i ." months", $datedebutCourrier));
				}
			}
// debug($piecesmanquantes);
// debug($data);
// die();

			return $this->ged(
				array(
					$data,
					'Piecesmanquantes' => $piecesmanquantes
				),
				"PCG66/Traitementpcg66/{$modeleodtname}.odt",
				true,
				$options
			);
		}

		/**
		*	Sous-requête afin d'obtenir la liste des traitements PCG de la personne PCG
		*	liée au dossier PCG
		*/
		public function sqListeTraitementpcg66( $personnepcg66IdFied = 'Personnepcg66.id' ) {
// 			return $this->sq(
// 				array(
// 					'fields' => array(
// 						'traitementspcgs66.id'
// 					),
// 					'alias' => 'traitementspcgs66',
// 					'conditions' => array(
// 						"traitementspcgs66.personnepcg66_id = {$personnepcg66IdFied}"
// 					),
// 					'order' => array( 'traitementspcgs66.daterecpetion DESC' )
// 				)
// 			);
		}


		/**
		*	Sous-requête afin d'obtenir la liste des traitements PCG
		*		- non clos
		*		- et dont la date d'échéance est dépassée
		*/
		public function sqTraitementpcg66Echu( $personnepcg66IdFied = 'Personnepcg66.id' ) {
			return $this->sq(
				array(
					'fields' => array(
						'traitementspcgs66.id'
					),
					'alias' => 'traitementspcgs66',
					'conditions' => array(
						"traitementspcgs66.personnepcg66_id = {$personnepcg66IdFied}",
						'traitementspcgs66.clos' => 'N',
						"traitementspcgs66.dateecheance < NOW()",
					),
					'order' => array( 'traitementspcgs66.datereception DESC' )
				)
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
         * Fonction permettant de récupérer les informations de la dernière
         *  fiche de calcul parmi les différents traitements PCGs d'une personne
         *
         * @param type $personneId
         * @param type $action
         * @param type $data
         * @return type
         */
        public function infoDerniereFicheCalcul( $personneId = 'Personne.id', $action, $data = array() ) {

            if( !empty( $personneId ) ) {
                $querydata = array(
                    'fields' => array(
                        'Traitementpcg66.nrmrcs',
                        'Traitementpcg66.dtdebutactivite',
                        'Traitementpcg66.regime',
                        'Traitementpcg66.raisonsocial',
                        'Traitementpcg66.created'
                    ),
                    'joins' => array(
                        $this->Personnepcg66->join( 'Traitementpcg66', array( 'type' => 'INNER' ) )
                    ),
                    'contain' => false,
                    'conditions' => array(
                        'Personnepcg66.personne_id' => $personneId,
                        'Traitementpcg66.typetraitement' => 'revenu'
                    ),
                    'order' => array( 'Traitementpcg66.created DESC' )
                );

				$dataPersonnepcg66 = $this->Personnepcg66->find( 'first', $querydata );

                $data = $dataPersonnepcg66;

            }

            return $data;
        }
	}
?>