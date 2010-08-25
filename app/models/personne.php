<?php
    class Personne extends AppModel
    {
        var $name = 'Personne';
        var $useTable = 'personnes';

        var $displayField = 'nom_complet';

        var $actsAs = array( 'ValidateTranslate' );
        //---------------------------------------------------------------------

        var $hasOne = array(
            'TitreSejour',
            'Dsp',
            'Infopoleemploi',
            'Dossiercaf',
//             'Avispcgpersonne', // Commentée car problème au niveau de l'ajout de nouveau RDV à une personne
                                    // on se retrouve avec un count(nbPersonnes) > 1 --> invalidParameter
            'Prestation' => array(
                'foreignKey' => 'personne_id',
                'conditions' => array (
                    'Prestation.natprest' => array( 'RSA' )
//                     'Prestation.natprest' => array( 'RSA', 'PFA' )
                )
            ),
            'Calculdroitrsa'
        );

        var $belongsTo = array(
            'Foyer'
        );

        var $hasAndBelongsToMany = array(
            'Actioncandidat' => array( 'with' => 'ActioncandidatPersonne' ),
            'Referent' => array( 'with' => 'PersonneReferent' )
        );

        var $hasMany = array(
            'Contratinsertion' => array(
                'classname' => 'Contratinsertion',
                'foreignKey' => 'personne_id',
            ),
            'Cui' => array(
                'classname' => 'Cui',
                'foreignKey' => 'personne_id',
            ),
            'Orientstruct' => array(
                'classname' => 'Orientstruct',
                'foreignKey' => 'personne_id'
            ),
            'Rendezvous' => array(
                'classname' => 'Rendezvous',
                'foreignKey' => 'personne_id'
            ),
            'Activite' => array(
                'classname' => 'Activite',
                'foreignKey' => 'personne_id',
            ),
            'Apre' => array(
                'classname' => 'Apre',
                'foreignKey' => 'personne_id',
            ),
            'Creancealimentaire' => array(
                'classname' => 'Creancealimentaire',
                'foreignKey' => 'personne_id',
            ),
            'Allocationsoutienfamilial' => array(
                'classname' => 'Allocationsoutienfamilial',
                'foreignKey' => 'personne_id',
            ),
            'Propopdo' => array(
                'classname' => 'Propopdo',
                'foreignKey' => 'personne_id',
            )
        );

        //---------------------------------------------------------------------

        var $validate = array(
            // Qualité
            'qual' => array( array( 'rule' => 'notEmpty' ) ),
            'nom' => array( array( 'rule' => 'notEmpty' ) ),
            'prenom' => array( array( 'rule' => 'notEmpty' ) ),
            'nir' => array(
//                 array(
//                     'rule' => 'isUnique',
//                     'message' => 'Ce NIR est déjà utilisé'
//                 ),
                array(
                    'rule' => array( 'between', 15, 15 ),
                    'message' => 'Le NIR est composé de 15 chiffres'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.',
                    'allowEmpty' => true
                )/*,
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )*/
                // TODO: format NIR
            ),
            'dtnai' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez vérifier le format de la date.'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'rgnai' => array(
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 ),
                array(
                    'rule' => array('comparison', '>', 0 ),
                    'message' => 'Veuillez entrer un nombre positif.',
                    'allowEmpty' => true
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.',
                    'allowEmpty' => true
                )
            ),
            //
