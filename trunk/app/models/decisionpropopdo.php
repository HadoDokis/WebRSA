<?php
	class Decisionpropopdo extends AppModel
	{
		public $name = 'Decisionpropopdo';

		public $actsAs = array(
			'Enumerable' => array(
				'fields' => array(
	 				'validationdecision' => array( 'domain' => 'decisionpropopdo' ),
	 				'etatdossierpdo' => array( 'domain' => 'propopdo' ),
	 				'avistechnique' => array( 'domain' => 'decisionpropopdo' )
				)
			),
			'Formattable',
			'Autovalidate',
            'Gedooo',
            'StorablePdf'
		);

		public $validate = array(
            'decisionpdo_id' => array(
                'rule' => 'notEmpty',
                'message' => 'champ obligatoire'
            ),
			'datedecisionpdo' => array(
				'rule' => 'date',
				'message' => 'Veuillez entrer une date valide.',
				'allowEmpty' => true
			),
// 			'dateavistechnique' => array(
//                 'rule' => array('dateSup'),
//                 'message' => 'Merci de choisir une date supérieure à la date de proposition',
//                 'on' => 'create'
// 			),
// 			'datevalidationdecision' => array(
//                 'rule' => array('dateSup'),
//                 'message' => 'Merci de choisir une date supérieure à la date de l\'avis technique',
//                 'on' => 'create'
// 			)
		);


		public $belongsTo = array(
			'Propopdo' => array(
				'className' => 'Propopdo',
				'foreignKey' => 'propopdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Decisionpdo' => array(
				'className' => 'Decisionpdo',
				'foreignKey' => 'decisionpdo_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);
		
		public function beforeSave( $options = array() ) {
			$return = parent::beforeSave( $options );

			if( Configure::read( 'nom_form_pdo_cg' ) == 'cg66' ) {
				$decisionpdo_id = Set::extract( $this->data, 'Decisionpropopdo.decisionpdo_id' );
				$validationdecision = Set::extract( $this->data, 'Decisionpropopdo.validationdecision' );
				
				$etat = null;
				//'attaffect', 'attinstr', 'instrencours', 'attval', 'decisionval', 'dossiertraite', 'attpj'
				
				$decisionpdo = $this->Decisionpdo->find(
					'first',
					array(
						'conditions' => array(
							'Decisionpdo.id' => $decisionpdo_id
						),
						'contain' => false
					)
				);
				
				if ( isset( $decisionpdo['Decisionpdo']['clos'] ) ) {
					if ( !empty( $decisionpdo_id ) && !is_numeric( $validationdecision ) )
						$etat = 'attval';
					elseif ( !empty( $decisionpdo_id ) && is_numeric( $validationdecision ) && $validationdecision == '1' && $decisionpdo['Decisionpdo']['clos'] == 'O' )
						$etat = 'dossiertraite';
					elseif ( !empty( $decisionpdo_id ) && is_numeric( $validationdecision ) && ( $validationdecision == '0' || $decisionpdo['Decisionpdo']['clos'] == 'N' ) )
						$etat = 'instrencours';
					
					$this->data['Decisionpropopdo']['etatdossierpdo'] = $etat;
				}
				else {
					$return = false;
				}
			}

			return $return;
		}



        /**
        * Récupère les données pour le PDf
        */

        public function getDataForPdf( $id ) {
            // TODO: error404/error500 si on ne trouve pas les données
            $optionModel = ClassRegistry::init( 'Option' );
            $qual = $optionModel->qual();
            $typevoie = $optionModel->typevoie();
            $services = $this->Propopdo->Serviceinstructeur->find( 'list' );
            $typestraitements = $this->Propopdo->Traitementpdo->Traitementtypepdo->find( 'list' );
            $descriptionspdos = $this->Propopdo->Traitementpdo->Descriptionpdo->find( 'list' );
            $conditions = array( 'Decisionpropopdo.id' => $id );

            $joins = array(
                array(
                    'table'      => 'propospdos',
                    'alias'      => 'Propopdo',
                    'type'       => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array( 'Propopdo.id = Decisionpropopdo.propopdo_id' )
                ),
                array(
                    'table'      => 'traitementspdos',
                    'alias'      => 'Traitementpdo',
                    'type'       => 'LEFT OUTER',
                    'foreignKey' => false,
                    'conditions' => array(
                        'Propopdo.id = Traitementpdo.propopdo_id',
                        'Traitementpdo.id IN(
                            '.$this->Propopdo->Traitementpdo->sq(
                                array(
                                    'alias' => 'traitementspdos',
                                    'fields' => array( 'traitementspdos.id' ),
                                    'conditions' => array(
                                        'traitementspdos.propopdo_id = Propopdo.id'
                                    ),
                                    'order' => array( 'traitementspdos.id ASC' ),
                                    'limit' => 1
                                )
                            ).'
                        )'
                    )
                ),
                array(
                    'table'      => 'personnes',
                    'alias'      => 'Personne',
                    'type'       => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array(
                        'Personne.id = Propopdo.personne_id',
                    )
                ),
                array(
                    'table'      => 'foyers',
                    'alias'      => 'Foyer',
                    'type'       => 'INNER',
                    'foreignKey' => false,
                    'conditions' => array( 'Foyer.id = Personne.foyer_id' )
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
                        'Pdf.fk_value = Decisionpropopdo.id'
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
                    'Propopdo.referent_id',
                    'Propopdo.orgpayeur',
                    'Propopdo.datereceptionpdo',
                    'Propopdo.serviceinstructeur_id',
                    'Traitementpdo.traitementtypepdo_id',
                    'Traitementpdo.datereception',
                    'Traitementpdo.id',
                    'Traitementpdo.datedepart',
                    'Traitementpdo.descriptionpdo_id',
                    'Traitementpdo.clos'
                ),
                'joins' => $joins,
                'conditions' => $conditions,
                'contain' => false
            );

            $data = $this->find( 'first', $queryData );

            $data['Personne']['qual'] = Set::enum( $data['Personne']['qual'], $qual );
            $data['Adresse']['typevoie'] = Set::enum( $data['Adresse']['typevoie'], $typevoie );
            $data['Propopdo']['serviceinstructeur_id'] = Set::enum( $data['Propopdo']['serviceinstructeur_id'], $services );
            $data['Traitementpdo']['traitementtypepdo_id'] = Set::enum( $data['Traitementpdo']['traitementtypepdo_id'], $typestraitements );
            $data['Traitementpdo']['descriptionpdo_id'] = Set::enum( $data['Traitementpdo']['descriptionpdo_id'], $descriptionspdos );
// debug($data);
// die();
            return $data;
        }

        /**
        * Retourne le chemin relatif du modèle de document à utiliser pour l'enregistrement du PDF.
        */

        public function modeleOdt( $data ) {
            return "PDO/propositiondecision.odt";
        }



	}
?>
