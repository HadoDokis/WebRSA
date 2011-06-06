<?php
    class HistoriquesepsController extends AppController
    {

        var $name = 'Historiqueseps';
        var $uses = array( 'Dossier', 'Personne', 'Option' );
        var $helpers = array(  'Default2' );

        /** ********************************************************************
        *
        *** *******************************************************************/
        protected function _setOptions() {
            $options['Dossierep'] = $this->Dossier->Foyer->Personne->Dossierep->allEnumLists();
            $options['Passagecommissionep'] = $this->Dossier->Foyer->Personne->Dossierep->Passagecommissionep->allEnumLists();

            // Ajout des enums pour les thématiques du CG uniquement
            foreach( $this->Dossier->Foyer->Personne->Dossierep->Passagecommissionep->Commissionep->Ep->Regroupementep->themes() as $theme ) {
                $modeleDecision = Inflector::classify( "decision{$theme}" );
                if( in_array( 'Enumerable', $this->Dossier->Foyer->Personne->Dossierep->Passagecommissionep->Commissionep->Passagecommissionep->{$modeleDecision}->Behaviors->attached() ) ) {
                    $options = Set::merge( $options, $this->Dossier->Foyer->Personne->Dossierep->Passagecommissionep->Commissionep->Passagecommissionep->{$modeleDecision}->enums() );
                }
            }
            $this->set( compact( 'options' ) );
        }

        /** ********************************************************************
        *
        *** *******************************************************************/

        function index( $personne_id = null ){
            // Vérification du format de la variable
            $this->assert( valid_int( $personne_id ), 'error404' );

            $personne = $this->Dossier->Foyer->Personne->find(
                'first',
                array(
                    'conditions' => array(
                        'Personne.id' => $personne_id
                    ),
                    'contain' => array(
                        'Foyer' => array(
                            'Dossier'
                        )
                    )
                )
            );

            $dossier_id = Set::classicExtract( $personne, 'Foyer.Dossier.id' );
            $details = array();

            // Dernier passage effectif (lié à un passagecommissionep)
            $tpassageEp = $this->Dossier->Foyer->Personne->Dossierep->Passagecommissionep->find(
                'all',
                array(
                    'conditions' => array(
                        'Passagecommissionep.dossierep_id IN ( '.$this->Dossier->Foyer->Personne->Dossierep->sq(
                            array(
                                'alias' => 'dossierseps',
                                'fields' => array( 'dossierseps.id' ),
                                'conditions' => array(
                                    'dossierseps.personne_id' => $personne_id
                                ),
                                'contain' => false
                            )
                        ).' )'
                    ),
                    'contain' => array(
                        'Dossierep' => array(
                        )
                    )
                )
            );

            $decisionEP = array();
            if( !empty( $tpassageEp ) ) {

                foreach( $tpassageEp as $key => $passage ){
// debug($passage);
                    $modelTheme = Inflector::classify( Inflector::singularize( $passage['Dossierep']['themeep'] ) );
                    $modelDecision = 'Decision'.Inflector::singularize( $passage['Dossierep']['themeep'] );

                    // Thématique
                    $thematique = $this->Dossier->Foyer->Personne->Dossierep->{$modelTheme}->find(
                        'first',
                        array(
                            'conditions' => array(
                                "{$modelTheme}.dossierep_id" => $passage['Passagecommissionep']['dossierep_id']
                            ),
                            'contain' => false,
                        )
                    );
                    $this->set( compact( 'thematique' ) );

                    // Décisions par thème
                    $decisionPassage = $this->Dossier->Foyer->Personne->Dossierep->Passagecommissionep->{$modelDecision}->find(
                        'all',
                        array(
                            'conditions' => array(
                                "{$modelDecision}.passagecommissionep_id" => $passage['Passagecommissionep']['id']
                            ),
                            'contain' => false,
                            'order' => array( "{$modelDecision}.created ASC" )
                        )
                    );

                    $tpassageEp[$key] = Set::merge( $passage, $thematique, $decisionPassage  );
                }
                $this->set( 'decisions', $tpassageEp );
            }

            $this->set( 'personne_id', $personne_id );
            $this->_setOptions();

            $this->set( 'dossier_id', $dossier_id );
            $this->set( 'tpassageEp', $tpassageEp );

        }

    }
?>
