<?php
    class Orientstruct extends AppModel
    {
        var $name = 'Orientstruct';
        var $useTable = 'orientsstructs';

        // ********************************************************************

        var $actsAs = array(
//             'Autovalidate',
            'Enumerable' => array(
                'fields' => array(
                    'accordbenef' => array(
                        'values' => array( 0, 1 )
                    ),
                    'urgent' => array(
                        'values' => array( 0, 1 )
                    ),
//                     'etatorient' => array( 'domain' => 'orientstruct' ),
                    /*'accordrefaccueil' => array(
                        'values' => array( 0, 1 )
                    ),
                    'decisionep' => array(
                        'values' => array( 0, 1 )
                    ),
                    'decisioncg' => array(
                        'values' => array( 0, 1 )
                    ),*/
                )
            ),
            'Formattable' => array(
                'suffix' => array( 'structurereferente_id', 'referent_id' ),
            )
        );


        var $hasMany = array(
            'Demandereorient' => array(
                'classname'     => 'Demandereorient',
                'foreignKey'    => 'orientstruct_id'
            )
        );

        var $belongsTo = array(
            'Personne' => array(
                'classname'     => 'Personne',
                'foreignKey'    => 'personne_id'
            ),
            'Structurereferente' => array(
                'classname'     => 'Structurereferente',
                'foreignKey'    => 'structurereferente_id'
            ),
            'Typeorient' => array(
                'classname'     => 'Typeorient',
                'foreignKey'    => 'typeorient_id'
            ),
            'Referent' => array(
                'classname'     => 'Referent',
                'foreignKey'    => 'referent_id'
            )/*,///FIXMRE: test avant confirmation !!!!!!!
            'Serviceinstructeur' => array(
                'classname' => 'Serviceinstructeur',
                'foreignKey' => 'serviceinstructeur_id'
            )*/
        );



        // ********************************************************************

        var $validate = array(
            'structurereferente_id' => array(
                array(
                    'rule' => array( 'choixStructure', 'statut_orient' ),
                    'message' => 'Champ obligatoire'
                )
            ),
            'typeorient_id' => array(
                'notEmpty' => array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'date_propo' => array(
                'notEmpty' => array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide'
                )
            ),
            'date_valid' => array(
                'notEmpty' => array(
                    'rule' => 'date',
                    'message' => 'Veuillez entrer une date valide'
                )
            )
        );

        // ********************************************************************

/*
        function search( $mesCodesInsee, $filtre_zone_geo, $criteres, $lockedDossiers ) {
            /// Conditions de base
            $conditions = array();

            /// Critere zone géographique
            if( $filtre_zone_geo ) {
                $mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
                $conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
            }

            /// Dossiers lockés
            if( !empty( $lockedDossiers ) ) {
                $conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
            }

            /// Critères
            $locaadr = Set::extract( $criteres, 'Critere.locaadr' );
            $numcomptt = Set::extract( $criteres, 'Critere.numcomptt' );
            $statut_orient = Set::extract( $criteres, 'Critere.statut_orient' );
            $typeorient_id = Set::extract( $criteres, 'Critere.typeorient_id' );
            $structurereferente_id = Set::extract( $criteres, 'Critere.structurereferente_id' );
            $serviceinstructeur_id = Set::extract( $criteres, 'Critere.serviceinstructeur_id' );

            /// Critères sur l'orientation - date d'orientation
            if( isset( $criteres['Critere']['date_valid'] ) && !empty( $criteres['Critere']['date_valid'] ) ) {
                $valid_from = ( valid_int( $criteres['Critere']['date_valid_from']['year'] ) && valid_int( $criteres['Critere']['date_valid_from']['month'] ) && valid_int( $criteres['Critere']['date_valid_from']['day'] ) );
                $valid_to = ( valid_int( $criteres['Critere']['date_valid_to']['year'] ) && valid_int( $criteres['Critere']['date_valid_to']['month'] ) && valid_int( $criteres['Critere']['date_valid_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Orientstruct.date_valid BETWEEN \''.implode( '-', array( $criteres['Critere']['date_valid_from']['year'], $criteres['Critere']['date_valid_from']['month'], $criteres['Critere']['date_valid_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteres['Critere']['date_valid_to']['year'], $criteres['Critere']['date_valid_to']['month'], $criteres['Critere']['date_valid_to']['day'] ) ).'\'';
                }
            }

            // ...
            if( !empty( $dtdemrsa ) && dateComplete( $criteres, 'Critere.dtdemrsa' ) ) {
                $dtdemrsa = $dtdemrsa['year'].'-'.$dtdemrsa['month'].'-'.$dtdemrsa['day'];
                $conditions[] = 'Dossier.dtdemrsa = \''.$dtdemrsa.'\'';
            }

            // Localité adresse
            if( !empty( $locaadr ) ) {
                $conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
            }

            // Commune au sens INSEE
            if( !empty( $numcomptt ) ) {
                $conditions[] = 'Adresse.numcomptt ILIKE \'%'.Sanitize::clean( $numcomptt ).'%\'';
            }

            // ...
            if( !empty( $statut_orient ) ) {
                $conditions[] = 'Orientstruct.statut_orient = \''.Sanitize::clean( $statut_orient ).'\'';
            }

            // ...
            if( !empty( $typeorient_id ) ) {
                $conditions[] = 'Orientstruct.typeorient_id = \''.Sanitize::clean( $typeorient_id ).'\'';
            }

            // ...
            if( !empty( $structurereferente_id ) ) {
                $conditions[] = 'Orientstruct.structurereferente_id = \''.Sanitize::clean( $structurereferente_id ).'\'';
            }

            // ... FIXME
            if( !empty( $serviceinstructeur_id ) ) {

                $conditions[] = 'Serviceinstructeur.lib_service ILIKE \''.Sanitize::clean( $serviceinstructeur_id ).'\'';
            }

            /// Requête
            $Situationdossierrsa =& ClassRegistry::init( 'Situationdossierrsa' );

            $query = array(
                'fields' => array(
                    '"Orientstruct"."id"',
                    '"Orientstruct"."personne_id"',
                    '"Orientstruct"."typeorient_id"',
                    '"Orientstruct"."structurereferente_id"',
                    '"Orientstruct"."propo_algo"',
                    '"Orientstruct"."valid_cg"',
                    '"Orientstruct"."date_propo"',
                    '"Orientstruct"."date_valid"',
                    '"Orientstruct"."statut_orient"',
                    '"Orientstruct"."date_impression"',
                    '"Dossier"."id"',
                    '"Dossier"."numdemrsa"',
                    '"Dossier"."dtdemrsa"',
                    '"Personne"."id"',
                    '"Personne"."nom"',
                    '"Personne"."prenom"',
                    '"Personne"."dtnai"',
                    '"Personne"."qual"',
                    '"Personne"."nomcomnai"',
                    '"Adresse"."locaadr"',
                    '"Adresse"."numcomptt"',
                    '"Modecontact"."numtel"',
                    '"Serviceinstructeur"."id"',
                    '"Serviceinstructeur"."lib_service"'
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.id = Orientstruct.personne_id' )
                    ),
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.foyer_id = Foyer.id' )
                    ),
                    array(
                        'table'      => 'dossiers_rsa',
                        'alias'      => 'Dossier',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'adresses_foyers',
                        'alias'      => 'Adressefoyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id', 'Adressefoyer.rgadr = \'01\'' )
                    ),
                    array(
                        'table'      => 'adresses',
                        'alias'      => 'Adresse',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                    ),
                    array(
                        'table'      => 'modescontact',
                        'alias'      => 'Modecontact',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Modecontact.foyer_id = Foyer.id' )
                    ),
                    array(
                        'table'      => 'typesorients',
                        'alias'      => 'Typeorient',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Typeorient.id = Orientstruct.typeorient_id' )
                    ),
                    array(
                        'table'      => 'structuresreferentes',
                        'alias'      => 'Structurereferente',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Orientstruct.structurereferente_id = Structurereferente.id' )
                    ),
                    array(
                        'table'      => 'suivisinstruction',
                        'alias'      => 'Suiviinstruction',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Suiviinstruction.dossier_rsa_id = Dossier.id' )
                    ),
                    array(
                        'table'      => 'servicesinstructeurs',
                        'alias'      => 'Serviceinstructeur',
                        'type'       => 'LEFT OUTER',
                        'foreignKey' => false,
                        'conditions' => array( 'Suiviinstruction.numdepins = Serviceinstructeur.numdepins AND Suiviinstruction.typeserins = Serviceinstructeur.typeserins AND Suiviinstruction.numcomins = Serviceinstructeur.numcomins AND Suiviinstruction.numagrins = Serviceinstructeur.numagrins' )
                    ),
                    array(
                        'table'      => 'situationsdossiersrsa',
                        'alias'      => 'Situationdossierrsa',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Situationdossierrsa.dossier_rsa_id = Dossier.id AND ( Situationdossierrsa.etatdosrsa IN ( \''.implode( '\', \'', $Situationdossierrsa->etatOuvert() ).'\' ) )' )
                    )
                ),
                'limit' => 10,
                'conditions' => $conditions
            );

            return $query;
        }*/
        // ********************************************************************

        function choixStructure( $field = array(), $compare_field = null ) {
            foreach( $field as $key => $value ) {
                if( !empty( $this->data[$this->name][$compare_field] ) && ( $this->data[$this->name][$compare_field] != 'En attente' ) && empty( $value ) ) {
                    return false;
                }
            }
            return true;
        }

        // ********************************************************************
/*
        function beforeSave( $options = array() ) {
            $return = parent::beforeSave( $options );
            $hasMany = ( array_depth( $this->data ) > 2 );

            if( !$hasMany ) { // INFO: 1 seul enregistrement
                if( array_key_exists( 'structurereferente_id', $this->data['Orientstruct'] ) && array_key_exists( 'referent_id', $this->data['Orientstruct'] ) ) {
                    $this->data['Orientstruct']['structurereferente_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $this->data['Orientstruct']['structurereferente_id'] );
                    $this->data['Orientstruct']['referent_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $this->data['Orientstruct']['referent_id'] );
                }
            }
            else { // INFO: plusieurs enregistrements
                foreach( $this->data['Orientstruct'] as $key => $value ) {
                    if( is_array( $value ) && array_key_exists( 'structurereferente_id', $value ) ) {
                        $this->data['Orientstruct'][$key]['structurereferente_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $value['structurereferente_id'] );
                        $this->data['Orientstruct'][$key]['referent_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $value['referent_id'] );
                    }
                }
            }

            return $return;
        }*/

        //*********************************************************************

        function dossierId( $ressource_id ) {
            $this->unbindModelAll();
            $this->bindModel(
                array(
                    'hasOne' => array(
                        'Personne' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Personne.id = Orientstruct.personne_id' )
                        ),
                        'Foyer' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Foyer.id = Personne.foyer_id' )
                        )
                    )
                )
            );
            $ressource = $this->findById( $ressource_id, null, null, 1 );

            if( !empty( $ressource ) ) {
                return $ressource['Foyer']['dossier_rsa_id'];
            }
            else {
                return null;
            }
        }

        //*********************************************************************

		/**
		* Récupère les données pour le PDf
		*/

		function getDataForPdf( $id, $user_id ) {
            // TODO: error404/error500 si on ne trouve pas les données
			$optionModel = ClassRegistry::init( 'Option' );
            $qual = $optionModel->qual();
            $typevoie = $optionModel->typevoie();


            $orientstruct = $this->find(
                'first',
                array(
                    'conditions' => array(
                        'Orientstruct.id' => $id
                    )
                )
            );

            /*$typeorient = $this->Structurereferente->Typeorient->find(
                'first',
                array(
                    'conditions' => array(
                        'Typeorient.id' => $orientstruct['Orientstruct']['typeorient_id'] // FIXME structurereferente_id
                    )
                )
            );*/

            $this->Personne->Foyer->Adressefoyer->bindModel(
                array(
                    'belongsTo' => array(
                        'Adresse' => array(
                            'className'     => 'Adresse',
                            'foreignKey'    => 'adresse_id'
                        )
                    )
                )
            );

            $adresse = $this->Personne->Foyer->Adressefoyer->find(
                'first',
                array(
                    'conditions' => array(
                        'Adressefoyer.foyer_id' => $orientstruct['Personne']['foyer_id'],
                        'Adressefoyer.rgadr' => '01',
                    )
                )
            );
            $orientstruct['Adresse'] = $adresse['Adresse'];

            // Récupération de l'utilisateur
            $user = ClassRegistry::init( 'User' )->find(
                'first',
                array(
                    'conditions' => array(
                        'User.id' => $user_id
                    )
                )
            );
            $orientstruct['User'] = $user['User'];

			// Recherche des informations du dossier
            $foyer = $this->Personne->Foyer->findById( $orientstruct['Personne']['foyer_id'], null, null, -1 );
            $dossier = $this->Personne->Foyer->Dossier->find(
                'first',
                array(
                    'conditions' => array(
                        'Dossier.id' => $foyer['Foyer']['dossier_rsa_id']
                    ),
					'recursive' => -1
                )
            );

            $orientstruct['Dossier'] = $dossier['Dossier'];

			if( isset( $orientstruct[$this->alias]['statut_orient'] ) && $orientstruct[$this->alias]['statut_orient'] == 'Orienté' ) {
				//Ajout pour le numéro de poste du référent de la structure
				$referent = $this->Personne->Referent->find(
					'first',
					array(
						'conditions' => array(
							'Referent.structurereferente_id' => $orientstruct['Structurereferente']['id']
						),
						'recursive' => -1
					)
				);

				if( !empty( $referent ) ) {
					$orientstruct['Referent'] = $referent['Referent'];
				}
			}

            $orientstruct['Personne']['dtnai'] = strftime( '%d/%m/%Y', strtotime( $orientstruct['Personne']['dtnai'] ) );
            $orientstruct['Personne']['qual'] = Set::classicExtract( $qual, Set::classicExtract( $orientstruct, 'Personne.qual' ) );
            $orientstruct['Adresse']['typevoie'] = Set::classicExtract( $typevoie, Set::classicExtract( $orientstruct, 'Adresse.typevoie' ) );
            $orientstruct['Structurereferente']['type_voie'] = Set::classicExtract( $typevoie, Set::classicExtract( $orientstruct, 'Structurereferente.type_voie' ) );


            $personne_referent = $this->Personne->Referent->PersonneReferent->find(
                'first',
                array(
                    'conditions' => array(
                        'PersonneReferent.personne_id' => Set::classicExtract( $orientstruct, 'Personne.id' )
                    )
                )
            );

            if( !empty( $personne_referent ) ){
                $orientstruct = Set::merge( $orientstruct, $personne_referent );
            }

			return $orientstruct;
		}

		/**
		* Ajout d'entrée dans la table orientsstructs pour les DEM ou CJT RSA n'en possédant pas
		*/

		function fillAllocataire() {
			$sql = "INSERT INTO orientsstructs (personne_id, statut_orient)
					(
						SELECT DISTINCT personnes.id, 'Non orienté' AS statut_orient
							FROM personnes
								INNER JOIN prestations ON ( prestations.personne_id = personnes.id AND prestations.natprest = 'RSA' AND ( prestations.rolepers = 'DEM' OR prestations.rolepers = 'CJT' ) )
							WHERE personnes.id NOT IN (
								SELECT orientsstructs.personne_id
									FROM orientsstructs
							)
					);";
			return $this->query( $sql );
		}
    }
?>