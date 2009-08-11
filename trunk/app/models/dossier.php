<?php
    class Dossier extends AppModel
    {
        var $name = 'Dossier';
        var $useTable = 'dossiers_rsa';

        //*********************************************************************

        var $hasOne = array(
            'Foyer' => array(
                'classname'     => 'Foyer',
                'foreignKey'    => 'dossier_rsa_id'
            ),
            'Situationdossierrsa' => array(
                'classname' => 'Situationdossierrsa',
                'foreignKey' => 'dossier_rsa_id'
            ),
            'Avispcgdroitrsa' => array(
                'classname' => 'Avispcgdroitrsa',
                'foreignKey' => 'dossier_rsa_id'
            ),
            'Detaildroitrsa' => array(
                'classname' => 'Detaildroitrsa',
                'foreignKey' => 'dossier_rsa_id'
            ),
            'Suiviinstruction' => array( // FIXME: hasMany
                'classname' => 'Suiviinstruction',
                'foreignKey' => 'dossier_rsa_id'
            ),
            'Infofinanciere' => array( // FIXME: hasMany
                'classname' => 'Infofinanciere',
                'foreignKey' => 'dossier_rsa_id'
            )
        );



        //*********************************************************************

        var $validate = array(
            'numdemrsa' => array(
                array(
                    'rule' => 'isUnique',
                    'message' => 'Cette valeur est déjà utilisée'
                ),
                array(
                    'rule' => 'alphaNumeric',
                    'message' => 'Veuillez n\'utiliser que des lettres et des chiffres'
                ),
                array(
                    'rule' => array( 'between', 11, 11 ),
                    'message' => 'Le n° de demande est composé de 11 caractères'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'dtdemrsa' => array(
                array(
                    'rule' => 'date',
                    'message' => 'Veuillez vérifier le format de la date.'
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
            ),
            'matricule' => array(
                array(
                    'rule' => 'isUnique',
                    'message' => 'Ce numéro CAF est déjà utilisé'
                ),
                array(
                    'rule' => array( 'between', 15, 15 ),
                    'message' => 'Le numéro CAF est composé de 15 chiffres'
                ),
                array(
                    'rule' => 'numeric',
                    'message' => 'Veuillez entrer une valeur numérique.',
                    'allowEmpty' => true
                ),
                array(
                    'rule' => 'notEmpty',
                    'message' => 'Champ obligatoire'
                )
                // TODO: format NIR
            ),
        );

        //*********************************************************************

        function beforeSave() {
            // Champs déduits
            if( !empty( $this->data['Dossier']['numdemrsa'] ) ) {
                $this->data['Dossier']['numdemrsa'] = strtoupper( $this->data['Dossier']['numdemrsa'] );
            }

            return parent::beforeSave();
        }

        //*********************************************************************

        function findByZones( $zonesGeographiques = array(), $filtre_zone_geo = true ) {
            $this->Foyer->unbindModelAll();

            $this->Foyer->bindModel(
                array(
                    'hasOne'=>array(
                        'Adressefoyer' => array(
                            'foreignKey'    => false,
                            'type'          => 'LEFT',
                            'conditions'    => array(
                                '"Adressefoyer"."foyer_id" = "Foyer"."id"',
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

            if( $filtre_zone_geo ) {
                $params = array (
                    'conditions' => array(
                        'Adresse.numcomptt' => array_values( $zonesGeographiques )
                    )
                );
            }
            else {
                $params = array();
            }

            $foyers = $this->Foyer->find( 'all', $params );

            $return = Set::extract( $foyers, '{n}.Foyer.dossier_rsa_id' );
            return ( !empty( $return ) ? $return : null );
        }

        // ********************************************************************

        function search( $mesCodesInsee, $filtre_zone_geo, $params ) {
            $conditions = array();

            /// Filtre zone géographique
            if( $filtre_zone_geo ) {
                $mesCodesInsee = ( !empty( $mesCodesInsee ) ? $mesCodesInsee : '0' );
                $conditions[] = 'Adresse.numcomptt IN ( \''.implode( '\', \'', $mesCodesInsee ).'\' )';
            }

            // Critères sur le dossier - numéro de dossier
            if( isset( $params['Dossier']['numdemrsa'] ) && !empty( $params['Dossier']['numdemrsa'] ) ) {
                $conditions[] = "Dossier.numdemrsa ILIKE '%".Sanitize::paranoid( $params['Dossier']['numdemrsa'] )."%'";
            }

            // Critères sur le dossier - date de demande
            if( isset( $params['Dossier']['dtdemrsa'] ) && !empty( $params['Dossier']['dtdemrsa'] ) ) {
                $valid_from = ( valid_int( $params['Dossier']['dtdemrsa_from']['year'] ) && valid_int( $params['Dossier']['dtdemrsa_from']['month'] ) && valid_int( $params['Dossier']['dtdemrsa_from']['day'] ) );
                $valid_to = ( valid_int( $params['Dossier']['dtdemrsa_to']['year'] ) && valid_int( $params['Dossier']['dtdemrsa_to']['month'] ) && valid_int( $params['Dossier']['dtdemrsa_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Dossier.dtdemrsa BETWEEN \''.implode( '-', array( $params['Dossier']['dtdemrsa_from']['year'], $params['Dossier']['dtdemrsa_from']['month'], $params['Dossier']['dtdemrsa_from']['day'] ) ).'\' AND \''.implode( '-', array( $params['Dossier']['dtdemrsa_to']['year'], $params['Dossier']['dtdemrsa_to']['month'], $params['Dossier']['dtdemrsa_to']['day'] ) ).'\'';
                }
            }

            // Critères sur une personne du foyer - nom, prénom, nom de jeune fille -> FIXME: seulement demandeur pour l'instant
            $filtersPersonne = array();
            foreach( array( 'nom', 'prenom', 'nomnai' ) as $criterePersonne ) {
                if( isset( $params['Personne'][$criterePersonne] ) && !empty( $params['Personne'][$criterePersonne] ) ) {
                    $conditions[] = 'Personne.'.$criterePersonne.' ILIKE \'%'.$params['Personne'][$criterePersonne].'%\'';
                }
            }

            // Critères sur une personne du foyer - date de naissance -> FIXME: seulement demandeur pour l'instant
            if( isset( $params['Personne']['dtnai'] ) && !empty( $params['Personne']['dtnai'] ) ) {
                if( valid_int( $params['Personne']['dtnai']['year'] ) ) {
                    $conditions[] = 'EXTRACT(YEAR FROM Personne.dtnai) = '.$params['Personne']['dtnai']['year'];
                }
                if( valid_int( $params['Personne']['dtnai']['month'] ) ) {
                    $conditions[] = 'EXTRACT(MONTH FROM Personne.dtnai) = '.$params['Personne']['dtnai']['month'];
                }
                if( valid_int( $params['Personne']['dtnai']['day'] ) ) {
                    $conditions[] = 'EXTRACT(DAY FROM Personne.dtnai) = '.$params['Personne']['dtnai']['day'];
                }
            }

            $query = array(
                'fields' => array(
                    '"Dossier"."id"',
                    '"Dossier"."numdemrsa"',
                    '"Dossier"."dtdemrsa"',
                    '"Personne"."nir"',
                    '"Personne"."qual"',
                    '"Personne"."nom"',
                    '"Personne"."prenom"',
                    '"Personne"."prenom2"',
                    '"Personne"."prenom3"',
                    '"Personne"."dtnai"',
                    '"Personne"."nomcomnai"',
                    '"Adresse"."locaadr"',
                    '"Situationdossierrsa"."etatdosrsa"'
                ),
                'recursive' => -1,
                'joins' => array(
                    array(
                        'table'      => 'foyers',
                        'alias'      => 'Foyer',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Dossier.id = Foyer.dossier_rsa_id' )
                    ),
                    array(
                        'table'      => 'personnes',
                        'alias'      => 'Personne',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array( 'Personne.foyer_id = Foyer.id' )
                    ),
                    array(
                        'table'      => 'prestations',
                        'alias'      => 'Prestation',
                        'type'       => 'INNER',
                        'foreignKey' => false,
                        'conditions' => array(
                            'Personne.id = Prestation.personne_id',
                            'Prestation.natprest = \'RSA\'',
                            'Prestation.rolepers = \'DEM\''
                        )
                    ),
                    array(
                        'table'      => 'situationsdossiersrsa',
                        'alias'      => 'Situationdossierrsa',
                        'type'       => 'LEFT OUTER', // FIXME
                        'foreignKey' => false,
                        'conditions' => array( 'Situationdossierrsa.dossier_rsa_id = Dossier.id' )
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
                    )
                ),
                'limit' => 10,
                'conditions' => $conditions
            );


            $typesAllocation = array( 'AllocationsComptabilisees', 'IndusConstates', 'IndusTransferesCG', 'RemisesIndus', 'AnnulationsFaibleMontant', 'AutresAnnulations' );

            foreach( $typesAllocation as $type ) {
                $meu  = Inflector::singularize( Inflector::tableize( $type ) );
                $query['fields'][] = '"'.$type.'"."mtmoucompta" AS mt_'.$meu;

                $join = array(
                    'table'      => 'infosfinancieres',
                    'alias'      => $type,
                    'type'       => 'LEFT OUTER',
                    'foreignKey' => false,
                    'conditions' => array(
                        $type.'.dossier_rsa_id = Dossier.id',
                        $type.'.type_allocation' => $type,
                    )
                );

                $query['joins'][] = $join;
            }
            $query['conditions'] = Set::merge( $query['conditions'], $conditions );

debug($query['fields']  );
            return $query;
        }
    }
?>