//             'nati' => array(
//                 'rule' => 'notEmpty',
//                 'message' => 'Champ obligatoire'
//             ),
//             'dtnati' => array(
//                 'rule' => 'notEmpty',
//                 'message' => 'Champ obligatoire'
//             ),
//             'pieecpres' => array(
//                 'rule' => 'notEmpty',
//                 'message' => 'Champ obligatoire'
//             )
        );

        //*********************************************************************

        function beforeSave( $options = array() ) {
            $return = parent::beforeSave( $options );

			// Mise en majuscule de nom, prénom, nomnai
			foreach( array( 'nom', 'prenom', 'prenom2', 'prenom3', 'nomnai' ) as $field ) {
				if( !empty( $this->data['Personne'][$field] ) ) {
					$this->data['Personne'][$field] = strtoupper( replace_accents( $this->data['Personne'][$field] ) );
				}
			}

            // Champs déduits
            if( !empty( $this->data['Personne']['qual'] ) ) {
                $this->data['Personne']['sexe'] = ( $this->data['Personne']['qual'] == 'MR' ) ? 1 : 2;
            }

            if( $this->data['Personne']['qual'] != 'MME' ) {
                $this->data['Personne']['nomnai'] = $this->data['Personne']['nom'];
            }

            return $return;
        }

        /**
        * Recherche de l'id du dossier à partir de l'id de la personne
        */

        function dossierId( $personne_id ) {
            $this->unbindModelAll();
            $this->bindModel( array( 'belongsTo' => array( 'Foyer' ) ) );
            $personne = $this->find(
                'first',
                array(
                    'fields' => array( 'Foyer.dossier_rsa_id' ),
                    'conditions' => array( 'Personne.id' => $personne_id ),
                    'recursive' => 0
                )
            );

            if( !empty( $personne ) ) {
                return $personne['Foyer']['dossier_rsa_id'];
            }
            else {
                return null;
            }
        }

        //*********************************************************************

        function soumisDroitsEtDevoirs( $personne_id ) {
            $this->unbindModelAll();
            $this->bindModel(
                array(
                    'hasMany' => array(
                        'Ressource' => array(
                            'order' => array( 'dfress DESC' )
                        )
                    ),
                    'hasOne' => array(
                        'Dsp',
                        'Prestation' => array(
                            'foreignKey' => 'personne_id',
                            'conditions' => array (
//                                 'Prestation.natprest' => array( 'RSA', 'PFA' )
                                'Prestation.natprest' => array( 'RSA' )
                            )
                        ),
						'Calculdroitrsa'
                    )
                )
            );

            $personne = $this->findById( $personne_id, null, null, 1 );
            if( isset( $personne['Prestation'] ) && ( $personne['Prestation']['rolepers'] == 'DEM' || $personne['Prestation']['rolepers'] == 'CJT' ) ) {
				$montant = Set::classicExtract( $personne, 'Calculdroitrsa.mtpersressmenrsa' );

                if( $montant < 500 ) {
                    return true;
                }
                else {
                    $montantForfaitaire = $this->Foyer->montantForfaitaire( $personne['Personne']['foyer_id'] );
                    if( $montantForfaitaire ) {
                        return $montantForfaitaire;
                    }
                }

                $dsp = array_filter( array( 'Dsp' => $personne['Dsp'] ) );
                $hispro = Set::extract( $dsp, 'Dsp.hispro' );
                if( $hispro !== NULL ) {
                    // Passé professionnel ? -> Emploi
                    //     1901 : Vous avez toujours travaillé
                    //     1902 : Vous travaillez par intermittence
                    if( $dsp['Dsp']['hispro'] == '1901' || $dsp['Dsp']['hispro'] == '1902' ) {
                        return false;
                    }
                    else {
                        return true;
                    }
                }
            }
            return false;
        }

        //*********************************************************************

        function findByZones( $zonesGeographiques = array(), $filtre_zone_geo = true ) { // TODO
            $this->unbindModelAll();

            $this->bindModel(
                array(
                    'hasOne'=>array(
                        'Adressefoyer' => array(
                            'foreignKey'    => false,
                            'type'          => 'LEFT',
                            'conditions'    => array(
                                '"Adressefoyer"."foyer_id" = "Personne"."foyer_id"',
                                '"Adressefoyer"."rgadr" = \'01\''
                            )
                        ),
                        'Adresse' => array(
                            'foreignKey'    => false,
                            'type'          => 'LEFT',
                            'conditions'    => array(
                                '"Adressefoyer"."adresse_id" = "Adresse"."id"'
                            )
                        )
                    )
                )
            );

            $conditions = array();
            if( $filtre_zone_geo ) {
                $conditions = array( 'Adresse.numcomptt' => ( !empty( $zonesGeographiques ) ? array_values( $zonesGeographiques ) : array() ) );
            }

            $personnes = $this->find( 'all', array ( 'conditions' => $conditions ) );

            $return = Set::extract( $personnes, '{n}.Personne.id' );
            return ( !empty( $return ) ? $return : null );
        }

        /** ********************************************************************
        *    Détails propre à la personne pour le contrat d'insertion
        *** *******************************************************************/

        function detailsCi( $personne_id, $user_id = null ){
            // TODO: début dans le modèle
            ///Recup personne
            $this->unbindModel(
                array(
                    'hasOne' => array( 'TitreSejour', 'Avispcgpersonne', 'Dossiercaf' ),
                    'hasMany' => array( 'Rendezvous', 'Activite', 'Contratinsertion', 'Orientstruct' )
                )
            );
            $this->bindModel( array( 'belongsTo' => array( 'Foyer' ) ) ); // FIXME
            $this->Foyer->unbindModel(
                array(
                    'hasMany' => array( 'Personne', 'Modecontact', 'Adressefoyer' ),
                    'hasAndBelongsToMany' => array( 'Creance' )
                )
            );
            $this->Foyer->Dossier->unbindModelAll();
            $this->Prestation->unbindModelAll();
            $this->Dsp->unbindModelAll();

            $personne = $this->findById( $personne_id, null, null, 2 );

            // Récupération du service instructeur
            $suiviinstruction = $this->Foyer->Dossier->Suiviinstruction->find(
                'first',
                array(
                    'fields' => array_keys( // INFO: champs des tables Suiviinstruction et Serviceinstructeur
                        Set::merge(
                            Set::flatten( array( 'Suiviinstruction' => Set::normalize( array_keys( $this->Foyer->Dossier->Suiviinstruction->schema() ) ) ) ),
                            Set::flatten( array( 'Serviceinstructeur' => Set::normalize( array_keys( ClassRegistry::init( 'Serviceinstructeur' )->schema() ) ) ) )
                        )
                    ),
                    'recursive' => -1,
                    'conditions' => array(
                        'Suiviinstruction.dossier_rsa_id' => $personne['Foyer']['dossier_rsa_id']
                    ),
                    'joins' => array(
                        array(
                            'table'      => 'servicesinstructeurs',
                            'alias'      => 'Serviceinstructeur',
                            'type'       => 'LEFT OUTER',
                            'foreignKey' => false,
                            'conditions' => array( 'Suiviinstruction.numdepins = Serviceinstructeur.numdepins AND Suiviinstruction.typeserins = Serviceinstructeur.typeserins AND Suiviinstruction.numcomins = Serviceinstructeur.numcomins AND Suiviinstruction.numagrins = Serviceinstructeur.numagrins' )
                        )
                    )
                )
            );

            $personne = Set::merge( $personne, $suiviinstruction );

            //On ajout l'ID de l'utilisateur connecté afind e récupérer son service instructeur
            if( empty( $suiviinstruction ) && is_int( $user_id ) ) {
                $user = $this->Contratinsertion->User->findById( $user_id, null, null, 0 );
                $personne = Set::merge( $personne, $user );
            }

            // FIXME -> comment distinguer ? + FIXME autorutitel / autorutiadrelec
            $modecontact = $this->Foyer->Modecontact->find(
                'all',
                array(
                    'conditions' => array(
                        'Modecontact.foyer_id' => $personne['Foyer']['id']
                    ),
                    'recursive' => -1,
                    'order' => 'Modecontact.nattel ASC'
                )
            );

            foreach( $modecontact as $index => $value ) {
                if( ( ( Set::extract( $value, 'Modecontact.autorutitel' ) != 'R' ) /*|| ( Set::extract( $value, 'Modecontact.autorutitel' ) != 'R' )*/ ) && ( Set::extract( $value, 'Modecontact.nattel' ) == 'D' ) ) {
                    $personne['Foyer'] = Set::merge( $personne['Foyer'], array( 'Modecontact' => Set::extract( $modecontact, '{n}.Modecontact' ) ) );
                }
            }

            /// Récupération de l'adresse lié à la personne
            $this->Foyer->Adressefoyer->bindModel(
                array(
                    'belongsTo' => array(
                        'Adresse' => array(
                            'className'     => 'Adresse',
                            'foreignKey'    => 'adresse_id'
                        )
                    )
                )
            );

            $detaildroitrsa = $this->Foyer->Dossier->Detaildroitrsa->find(
                'first',
                array(
                    'conditions' => array(
                        'Detaildroitrsa.dossier_rsa_id' => $personne['Foyer']['Dossier']['id']
                    ),
                    'recursive' => -1
                )
            );//Detaildroitrsa.oridemrsa
            if( !empty( $detaildroitrsa ) ) {
                $personne = Set::merge( $personne, $detaildroitrsa );
            }


            $activite = $this->Activite->find(
                'first',
                array(
                    'conditions' => array(
                        'Activite.personne_id' => $personne_id
                    ),
                    'recursive' => -1,
                    'order' => 'Activite.dfact DESC'
                )
            );//Activite.act
            if( !empty( $activite ) ) {
                $personne = Set::merge( $personne, $activite );

            }


            $adresse = $this->Foyer->Adressefoyer->find(
                'first',
                array(
                    'conditions' => array(
                        'Adressefoyer.foyer_id' => $personne['Personne']['foyer_id'],
                        'Adressefoyer.rgadr' => '01',
                    )
                )
            );
            $personne['Adresse'] = $adresse['Adresse'];

            // Recherche de la structure référente
            $this->Orientstruct->unbindModelAll();
            $this->Orientstruct->bindModel( array( 'belongsTo' => array( 'Structurereferente' ) ) );
            $orientstruct = $this->Orientstruct->find(
                'first',
                array(
                    'conditions' => array(
                        'Orientstruct.personne_id' => $personne_id,
                        'Orientstruct.date_propo IS NOT NULL'
                    ),
                    'order' => 'Orientstruct.date_propo DESC',
                    'recursive' => 0
                )
            );



            if( !empty( $orientstruct ) ) {
                $personne = Set::merge( $personne, $orientstruct );
            }

            return $personne;
        }

        /** ********************************************************************
        *   Détails propre à la personne pour l'APRE
        *** *******************************************************************/

        function detailsApre( $personne_id, $user_id = null ){
            // TODO: début dans le modèle
            ///Recup personne
            $this->unbindModel(
                array(
                    'hasOne' => array( 'TitreSejour', 'Avispcgpersonne', 'Dossiercaf', 'Dsp' ),
                    'hasMany' => array( 'Rendezvous', 'Activite', 'Contratinsertion', 'Orientstruct' , 'Apre' )
                )
            );
            $this->bindModel( array( 'belongsTo' => array( 'Foyer' ) ) ); // FIXME
            $this->Foyer->unbindModel(
                array(
                    'hasMany' => array( 'Personne',/* 'Modecontact',*/ 'Adressefoyer' ),
                    'hasAndBelongsToMany' => array( 'Creance' )
                )
            );
            $this->Foyer->Dossier->unbindModelAll();
            $this->Prestation->unbindModelAll();
            $this->Dsp->unbindModelAll();

            $personne = $this->findById( $personne_id, null, null, 2 );

            /// Recherche des informations financières
//             $infofinancieres = $this->Foyer->Dossier->Infofinanciere->find(
//                 'all',
//                 array(
//                     'recursive' => -1,
//                     'order' => array( 'Infofinanciere.moismoucompta DESC' )
//                 )
//             );
//             $personne['Foyer']['Dossier']['Infofinanciere'] = Set::classicExtract( $infofinancieres, '{n}.Infofinanciere' );

            // FIXME -> comment distinguer ? + FIXME autorutitel / autorutiadrelec
            $modecontact = $this->Foyer->Modecontact->find(
                'all',
                array(
                    'conditions' => array(
                        'Modecontact.foyer_id' => $personne['Foyer']['id']
                    ),
                    'recursive' => -1
                )
            );
// debug($modecontact);
            if( !empty( $modecontact ) ) {
                //$personne['Foyer']['Modecontact'] = $modecontact[0]['Modecontact'];
                $personne['Foyer']['Modecontact'] = Set::extract( $modecontact, '{n}.Modecontact' );
            }

// debug($personne);
            /// Récupération de l'adresse lié à la personne
            $this->Foyer->Adressefoyer->bindModel(
                array(
                    'belongsTo' => array(
                        'Adresse' => array(
                            'className'     => 'Adresse',
                            'foreignKey'    => 'adresse_id'
                        )
                    )
                )
            );

            $adresse = $this->Foyer->Adressefoyer->find(
                'first',
                array(
                    'conditions' => array(
                        'Adressefoyer.foyer_id' => $personne['Personne']['foyer_id'],
                        'Adressefoyer.rgadr' => '01',
                    )
                )
            );
            if( !empty( $adresse ) ) {
                $personne['Adresse'] = $adresse['Adresse'];
            }
// debug($personne);
            // Recherche de la structure référente
            $this->Orientstruct->unbindModelAll();
            $this->Orientstruct->bindModel( array( 'belongsTo' => array( 'Structurereferente' ) ) );
            $orientstruct = $this->Orientstruct->find(
                'first',
                array(
                    'conditions' => array(
                        'Orientstruct.personne_id' => $personne_id,
                        'Orientstruct.date_propo IS NOT NULL'
                    ),
                    'order' => 'Orientstruct.date_propo DESC',
                    'recursive' => 0
                )
            );
            if( !empty( $orientstruct ) ) {
                $personne = Set::merge( $personne, $orientstruct );
            }

            ///Récupération des données propres au contrat d'insertion, notammenrt le premier contrat validé ainsi que le dernier.
            $contrat = $this->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array(
                        'Contratinsertion.personne_id' => $personne['Personne']['id']
                    ),
                    'order' => 'Contratinsertion.datevalidation_ci ASC',
                    'recursive' => -1
                )
            );
            $personne['Contratinsertion']['premier'] = $contrat['Contratinsertion'];

            $contrat = $this->Contratinsertion->find(
                'first',
                array(
                    'conditions' => array(
                        'Contratinsertion.personne_id' => $personne['Personne']['id']
                    ),
                    'order' => 'Contratinsertion.datevalidation_ci DESC',
                    'recursive' => -1
                )
            );
            $personne['Contratinsertion']['dernier'] = $contrat['Contratinsertion'];

            ///Récupération des données Dsp
            $dsp = $this->Dsp->find(
                'first',
                array(
                    'conditions' => array(
                        'Dsp.personne_id' => $personne_id
                    ),
                    'recursive' => -1
                )
            );
            $personne['Dsp'] = $dsp['Dsp'];



            // Récupération du service instructeur
            $suiviinstruction = $this->Foyer->Dossier->Suiviinstruction->find(
                'first',
                array(
                    'fields' => array_keys( // INFO: champs des tables Suiviinstruction et Serviceinstructeur
                        Set::merge(
                            Set::flatten( array( 'Suiviinstruction' => Set::normalize( array_keys( $this->Foyer->Dossier->Suiviinstruction->schema() ) ) ) ),
                            Set::flatten( array( 'Serviceinstructeur' => Set::normalize( array_keys( ClassRegistry::init( 'Serviceinstructeur' )->schema() ) ) ) )
                        )
                    ),
                    'recursive' => -1,
                    'conditions' => array(
                        'Suiviinstruction.dossier_rsa_id' => $personne['Foyer']['dossier_rsa_id']
                    ),
                    'joins' => array(
                        array(
                            'table'      => 'servicesinstructeurs',
                            'alias'      => 'Serviceinstructeur',
                            'type'       => 'LEFT OUTER',
                            'foreignKey' => false,
                            'conditions' => array( 'Suiviinstruction.numdepins = Serviceinstructeur.numdepins AND Suiviinstruction.typeserins = Serviceinstructeur.typeserins AND Suiviinstruction.numcomins = Serviceinstructeur.numcomins AND Suiviinstruction.numagrins = Serviceinstructeur.numagrins' )
                        )
                    )
                )
            );

            $personne = Set::merge( $personne, $suiviinstruction );

            //On ajout l'ID de l'utilisateur connecté afind e récupérer son service instructeur
            if( empty( $suiviinstruction ) && is_int( $user_id ) ) {
                $user = $this->Contratinsertion->User->findById( $user_id, null, null, 0 );
                $personne = Set::merge( $personne, $user );
            }




// debug($personne);
            return $personne;
        }

		/**
		*
		*/

		public $virtualFields = array(
			'nom_complet' => array(
				'type'		=> 'string',
				'postgres'	=> '( "%s"."qual" || \' \' || "%s"."nom" || \' \' || "%s"."prenom" )'
			),
		);
    }
?>