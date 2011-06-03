<?php
	class ActioncandidatPersonne extends AppModel
	{
		public $name = 'ActioncandidatPersonne';

		public $belongsTo = array(
			'Personne' => array(
				'className' => 'Personne',
				'foreignKey' => 'personne_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			),
			'Actioncandidat' => array(
				'className' => 'Actioncandidat',
				'foreignKey' => 'actioncandidat_id',
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
			'Motifsortie' => array(
				'className' => 'Motifsortie',
				'foreignKey' => 'motifsortie_id',
				'conditions' => '',
				'fields' => '',
				'order' => ''
			)
		);

		public $actsAs = array (
			'Nullable',
			'ValidateTranslate',
			'Enumerable' => array(
				'fields' => array(
					'enattente' => array(
						'values' => array( 'O', 'N' )
					),
					'bilanvenu' => array(
						'values' => array( 'VEN', 'NVE' ),
						'domain' => 'actioncandidat_personne'
					),
					'bilanretenu' => array(
						'values' => array( 'RET', 'NRE' ),
						'domain' => 'actioncandidat_personne'
					),
					'bilanrecu' => array(
						'values' => array( 'O', 'N' ),
						'domain' => 'actioncandidat_personne'
					),
					'presencecontrat' => array(
						'values' => array( 'O', 'N' ),
						'domain' => 'actioncandidat_personne'
					),
					'pieceallocataire' => array(
						'values' => array( 'CER', 'NCA', 'CV', 'AUT' ),
						'domain' => 'actioncandidat_personne'
					),
					'integrationaction' => array(
						'values' => array( 'O', 'N' ),
						'domain' => 'actioncandidat_personne'
					),
                    'positionfiche' => array(
                        'domain' => 'actioncandidat_personne'
                    )
				)
			),
			'Formattable',
			'Gedooo',
			'Autovalidate'
		);


		public $validate = array(
			'personne_id' => array(
				array( 'rule' => 'notEmpty' )
			),
			'referent_id' => array(
				array( 'rule' => 'notEmpty' )
			),
			'actioncandidat_id' => array(
				array( 'rule' => 'notEmpty' )
			),
//			'enattente'  => array(
//				'rule' => 'notEmpty',
//				'message' => 'Champ obligatoire'
//			),
			'nivetu'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'bilanvenu'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'bilanretenu'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'bilanrecu'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'integrationaction'  => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'pieceallocataire' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'horairerdvpartenaire' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire',
				'required' => false
			),
			'ddaction' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'motifdemande' => array(
				'rule' => 'notEmpty',
				'message' => 'Champ obligatoire'
			),
			'motifsortie_id' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            )
		);


        /**
        *   BeforeSave
        */

        public function beforeSave( $options = array() ) {
            $return = parent::beforeSave( $options );
            //  Calcul de la position de la fiche de calcul
            $this->data[$this->alias]['positionfiche'] = $this->_calculPosition( $this->data );

            return $return;
        }


        protected function _calculPosition( $data ){

            $bilanrecu = Set::classicExtract( $data, 'ActioncandidatPersonne.bilanvenu' );
            $bilanretenu = Set::classicExtract( $data, 'ActioncandidatPersonne.bilanretenu' );
            $issortie = Set::classicExtract( $data, 'ActioncandidatPersonne.issortie' );

            $positionfiche = null;
            // enattente,encours,nonretenue,sortie,annule


            if ( empty( $bilanrecu ) && empty( $bilanretenu ) && empty( $motifsortie ) ){
                $positionfiche = 'enattente';
            }
            elseif ( !empty( $bilanrecu ) && ( $bilanretenu == 'NRE' ) && empty( $issortie ) ){
                $positionfiche = 'nonretenue';
            }
            elseif ( !empty( $bilanrecu ) && ( $bilanretenu != 'NRE' ) && empty( $issortie ) ){
                $positionfiche = 'encours';
            }
            elseif ( !empty( $bilanrecu ) && ( $bilanretenu != 'NRE' ) && !empty( $issortie ) ){
                $positionfiche = 'sortie';
            }

            return $positionfiche;
        }


        /**
        *
        */

        public function getPdfFiche( $actioncandidat_personne_id ) {

//             $actioncandidat_personne_data = $this->find(
//                 'first',
//                 array(
//                     'conditions' => array(
//                         'ActioncandidatPersonne.id' => $actioncandidat_personne_id
//                     ),
//                     'contain' => false
//                 )
//             );

            $queryData = array(
                'fields' => array(
                    'Actioncandidat.name',
                    'Actioncandidat.themecode',
                    'Actioncandidat.codefamille',
                    'Actioncandidat.numcodefamille',
                    'Actioncandidat.correspondantaction',
                    'Actioncandidat.contractualisation',
                    'Actioncandidat.referent_id',
                    'Actioncandidat.lieuaction',
                    'Actioncandidat.cantonaction',
                    'Actioncandidat.ddaction',
                    'Actioncandidat.dfaction',
                    'ActioncandidatPersonne.sortiele',
                    'ActioncandidatPersonne.datebilan',
                    'ActioncandidatPersonne.infocomplementaire',
                    'ActioncandidatPersonne.bilanvenu',
                    'ActioncandidatPersonne.bilanretenu',
                    'ActioncandidatPersonne.mobile',
                    'ActioncandidatPersonne.rendezvouspartenaire',
                    'ActioncandidatPersonne.horairerdvpartenaire',
                    'ActioncandidatPersonne.positionfiche',
                    'ActioncandidatPersonne.naturemobile',
                    'ActioncandidatPersonne.typemobile',
                    'ActioncandidatPersonne.motifsortie_id',
                    'Partenaire.libstruc',
                    'Partenaire.typevoie',
                    'Partenaire.numvoie',
                    'Partenaire.nomvoie',
                    'Partenaire.compladr',
                    'Partenaire.codepostal',
                    'Partenaire.ville',
                    'Partenaire.numtel',
                    'Partenaire.numfax',
                    'Contactpartenaire.numtel',
                    'Contactpartenaire.numfax',
                    'Referent.qual',
                    'Referent.nom',
                    'Referent.prenom',
                    'Personne.qual',
                    'Personne.nom',
                    'Personne.prenom',
                    'Personne.dtnai',
                    'Personne.typedtnai',
                    'Personne.nir',
                    'Personne.idassedic',
                    'Personne.numfixe',
                    'Personne.numport',
                    'Adresse.numvoie',
                    'Adresse.typevoie',
                    'Adresse.nomvoie',
                    'Adresse.compladr',
                    'Adresse.locaadr',
                    'Adresse.numcomptt',
                    'Adresse.codepos',
                ),
                'joins' => array(
                    array(
                        'table'      => 'actionscandidats',
                        'alias'      => 'Actioncandidat',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Actioncandidat.id = ActioncandidatPersonne.actioncandidat_id'
                        ),
                    ),
                    array(
                        'table'      => 'contactspartenaires',
                        'alias'      => 'Contactpartenaire',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Contactpartenaire.id = Actioncandidat.contactpartenaire_id' ),
                    ),
                    array(
                        'table'      => 'partenaires',
                        'alias'      => 'Partenaire',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Partenaire.id = Contactpartenaire.partenaire_id' ),
                    ),
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( "ActioncandidatPersonne.personne_id = Personne.id" ),
                    ),
                    array(
                        'table'      => 'referents',
                        'alias'      => 'Referent',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Referent.id = ActioncandidatPersonne.referent_id' ),
                    ),
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.foyer_id = Foyer.id' )
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
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Foyer.id = Adressefoyer.foyer_id',
                            'Adressefoyer.id IN (
                                '.ClassRegistry::init( 'Adressefoyer' )->sqDerniereRgadr01('Adressefoyer.foyer_id').'
                            )'
                        )
                    ),
                    array(
                        'table'      => 'adresses',
                        'alias'      => 'Adresse',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                    )
                ),
                'conditions' => array(
                    'ActioncandidatPersonne.id' => $actioncandidat_personne_id
                ),
                'recursive' => -1
            );

            $options = array( 'Personne' => array( 'qual' => ClassRegistry::init( 'Option' )->qual() ) );
            $options = Set::insert( $options, 'ActioncandidatPersonne.naturemobile', $this->Personne->Dsp->Detailnatmob->enumList( 'natmob' ) );


            $options = Set::merge( $options, $this->enums() );
