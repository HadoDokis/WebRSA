<?php
	class Traitementpdo extends AppModel
	{
		public $name = 'Traitementpdo';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
					'hascourrier',
					'hasrevenu',
					'haspiecejointe',
					'hasficheanalyse',
					'regime',
					'aidesubvreint',
					'dureedepart',
					'dureeecheance'
				)
			),
			'Autovalidate'
		);

		public $validate = array(
			'propopdo_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'descriptionpdo_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'traitementtypepdo_id' => array(
				'numeric' => array(
					'rule' => array('numeric'),
				),
			),
			'datereception' => array(
				'date' => array(
					'rule' => 'date',
					'required' => false,
					'allowEmpty' => false,
					'message' => 'Merci de rentrer une date valide'
				)
			),
			'datedepart' => array(
				'date' => array(
					'rule' => 'date',
					'required' => false,
					'allowEmpty' => false,
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
				'date' => array(
					'rule' => 'date',
					'required' => false,
					'allowEmpty' => false,
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
					'required' => false,
					'allowEmpty' => false
				)
			),
			'raisonsocial' => array(
				array(
					'rule' => 'notEmpty',
					'required' => false,
					'allowEmpty' => false,
					'message' => 'Champ obligatoire'
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
			'dtfinperiode' => array(
				'date' => array(
					'rule' => 'date',
					'required' => false,
					'allowEmpty' => false,
					'message' => 'Merci de rentrer une date valide'
				)
			),
			'dtprisecompte' => array(
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
			'autrecorrection' => array(
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
            )
		);

		public $belongsTo = array(
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => 'propopdo_id',
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
			'Traitementtypepdo' => array(
				'className' => 'Traitementtypepdo',
				'foreignKey' => 'traitementtypepdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $hasOne = array(
			'Saisineepdpdo66' => array(
				'className' => 'Saisineepdpdo66',
				'foreignKey' => 'traitementpdo_id',
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

		public function beforeSave($options = array()) {
			if ((!isset($this->data['Traitementpdo']['daterevision']) || empty($this->data['Traitementpdo']['daterevision']) ) && (!isset($this->data['Traitementpdo']['dateecheance']) || empty($this->data['Traitementpdo']['dateecheance']))) {
				$this->data['Traitementpdo']['clos'] = 1;
			}
			return parent::beforeSave($options);
		}

		public function sauvegardeTraitement($data) {
			$passageEpd = false;

			$dossierep = 0;
			if (isset($data['Traitementpdo']['id']))
				$dossierep = $this->Saisineepdpdo66->find(
					'count',
					array(
						'conditions'=>array(
							'Saisineepdpdo66.traitementpdo_id'=>$data['Traitementpdo']['id']
						)
					)
				);

			if ($dossierep==0 && $data['Traitementpdo']['traitementtypepdo_id']==Configure::read( 'traitementEnCoursId' )) {
				$descriptionpdo = $this->Descriptionpdo->find(
					'first',
					array(
						'conditions'=>array(
							'Descriptionpdo.id'=>$data['Traitementpdo']['descriptionpdo_id']
						),
						'contain'=>false
					)
				);
				$passageEpd = ($descriptionpdo['Descriptionpdo']['declencheep']==1) ? true : false;
			}

			$success = true;

			$has = array('hascourrier', 'hasrevenu', 'haspiecejointe', 'hasficheanalyse');
			foreach ($has as $field) {
				if (empty($data['Traitementpdo'][$field]))
					unset($data['Traitementpdo'][$field]);
			}
			$success = $this->saveAll( $data, array( 'validate' => 'first', 'atomic' => false ) ) && $success;

			if ( isset( $data['Traitementpdo']['traitmentpdoIdClore'] ) && !empty( $data['Traitementpdo']['traitmentpdoIdClore'] ) ) {
				foreach( $data['Traitementpdo']['traitmentpdoIdClore'] as $id => $clore ) {
					if ( $clore==1 ) {
						$success = $this->updateAll(array('Traitementpdo.clos'=>1),array('"Traitementpdo"."id"'=>$id)) && $success;
					}
				}
			}

			if ($passageEpd) {
				$propopdo = $this->Propopdo->find(
					'first',
					array(
						'conditions'=>array(
							'Propopdo.id' => $data['Traitementpdo']['propopdo_id']
						)
					)
				);

				$dataDossierEp = array(
					'Dossierep' => array(
						'personne_id' => $propopdo['Propopdo']['personne_id'],
						'themeep' => 'saisinesepdspdos66'
					)
				);

				$this->Saisineepdpdo66->Dossierep->create( $dataDossierEp );
				$success = $this->Saisineepdpdo66->Dossierep->save() && $success;

				$dataSaisineepdpdo66 = array(
					'Saisineepdpdo66' => array(
						'traitementpdo_id' => $this->id,
						'dossierep_id' => $this->Saisineepdpdo66->Dossierep->id
					)
				);
				$this->Saisineepdpdo66->create( $dataSaisineepdpdo66 );
				$success = $this->Saisineepdpdo66->save() && $success;
			}
			return $success;
		}
	}
?>
