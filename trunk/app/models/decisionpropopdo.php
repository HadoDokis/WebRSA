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
            'Gedooo'
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
				$isvalidation = Set::extract( $this->data, 'Decisionpropopdo.isvalidation' );
				
				$etat = null;
				//'attaffect', 'attinstr', 'instrencours', 'attval', 'decisionval', 'dossiertraite', 'attpj'

				if ( !empty($decisionpdo_id) && empty($decisionpdo_id) )
					$etat = 'attval';
				elseif ( !empty($decisionpdo_id) && !empty($isvalidation) )
					$etat = 'decisionval';
				
				$this->data['Decisionpropopdo']['etatdossierpdo'] = $etat;
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
                    'Personne.qual',
                    'Personne.nom',
                    'Personne.prenom',
                    'Personne.nir'
                ),
                'joins' => $joins,
                'conditions' => $conditions,
                'contain' => false
            );

            $data = $this->find( 'first', $queryData );
// debug($data);
// die();
            $data['Personne']['qual'] = Set::enum( $data['Personne']['qual'], $qual );
            $data['Adresse']['typevoie'] = Set::enum( $data['Adresse']['typevoie'], $typevoie );

            return $data;
        }

        /**
        *
        */

        public function generatePdf( $id ) {
            $gedooo_data = $this->getDataForPdf( $id );

            $modeledoc = "PDO/propositiondecision.odt";

            $pdf = $this->ged( $gedooo_data, $modeledoc );
// debug($gedooo_data);
// die();
            $success = true;

            if( $pdf ) {
                $pdfModel = ClassRegistry::init( 'Pdf' );

                $oldPdf = $pdfModel->find(
                    'first',
                    array(
                        'fields' => array( 'id' ),
                        'conditions' => array(
                            'modele' => 'Decisionpropopdo',
                            'modeledoc' => $modeledoc,
                            'fk_value' => $id
                        )
                    )
                );
                $oldPdf['Pdf']['modele'] = $this->alias;
                $oldPdf['Pdf']['modeledoc'] = $modeledoc;
                $oldPdf['Pdf']['fk_value'] = $id;
                $oldPdf['Pdf']['document'] = $pdf;

                $pdfModel->create( $oldPdf );
                $success = $pdfModel->save() && $success;
            }
            else {
                $success = false;
            }

            return $success;
        }

        /**
        * Enregistrement du Pdf
        */

        public function afterSave( $created ) {
            return $this->generatePdf( $this->id );
        }


	}
?>
