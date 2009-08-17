<?php
    class Orientstruct extends AppModel
    {
        var $name = 'Orientstruct';
        var $useTable = 'orientsstructs';

        // ********************************************************************

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
            'toppersdrodevorsa' => array(
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

        function search( $mesCodesInsee, $filtre_zone_geo, $criteres, $lockedDossiers ) {
            /// Conditions de base
            $conditions = array(/* '1 = 1' */);

            /// Filtre zone géographique
            if( $filtre_zone_geo ) {
                $mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
                $conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
            }

            /// Dossiers lockés
            if( !empty( $lockedDossiers ) ) {
                $conditions[] = 'Dossier.id NOT IN ( '.implode( ', ', $lockedDossiers ).' )';
            }

            /// Critères
            $dtdemrsa = Set::extract( $criteres, 'Filtre.dtdemrsa' );
            $locaadr = Set::extract( $criteres, 'Filtre.locaadr' );
            $statut_orient = Set::extract( $criteres, 'Filtre.statut_orient' );
            $typeorient_id = Set::extract( $criteres, 'Filtre.typeorient_id' );
            $structurereferente_id = Set::extract( $criteres, 'Filtre.structurereferente_id' );
            $serviceinstructeur_id = Set::extract( $criteres, 'Filtre.serviceinstructeur_id' );

            // ...
            if( !empty( $dtdemrsa ) && dateComplete( $criteres, 'Filtre.dtdemrsa' ) ) {
                $dtdemrsa = $dtdemrsa['year'].'-'.$dtdemrsa['month'].'-'.$dtdemrsa['day'];
                $conditions[] = 'Dossier.dtdemrsa = \''.$dtdemrsa.'\'';
            }

            // Localité adresse
            if( !empty( $locaadr ) ) {
                $conditions[] = 'Adresse.locaadr ILIKE \'%'.Sanitize::clean( $locaadr ).'%\'';
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
// debug( $query );
            return $query;
        }
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

        function beforeSave( $options = array() ) {
            $return = parent::beforeSave( $options );
            $hasMany = ( array_depth( $this->data ) > 2 );
// debug( $hasMany );
            if( !$hasMany ) { // INFO: 1 seul enregistrement
                if( array_key_exists( 'structurereferente_id', $this->data['Orientstruct'] ) ) {
                    $this->data['Orientstruct']['structurereferente_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $this->data['Orientstruct']['structurereferente_id'] );
                }
            }
            else { // INFO: plusieurs enregistrements
                foreach( $this->data['Orientstruct'] as $key => $value ) {
                    if( is_array( $value ) && array_key_exists( 'structurereferente_id', $value ) ) {
                        $this->data['Orientstruct'][$key]['structurereferente_id'] = preg_replace( '/^[0-9]+_([0-9]+)$/', '\1', $value['structurereferente_id'] );
                    }
                }
            }
// debug( $this->data );
            return $return;
        }

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
    }
?>