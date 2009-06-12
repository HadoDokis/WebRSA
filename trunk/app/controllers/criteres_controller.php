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
//         function constReq ($requete, $champ, $valeur) {
//             if (empty($requete))
//                 return "($champ = $valeur)";
//             else
//                 return $requete." AND ($champ = $valeur) ";
//         }

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
                $this->data['Search'] = $params;
            }
        }
/*
        function index() {

            $this->set( 'etatdosrsa', $this->Option->etatdosrsa() );

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
            if( count( $params ) > 0 ) {
                // INFO: seulement les personnes qui sont dans ma zone géographique
                $mesZones = $this->Personne->findByZones( $this->Session->read( 'Auth.Zonegeographique' ) );
                $requete = ( !empty( $mesZones ) ? 'personne_id IN ('.implode( ',', array_values( $mesZones ) ).') ' : 'personne_id IS NULL' );
                $select  = "SELECT * FROM personnes WHERE id IN ( SELECT personne_id FROM orientsstructs WHERE ";

                //Critères sur la date d'ouverture d'orientation
                if( !dateComplete( $this->data, 'Dossier.dtdemrsa' ) ) {
                    $dtdemrsa = $this->data['Dossier']['dtdemrsa'];
                    $conditions['Dossier.dtdemrsa'] = $dtdemrsa['year'].'-'.$dtdemrsa['month'].'-'.$dtdemrsa['day'];
                }

                //Critères sur un type d'orientation - libelle, parentid, modèle de notification
                if( isset( $params['Typeorient']['id'] ) && !empty( $params['Typeorient']['id'] ) )
                    $requete = $this->constReq($requete, 'Orientsstructs.typeorient_id', suffix( $params['Typeorient']['id'] ) );

                //Critères sur une structure référente - libelle, nom_voie, ville, code_insee
                if( isset( $params['Structurereferente']['id'] ) && !empty( $params['Structurereferente']['id'] ))
                    $requete = $this->constReq($requete, 'Orientsstructs.structurereferente_id', suffix( $params['Structurereferente']['id'] ) );


                //Critères sur une statut d'orientation
                if( isset( $params['Orientstructs']['statut_orient']  ) && !empty( $params['Orientstructs']['statut_orient'] ))
                    $requete = $this->constReq($requete, 'Orientsstructs.statut_orient', "'".$params['Orientstructs']['statut_orient']."'");

                //Critères sur le service
//                 $service = $this->Serviceinstructeur->findById( $params['Serviceinstructeur']['id'] );
//                 if( !empty( $service ) ) {
//                     foreach( array( 'numdepins', 'typeserins', 'numcomins', 'numagrins' ) as $key ) {
//                         $requete = $this->constReq($requete, 'Orientsstructs.'.$key, "'".$service['Serviceinstructeur'][$key]."'");
//                     }
//                 }



                $this->Orientstruct->unbindModelAll();
                $this->Orientstruct->bindModel(
                    array(
                        'belongsTo' => array(
                            'Personne' => array(
                                'foreignKey' => false,
                                'conditions' => array( 'Orientstruct.personne_id = Personne.id' )
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


//                 debug( $orients );


                $requete = $select. $requete .')';
                $criteres = $this->Orientstruct->query($requete);


                //Recherche

                for ($i = 0; $i < count ($criteres); $i++ ){
                    $criteres[$i]['Foyer'] = $this->Foyer->read(null, $criteres[$i][0]['foyer_id']);
                    $criteres[$i]['Dossier'] = $this->Dossier->read(null, $criteres[$i]['Foyer']['Foyer']['dossier_rsa_id']);
                }
                $this->set( 'criteres', $criteres );
// debug(  $criteres );
                $orients = $this->Orientstruct->find( 
                    'all',
                    array(
                        'conditions' => array(
                            $requete
                        ),
                        'recursive' => 0
                    )
                );

                $this->set( 'orients', $orients );
                $this->data['Search'] = $params;
            }
        }*/
    }
?>
