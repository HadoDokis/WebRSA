<?php
    App::import('Sanitize');

    class CriteresController extends AppController
    {
        var $name = 'Criteres';
        var $uses = array( 'Dossier', 'Foyer', 'Adresse', 'Personne', 'Typeorient', 'Structurereferente', 'Contratinsertion', 'Option', 'Serviceinstructeur', 'Orientstruct' );
        //var $aucunDroit = array('index', 'menu', 'constReq');
        var $aucunDroit = array( 'constReq' );

        /**
            INFO: ILIKE et EXTRACT sont spécifiques à PostgreSQL
        */

        function index() {
            $typeservice = $this->Serviceinstructeur->find(
                'list',
                array(
                    'fields' => array(
                        'Serviceinstructeur.id',
                        'Serviceinstructeur.lib_service'
                    ),
                )
            );
            $this->set( 'typeservice', $typeservice );

            $sr = $this->Structurereferente->find(
                'list',
                array(
                    'fields' => array(
                        'Structurereferente.lib_struc'
                    ),
                )
            );
            $this->set( 'sr', $sr );


            $this->set( 'typeorient', $this->Typeorient->listOptions() );
            $this->set( 'statuts', $this->Option->statut_orient() );
            $this->set( 'statuts_contrat', $this->Option->statut_contrat_insertion() );
            $this->set( 'typeservice', $this->Serviceinstructeur->listOptions());


            $params = $this->data;
            if( !empty( $params ) ) {
                $conditions = array();

                // INFO: seulement les personnes qui sont dans ma zone géographique
                $conditions['Orientstruct.personne_id'] = $this->Personne->findByZones( $this->Session->read( 'Auth.Zonegeographique' ) );

               //Critères sur la date d'ouverture d'orientation
                if( !dateComplete( $this->data, 'Dossier.dtdemrsa' ) ) {
                    $dtdemrsa = $this->data['Dossier']['dtdemrsa'];
                    $conditions['Dossier.dtdemrsa'] = $dtdemrsa['year'].'-'.$dtdemrsa['month'].'-'.$dtdemrsa['day'];
                }

                //Critère recherche par Contrat insertion: localisation de la personne rattachée au contrat
                if( isset( $params['Adresse']['locaadr'] ) && !empty( $params['Adresse']['locaadr'] ) ){
                    $conditions[] = "Adresse.locaadr ILIKE '%".Sanitize::paranoid( $params['Adresse']['locaadr'] )."%'";
                }

                //Critère recherche par Type orientation: localisation de la personne rattachée au contrat
                if( isset( $params['Typeorient']['id'] ) && !empty( $params['Typeorient']['id'] ) ){
                    $conditions['Orientstruct.typeorient_id'] = $params['Typeorient']['id'];
                }

                //Critère recherche par Orientation : Structure referente
                if( isset( $params['Structurereferente']['id'] ) && !empty( $params['Structurereferente']['id'] ) ){
                    $conditions['Orientstruct.structurereferente_id'] = $params['Structurereferente']['id'];
                }

                //Critère recherche par Orientation: par statut_orient
                if( isset( $params['Orientstruct']['statut_orient'] ) && !empty( $params['Orientstruct']['statut_orient'] ) )
                    /*$conditions[] = "Orientsstructs.statut_orient ILIKE '%".Sanitize::paranoid( $params['Orientsstructs']['statut_orient'] )."%'";*/
                    $conditions['Orientstruct.statut_orient'] = $params['Orientstruct']['statut_orient'];


                //Critère recherche par Orientation : par service instructeur
                if( isset( $params['Serviceinstructeur']['id'] ) && !empty( $params['Serviceinstructeur']['id'] ) ){
                    $conditions['Serviceinstructeur.id'] = $params['Serviceinstructeur']['id'];
                }

                $this->Orientstruct->unbindModelAll();
                $this->Orientstruct->bindModel(
                    array(
                        'belongsTo' => array(
                            'Personne' => array(
                                'foreignKey' => false,
                                'conditions' => array( 'Orientstruct.personne_id = Personne.id' )
                            ),
                            'Adressefoyer' => array(
                                'foreignKey' => false,
                                'conditions' => array(
                                    'Adressefoyer.foyer_id = Personne.foyer_id',
                                    'Adressefoyer.rgadr = \'01\''
                                )
                            ),
                            'Modecontact' => array(
                                'foreignKey' => false,
                                'conditions' => array( 'Modecontact.id = Personne.foyer_id' )
                            ),
                            'Adresse' => array(
                                'foreignKey' => false,
                                'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                            ),
                            'Foyer' => array(
                                'foreignKey' => false,
                                'conditions' => array( 'Foyer.id = Personne.foyer_id' )
                            ),
                            'Dossier' => array(
                                'foreignKey' => false,
                                'conditions' => array( 'Dossier.id = Foyer.dossier_rsa_id' )
                            ),
                            'Serviceinstructeur' => array(
                                'foreignKey' => false,
                                'conditions' => array( 'Serviceinstructeur.id' => $this->Session->read( 'Auth.User.serviceinstructeur_id' ) )
                            ),
                        )
                    )
                );

                $orients = $this->Orientstruct->find( 
                    'all',
                    array(
                        'conditions' => array(
                            $conditions
                        ),
                        'recursive' => 0
                    )
                );
                $this->set( 'orients', $orients );
// debug( $orients );
//             debug($params);
                $this->data['Search'] = $params;
            }
        }

        /*function view($orientstruct_id = null){
            $sr = $this->Structurereferente->find(
                'list',
                array(
                    'fields' => array(
                        'Structurereferente.lib_struc'
                    ),
                )
            );
            $this->set( 'sr', $sr );

            $this->Orientstruct->unbindModelAll();
            $this->Orientstruct->bindModel(
                array(
                    'belongsTo' => array(
                        'Personne' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Orientstruct.personne_id = Personne.id' )
                        ),
                        'Adressefoyer' => array(
                            'foreignKey' => false,
                            'conditions' => array(
                                'Adressefoyer.foyer_id = Personne.foyer_id',
                                'Adressefoyer.rgadr = \'01\''
                            )
                        ),
                        'Modecontact' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Modecontact.id = Personne.foyer_id' )
                        ),
                        'Adresse' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                        ),
                        'Foyer' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Foyer.id = Personne.foyer_id' )
                        ),
                        'Dossier' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Dossier.id = Foyer.dossier_rsa_id' )
                        ),
                        'Serviceinstructeur' => array(
                            'foreignKey' => false,
                            'conditions' => array( 'Serviceinstructeur.id' => $this->Session->read( 'Auth.User.serviceinstructeur_id' ) )
                        ),
                    )
                )
            );
            //$orients = $this->Orientstruct->findById( $orientstruct_id, null, null, 2 );

$orients = $this->Orientstruct->find( 'all' );
            $this->set( 'orients', $orients);
        }*/
    }
?>
