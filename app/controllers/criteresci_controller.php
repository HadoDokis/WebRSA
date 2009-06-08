<?php
    App::import('Sanitize');

    function freu( $data, $key ) {
        $freu = Set::extract( $data, $key );
        if( !array( $freu ) ) {
            return empty( $freu );
        }
        else {
            $empty = true;
            foreach( $freu as $tmp ) {
                if( !empty( $tmp ) ) {
                    $empty = false;
                }
            }
            return $empty;
        }
    }

    class CriteresciController extends AppController
    {
        var $name = 'Criteresci';
        var $uses = array( 'Dossier', 'Foyer', 'Personne', 'Contratinsertion', 'Option', 'Serviceinstructeur' );
//         var $aucunDroit = array( 'constReq' );

        /**
            INFO: ILIKE et EXTRACT sont spécifiques à PostgreSQL
        */

        var $paginate = array(
            // FIXME
            'limit' => 20
        );

        function beforeFilter() {
            $return = parent::beforeFilter();
                //$this->set( 'statuts', $this->Option->statut_contrat_insertion() );
                $this->set( 'decision_ci', $this->Option->decision_ci() );
            return $return;
        }


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

            $params = $this->data;
            if( !empty( $params ) ) {
                $conditions = array();

                // INFO: seulement les personnes qui sont dans ma zone géographique
                $conditions['Contratinsertion.personne_id'] = $this->Personne->findByZones( $this->Session->read( 'Auth.Zonegeographique' ) );


                if( !freu( $this->data, 'Contratinsertion.dd_ci' ) ) {
                    $dd_ci = $this->data['Contratinsertion']['dd_ci'];
                    $conditions['Contratinsertion.dd_ci'] = $dd_ci['year'].'-'.$dd_ci['month'].'-'.$dd_ci['day'];
                }

                if( isset( $params['Adresse']['locaadr'] ) && !empty( $params['Adresse']['locaadr'] ) ){
                    $conditions[] = "Adresse.locaadr ILIKE '%".Sanitize::paranoid( $params['Adresse']['locaadr'] )."%'";
                }

                if( isset( $params['Contratinsertion']['decision_ci'] ) && !empty( $params['Contratinsertion']['decision_ci'] ) ){
                    $conditions[] = "Contratinsertion.decision_ci ILIKE '%".Sanitize::paranoid( $params['Contratinsertion']['decision_ci'] )."%'";
                }

                if( isset( $params['Serviceinstructeur']['id'] ) && !empty( $params['Serviceinstructeur']['id'] ) ){
                    $conditions['Serviceinstructeur.id'] = $params['Serviceinstructeur']['id'];
                }

                $this->Contratinsertion->unbindModelAll();
                $this->Contratinsertion->bindModel(
                    array(
                        'belongsTo' => array(
                            'Personne' => array(
                                'foreignKey' => false,
                                'conditions' => array( 'Contratinsertion.personne_id = Personne.id' )
                            ),
                            'Adressefoyer' => array(
                                'foreignKey' => false,
                                'conditions' => array(
                                    'Adressefoyer.foyer_id = Personne.foyer_id',
                                    'Adressefoyer.rgadr = \'01\''
                                )
                            ),
                            'Adresse' => array(
                                'foreignKey' => false,
                                'conditions' => array( 'Adresse.id = Adressefoyer.adresse_id' )
                            ),
                            'Foyer' => array(
                                'foreignKey' => false,
                                'conditions' => array( 'Foyer.id = Adressefoyer.foyer_id' )
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
                $contrats = $this->Contratinsertion->find( 'all', array( 'conditions' => array( $conditions ), 'recursive' => 0 ) );

                $this->set( 'contrats', $contrats );

                $this->data['Search'] = $params;
            }
        }

    }
?>