//             $options = Set::merge( $options, $this->Passagecommissionep->Dossierep->enums() );
//             $options = Set::merge( $options, $this->Membreep->enums() );
//             $options = Set::merge( $options, $this->CommissionepMembreep->enums() );

            $actioncandidat = $this->find( 'first', $queryData );
            $referents = $this->Referent->find( 'list' );
            $motifssortie = ClassRegistry::init( 'Motifsortie' )->find( 'list' );

            $correspondantaction = Set::classicExtract( $actioncandidat, 'Actioncandidat.correspondantaction' );

            if( !empty( $correspondantaction ) ){
                $actioncandidat['Actioncandidat']['correspondantaction_nom_complet'] = Set::enum( $actioncandidat['Actioncandidat']['referent_id'],  $referents );
            }
            $actioncandidat['Actioncandidat']['codeaction'] = Set::classicExtract( $actioncandidat, 'Actioncandidat.themecode' ).' '. Set::classicExtract( $actioncandidat, 'Actioncandidat.codefamille' ).' '.Set::classicExtract( $actioncandidat, 'Actioncandidat.numcodefamille' );



            $actioncandidat['ActioncandidatPersonne']['motifsortie_id'] = Set::enum( Set::classicExtract( $actioncandidat, 'ActioncandidatPersonne.motifsortie_id' ), $motifssortie ); 
//             debug($options);
// debug($actioncandidat);
// die();
            return $this->ged( array( $actioncandidat ), "Candidature/fichecandidature.odt", true, $options );
        }



	}
?>