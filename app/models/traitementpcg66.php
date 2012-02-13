<?php
	class Traitementpcg66 extends AppModel
	{
		public $name = 'Traitementpcg66';

		public $recursive = -1;

		public $actsAs = array(
			'Autovalidate',
			'ValidateTranslate',
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
					'annule',
					'typetraitement'
				)
			),
			'Gedooo'
		);

		public $validate = array(
			'propopdo_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => 'Merci de saisir une valeur numérique'
				),
			),
			'descriptionpdo_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
					'message' => 'Merci de saisir une valeur numérique'
				),
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
					'allowEmpty' => false,
					'message' => 'Merci de rentrer une date valide'
				)
			),
			'dateecheance' => array(
				array(
					'rule' => array( 'notEmptyIf', 'dureeecheance', false, array( '0' ) ),
					'message' => 'Champ obligatoire'
				),
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
				)
			),
			'dtfinprisecompte' => array(
				'date' => array(
					'rule' => 'date',
					'required' => false,
					'allowEmpty' => false,
					'message' => 'Merci de rentrer une date valide'
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
			'dureefinprisecompte' => array(
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
// 			'Traitementtypepdo' => array(
// 				'className' => 'Traitementtypepdo',
// 				'foreignKey' => 'traitementtypepdo_id',
// 				'conditions' => '',
// 				'fields' => '',
// 				'order' => ''
// 			),
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
			)
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

		public function sauvegardeTraitement($data) {
			$passageEpd = false;

			//Sauvegarde des couriers liés à un traitement si présents
			if( isset( $data['Courrierpdo'] ) ){
				$dataCourrierIds = Set::extract( $data, '/Courrierpdo[checked=1]/id' );
				if( count( $dataCourrierIds ) != 0 ){
					$dataContenutextareacourrierpdo = $data['Contenutextareacourrierpdo'];
					unset( $data['Courrierpdo'], $data['Contenutextareacourrierpdo'] );
				}
			}

			$dossierep = 0;
			if (isset($data['Traitementpcg66']['id']))
				$dossierep = $this->Saisinepdoep66->find(
					'count',
					array(
						'conditions'=>array(
							'Saisinepdoep66.traitementpcg66_id'=>$data['Traitementpcg66']['id']
						)
					)
				);

			///FIXME : remettre en place dès que l'on saura quoi faire des passages en EPD
			/*if ($dossierep==0 && $data['Traitementpcg66']['traitementtypepdo_id']==Configure::read( 'traitementEnCoursId' )) {
				$descriptionpdo = $this->Descriptionpdo->find(
					'first',
					array(
						'conditions'=>array(
							'Descriptionpdo.id'=>$data['Traitementpcg66']['descriptionpdo_id']
						),
						'contain'=>false
					)
				);
				$passageEpd = ($descriptionpdo['Descriptionpdo']['declencheep']==1) ? true : false;
			}*/

			$success = true;

			$has = array('hascourrier', 'hasrevenu', 'haspiecejointe', 'hasficheanalyse');
			foreach ($has as $field) {
				if (empty($data['Traitementpcg66'][$field]))
					unset($data['Traitementpcg66'][$field]);
			}
			$success = $this->saveAll( $data, array( 'validate' => 'first', 'atomic' => false ) ) && $success;

			$traitementpcg66_id = $this->id;
			if( $success && !empty( $dataCourrierIds ) ){
				foreach( $dataCourrierIds as $dataCourrierId ){
					$dataCourrierpdoTraitementpcg66 = array( 'CourrierpdoTraitementpcg66' => array( 'courrierpdo_id' => $dataCourrierId, 'traitementpcg66_id' => $traitementpcg66_id ) );
					$this->CourrierpdoTraitementpcg66->create( $dataCourrierpdoTraitementpcg66 );
					$success = $this->CourrierpdoTraitementpcg66->save() && $success;

					if( $success ){
						foreach( array_keys( $dataContenutextareacourrierpdo ) as $key ) {
							$dataContenutextareacourrierpdo[$key]['courrierpdo_traitementpcg66_id'] = $this->CourrierpdoTraitementpcg66->id;
						}
						$success = $this->CourrierpdoTraitementpcg66->Contenutextareacourrierpdo->saveAll( $dataContenutextareacourrierpdo, array( 'atomic' => false ) ) && $success;
					}
				}
			}

			if ( $success && isset( $data['Traitementpcg66']['traitmentpdoIdClore'] ) && !empty( $data['Traitementpcg66']['traitmentpdoIdClore'] ) ) {
				foreach( $data['Traitementpcg66']['traitmentpdoIdClore'] as $id => $clore ) {
					if ( $clore == 'O' ) {
						$success = $this->updateAll( array( 'Traitementpcg66.clos' => '\'O\'' ), array( '"Traitementpcg66"."id"' => $id ) ) && $success;
					}
				}
			}

// 			if ( $success ) {
// 				$success = $this->Personnepcg66->Dossierpcg66->updateEtatViaTraitement( $traitementpcg66_id ) && $success;
// 			}

			///TODO: changer l'état du dossier

			///FIXME : remettre en place dès que l'on saura quoi faire des passages en EPD
			/*if ($passageEpd) {
				$propopdo = $this->Propopdo->find(
					'first',
					array(
						'conditions'=>array(
							'Propopdo.id' => $data['Traitementpcg66']['propopdo_id']
						)
					)
				);

				$dataDossierEp = array(
					'Dossierep' => array(
						'personne_id' => $propopdo['Propopdo']['personne_id'],
						'themeep' => 'saisinespdoseps66'
					)
				);

				$this->Saisinepdoep66->Dossierep->create( $dataDossierEp );
				$success = $this->Saisinepdoep66->Dossierep->save() && $success;

				$dataSaisineepdpdo66 = array(
					'Saisinepdoep66' => array(
						'traitementpcg66_id' => $this->id,
						'dossierep_id' => $this->Saisinepdoep66->Dossierep->id
					)
				);
				$this->Saisinepdoep66->create( $dataSaisineepdpdo66 );
				$success = $this->Saisinepdoep66->save() && $success;
			}*/
			return $success;
		}




		/**
		* Récupère les données pour le PDf
		*/

		public function getPdfDecision( $id ) {
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

	}
?>