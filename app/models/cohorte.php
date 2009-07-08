<?php
//     App::import( 'Sanitize' );
//     App::import( 'Dossier' );

    // ************************************************************************

    class Cohorte extends AppModel
    {
        var $name = 'Cohorte';
        var $useTable = false;

        function search( $statutOrientation, $mesCodesInsee, $filtre_zone_geo, $criteres, $lockedDossiers, $limit = PHP_INT_MAX ) {
            $conditions = array();

            $this->Dossier =& ClassRegistry::init( 'Dossier' );
            $this->Detaildroitrsa =& ClassRegistry::init( 'Detaildroitrsa' );
            $this->Option =& ClassRegistry::init( 'Option' );
            $this->Foyer =& ClassRegistry::init( 'Foyer' );
            $this->Adressefoyer =& ClassRegistry::init( 'Adressefoyer' );
            $this->Ressource =& ClassRegistry::init( 'Ressource' );

            //$this->Dossier->begin(); // Pour les jetons
            // Moteur de recherche
            $filtres = array();
            // Critères sur le dossier - date de demande
            if( isset( $criteres['Filtre']['dtdemrsa'] ) && !empty( $criteres['Filtre']['dtdemrsa'] ) ) {
                $valid_from = ( valid_int( $criteres['Filtre']['dtdemrsa_from']['year'] ) && valid_int( $criteres['Filtre']['dtdemrsa_from']['month'] ) && valid_int( $criteres['Filtre']['dtdemrsa_from']['day'] ) );
                $valid_to = ( valid_int( $criteres['Filtre']['dtdemrsa_to']['year'] ) && valid_int( $criteres['Filtre']['dtdemrsa_to']['month'] ) && valid_int( $criteres['Filtre']['dtdemrsa_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $filtres['Dossier.id'] = $this->Dossier->find(
                        'list',
                        array(
                            'fields' => array(
                                'Dossier.id',
                                'Dossier.id'
                            ),
                            'conditions' => 'Dossier.dtdemrsa BETWEEN \''.implode( '-', array( $criteres['Filtre']['dtdemrsa_from']['year'], $criteres['Filtre']['dtdemrsa_from']['month'], $criteres['Filtre']['dtdemrsa_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteres['Filtre']['dtdemrsa_to']['year'], $criteres['Filtre']['dtdemrsa_to']['month'], $criteres['Filtre']['dtdemrsa_to']['day'] ) ).'\''
                        )
                    );
                }
            }

            // --------------------------------------------------------

            // Critères sur le code origine demande Rsa
            if( empty( $criteres['Filtre']['oridemrsa'] ) ) {
                // Si rien n'est sélectionné, on sélectionne tout
                $criteres['Filtre']['oridemrsa'] = array_keys( $this->Option->oridemrsa() );
            }

            if( isset( $criteres['Filtre']['oridemrsa'] ) ) {
                $conditions = array();
                if( array_key_exists( 'Dossier.id', $filtres ) ) {
                    $conditions['Detaildroitrsa.dossier_rsa_id'] = ( !empty( $filtres['Dossier.id'] ) ? $filtres['Dossier.id'] : null );
                }
                if( !empty( $criteres['Filtre']['oridemrsa'] ) ) {
                    $conditions['Detaildroitrsa.oridemrsa'] = array_values( $criteres['Filtre']['oridemrsa'] );
                }

                $filtres['Dossier.id'] = $this->Detaildroitrsa->find(
                    'list',
                    array(
                        'fields' => array(
                            'Detaildroitrsa.dossier_rsa_id',
                            'Detaildroitrsa.dossier_rsa_id'
                        ),
                        'conditions' => $conditions
                    )
                );
            }

            // --------------------------------------------------------

            if( !empty( $lockedDossiers ) ) {
                $conditions =  array(
                    'Foyer.dossier_rsa_id' => ( !empty( $filtres['Dossier.id'] ) ? $filtres['Dossier.id'] : null ),
                    'NOT' => array( '"Foyer"."dossier_rsa_id"' => $lockedDossiers )
                );
            }
            else {
                $conditions =  array(
                    'Foyer.dossier_rsa_id' => ( !empty( $filtres['Dossier.id'] ) ? $filtres['Dossier.id'] : null )
                );
            }
            // Recherche des foyers associés à ces dossiers
            $filtres['Foyer.id'] = $this->Foyer->find(
                'list',
                array(
                    'fields' => array(
                        'Foyer.dossier_rsa_id',
                        'Foyer.dossier_rsa_id'
                    ),
                    'conditions' => $conditions
                )
            );
            unset( $filtres['Dossier.id'] );

            // --------------------------------------------------------

            $locaadr = ( isset( $criteres['Filtre']['locaadr'] ) ? $criteres['Filtre']['locaadr'] : null );
            $conditions = array(
                'Adressefoyer.foyer_id' => ( !empty( $filtres['Foyer.id'] ) ? $filtres['Foyer.id'] : null ),
                'Adressefoyer.rgadr' => '01',
                '"Adresse"."locaadr" ILIKE'  => '%'.$locaadr.'%'
            );

            if( $filtre_zone_geo ) {
                $conditions = Set::merge(
                    $conditions,
                    array( 'Adresse.numcomptt'  => ( !empty( $mesCodesInsee ) ? $mesCodesInsee : null ) )
                );
            }

            $filtres['Foyer.id'] = $this->Adressefoyer->find(
                'list',
                array(
                    'fields' => array(
                        'Adressefoyer.id',
                        'Adressefoyer.foyer_id'
                    ),
                    'conditions' => $conditions,
                    'recursive' => 0
                )
            );

            // --------------------------------------------------------

            $filtres['Personne.id'] = $this->Dossier->Foyer->Personne->find(
                'list',
                array(
                    'fields' => array(
                        'Personne.id',
                        'Personne.id'
                    ),
                    'conditions'    => array(
                        'Personne.foyer_id' => ( !empty( $filtres['Foyer.id'] ) ? $filtres['Foyer.id'] : null )
                    ),
                    'recursive'     => -1
                )
            );

            // --------------------------------------------------------

            $conditions = array(
                'Orientstruct.statut_orient' => $statutOrientation,
                'Orientstruct.personne_id'   => ( !empty( $filtres['Personne.id'] ) ? $filtres['Personne.id'] : null )
            );

            if( !empty( $criteres['Filtre']['date_impression'] ) ) {
                if( $criteres['Filtre']['date_impression'] == 'I' ) {
                    $conditions = Set::merge(
                        $conditions,
                        array( '"Orientstruct"."date_impression" NOT' => NULL )
                    );
                }
                else if( $criteres['Filtre']['date_impression'] == 'N' ) {
                    $conditions = Set::merge(
                        $conditions,
                        array( '"Orientstruct"."date_impression"' => NULL )
                    );
                }
            }

            $filtres['Personne.id'] = $this->Dossier->Foyer->Personne->Orientstruct->find(
                'list',
                array(
                    'fields' => array(
                        'Orientstruct.personne_id',
                        'Orientstruct.personne_id'
                    ),
                    'conditions'    => $conditions,
                    'recursive'     => -1
                )
            );

            $filtres['Personne.id'] = $this->Dossier->Foyer->Personne->Prestation->find(
                'list',
                array(
                    'fields' => array(
                        'Prestation.personne_id',
                        'Prestation.personne_id'
                    ),
                    'conditions' => array(
                        'Prestation.personne_id' => ( !empty( $filtres['Personne.id'] ) ? $filtres['Personne.id'] : null ),
                        'Prestation.toppersdrodevorsa' => true
                    )
                )

            );

            // Ces personnes sont-elles soumises à droits et devoirs ?
            // FIXME -> est-ce que mtpersressmenrsa existe bien -> à l'importation des données, faire la moyenne ?
            // FIXME: çe filtre ne servait à rien parce qu'on le fait lors du refresh ?
//             $filtres['Personne.id'] = $this->Ressource->find(
//                 'list',
//                 array(
//                     'fields' => array(
//                         'Ressource.personne_id',
//                         'Ressource.personne_id'
//                     ),
//                     'conditions' => array(
//                         'or' => array(
//                             '"Ressource.mtpersressmenrsa" <' => 500,
//                             'or' => array(
//                                 'Dspp.hispro = \'1903\'',
//                                 'Dspp.hispro = \'1904\''
//                             )
//                         ),
//                         'Ressource.personne_id' => ( !empty( $filtres['Personne.id'] ) ? $filtres['Personne.id'] : null ),
//                     ),
//                     'joins' => array(
//                         array(
//                             'table'      => 'dspps',
//                             'alias'      => 'Dspp',
//                             'type'       => 'LEFT OUTER', // FIXME ?
//                             'foreignKey' => false,
//                             'conditions' => array( 'Ressource.personne_id = Dspp.personne_id' )
//                         ),
//                     ),
//                     'recursive' => -1
//                 )
//             );

            // --------------------------------------------------------

            foreach( $filtres['Personne.id'] as $personne_id ) {
                if( !$this->Dossier->Foyer->Personne->soumisDroitsEtDevoirs( $personne_id ) ) {
                    unset( $filtres['Personne.id'][$personne_id] );
                }
            }

            //;

            // --------------------------------------------------------

            // INFO: 190509 - grille test web-rsa.doc
            // FIXME: optimiser
            $cohorte = $this->Dossier->Foyer->Personne->find(
                'list',
                array(
                    'conditions' => array(
                        'Personne.id' => ( !empty( $filtres['Personne.id'] ) ? $filtres['Personne.id'] : null ),
                        'Personne.foyer_id' => ( !empty( $filtres['Foyer.id'] ) ? $filtres['Foyer.id'] : null ),
                    ),
                    'recursive' => 0,
                    'limit'     => $limit
                )
            );

            return $cohorte;
        }
    }
?>