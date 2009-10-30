<?php
    class Personne extends AppModel
    {
        var $name = 'Personne';
        var $useTable = 'personnes';

        //---------------------------------------------------------------------

        var $hasOne = array(
            'TitreSejour',
            'Dspp',
            'Infopoleemploi',
            'Dossiercaf',
            'Avispcgpersonne',
            'Prestation' => array(
                'foreignKey' => 'personne_id',
                'conditions' => array (
                    'Prestation.natprest' => array( 'RSA' )
//                     'Prestation.natprest' => array( 'RSA', 'PFA' )
                )
            )
        );

        var $belongsTo = array(
            'Foyer'
        );

        var $hasMany = array(
            'Contratinsertion' => array(
                'classname' => 'Contratinsertion',
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
        );

        //---------------------------------------------------------------------

        var $validate = array(
            // Qualité
            'qual' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'nom' => array(
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'prenom' => array(
                'rule' => 'notEmpty',
                'message' => 'Champ obligatoire'
            ),
//             'nir' => array(
//                 array(
//                     'rule' => 'isUnique',
//                     'message' => 'Ce NIR est déjà utilisé'
//                 ),
//                 array(
//                     'rule' => array( 'between', 15, 15 ),
//                     'message' => 'Le NIR est composé de 15 chiffres'
//                 ),
//                 array(
//                     'rule' => 'numeric',
//                     'message' => 'Veuillez entrer une valeur numérique.',
//                     'allowEmpty' => true
//                 ),
//                 array(
//                     'rule' => 'notEmpty',
//                     'message' => 'Champ obligatoire'
//                 )
//                 // TODO: format NIR
//             ),
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

        //*********************************************************************

        function dossierId( $personne_id ) {
            $this->unbindModelAll();
            $this->bindModel( array( 'belongsTo' => array( 'Foyer' ) ) );
            $personne = $this->findById( $personne_id, null, null, 0 );
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
                        'Dspp',
                        'Prestation' => array(
                            'foreignKey' => 'personne_id',
                            'conditions' => array (
//                                 'Prestation.natprest' => array( 'RSA', 'PFA' )
                                'Prestation.natprest' => array( 'RSA' )
                            )
                        )
                    )
                )
            );

            $personne = $this->findById( $personne_id, null, null, 1 );
            if( isset( $personne['Prestation'] ) && ( $personne['Prestation']['rolepers'] == 'DEM' || $personne['Prestation']['rolepers'] == 'CJT' ) ) {
                if( isset( $personne['Ressource'] ) && isset( $personne['Ressource'][0] ) && isset( $personne['Ressource'][0]['mtpersressmenrsa'] ) ) {
                    $montant = $personne['Ressource'][0]['mtpersressmenrsa'];
                }
                else {
                    $montant = 0;
                }

                if( $montant < 500 ) {
                    return true;
                }
                else {
                    $montantForfaitaire = $this->Foyer->montantForfaitaire( $personne['Personne']['foyer_id'] );
                    if( $montantForfaitaire ) {
                        return $montantForfaitaire;
                    }
                }

                $dspp = array_filter( array( 'Dspp' => $personne['Dspp'] ) );
                $hispro = Set::extract( $dspp, 'Dspp.hispro' );
                if( $hispro !== NULL ) {
                    // Passé professionnel ? -> Emploi
                    //     1901 : Vous avez toujours travaillé
                    //     1902 : Vous travaillez par intermittence
                    if( $dspp['Dspp']['hispro'] == '1901' || $dspp['Dspp']['hispro'] == '1902' ) {
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
        *
        *** *******************************************************************/

        function detailsCi( $personne_id ){
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
                    'hasOne' => array( 'Dspf' ), 'hasAndBelongsToMany' => array( 'Creance' )
                )
            );
            $this->Foyer->Dossier->unbindModelAll();
            $this->Prestation->unbindModelAll();
            $this->Dspp->unbindModelAll();

            $personne = $this->findById( $personne_id, null, null, 2 );

            /// Recherche des informations financières
            $infofinancieres = $this->Foyer->Dossier->Infofinanciere->find(
                'all',
                array(
                    'recursive' => -1,
                    'order' => array( 'Infofinanciere.moismoucompta DESC' )
                )
            );
            $personne['Foyer']['Dossier']['Infofinanciere'] = Set::classicExtract( $infofinancieres, '{n}.Infofinanciere' );

//             $detaildroitrsa = $this->Foyer->Dossier->Detaildroitrsa->find(
//                 'first',
//                 array(
//                     'recursive' => -1,
//                     'conditions' => array( 'Detaildroitrsa.dossier_rsa_id' => $personne['Foyer']['Dossier']['id'] )
//                 )
//             );
//             $personne['Foyer']['Dossier']['Detaildroitrsa'] = $detaildroitrsa['Detaildroitrsa'];

            // FIXME -> comment distinguer ? + FIXME autorutitel / autorutiadrelec
            $modecontact = $this->Foyer->Modecontact->find(
                'all',
                array(
                    'recursive' => -1,
                    'conditions' => array(
                        'Modecontact.foyer_id' => $personne['Foyer']['id']
                    )
                )
            );
            $personne['Foyer']['Modecontact'] = Set::extract( $modecontact, '/Modecontact' );

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
    }
?>