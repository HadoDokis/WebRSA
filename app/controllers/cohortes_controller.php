<?php
    class CohortesController extends AppController
    {
        var $name = 'Cohortes';
        var $uses = array( 'Dossier', 'Structurereferente' );

        function index() {
            // TODO: par 15
            $services = array(
                1 => 'Association agréée',
                2 => 'Pôle Emploi',
                3 => 'Service Social du Département',
            );

            $this->set( 'options2', $this->Structurereferente->list1Options() );

            $dossiers = $this->Dossier->find(
                'all',
                array(
                    'fields' => array(
                        'Dossier.id',
                        'Dossier.numdemrsa',
                        'Personne.dtnai',
                        'Personne.nir',
                        'Dossier.dtdemrsa',
                        'Dossier.matricule',
                        'Adresse.codepos',
                        'Adresse.locaadr',
                        'Adresse.canton',
                        'Personne.nom',
                        'Personne.prenom',
                        'Contratinsertion.id',
                        'Structurereferente.id',
                        'Structurereferente.lib_struc',
                    ),
                    'joins' => array(
                        array(
                            'table' => 'foyers',
                            'alias' => 'Foyer',
                            'type'  => 'LEFT OUTER',
                            'conditions'=> array( 'Dossier.id = Foyer.dossier_rsa_id' )
                        ),
                        array(
                            'table' => 'adresses_foyers',
                            'alias' => 'AdresseFoyer',
                            'type'  => 'LEFT OUTER',
                            'conditions'=> array( 'AdresseFoyer.foyer_id = Foyer.id', 'AdresseFoyer.rgadr = \'01\'' )
                        ),
                        array(
                            'table' => 'adresses',
                            'alias' => 'Adresse',
                            'type'  => 'LEFT OUTER',
                            'conditions'=> array( 'AdresseFoyer.adresse_id = Adresse.id' )
                        ),
                        array(
                            'table' => 'personnes',
                            'alias' => 'Personne',
                            'type'  => 'LEFT OUTER',
                            'conditions'=> array( 'Personne.foyer_id = Foyer.id', 'or' => array( 'Personne.rolepers = \'DEM\'', 'Personne.rolepers = \'CJT\'' ) )
                        ),
                        array(
                            'table' => 'contratsinsertion',
                            'alias' => 'Contratinsertion',
                            'type'  => 'LEFT OUTER',
                            'conditions'=> array( 'Contratinsertion.personne_id = Personne.id' )
                        ),
                        array(
                            'table' => 'structuresreferentes',
                            'alias' => 'Structurereferente',
                            'type'  => 'LEFT OUTER',
                            'conditions' => array( 'Personne.id = Structurereferente.id' )
                        ),
                    ),
                    'recursive' => -1
                )
            );

            foreach( $dossiers as $key => $dossier ) {
                $i = rand( 1, count( $services ) );
                $dossiers[$key]['Dossier']['preorientation'] = $services[$i];
                $dossiers[$key]['Dossier']['preorientation_id'] = $i;
            }
// debug( $dossiers );
            $this->set( 'services', $services );
            $this->set( 'dossiers', $dossiers );
        }
    }
?>