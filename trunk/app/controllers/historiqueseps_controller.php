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
            $tdossierEp = $this->Dossier->Foyer->Personne->Dossierep->find(
                'all',
                array(
                    'fields' => array(
                        'Dossierep.themeep',
                        'Commissionep.dateseance',
                        'Passagecommissionep.id',
                        'Passagecommissionep.commissionep_id',
                        'Passagecommissionep.etatdossierep',
                    ),
                    'joins' => array(
                        array(
                            'table'      => 'passagescommissionseps',
                            'alias'      => 'Passagecommissionep',
                            'type'       => 'INNER',
                            'foreignKey' => false,
                            'conditions' => array( 'Passagecommissionep.dossierep_id = Dossierep.id' )
                        ),
                        array(
                            'table'      => 'commissionseps',
                            'alias'      => 'Commissionep',
                            'type'       => 'INNER',
                            'foreignKey' => false,
                            'conditions' => array( 'Passagecommissionep.commissionep_id = Commissionep.id' )
                        ),
                    ),
                    'conditions' => array(
                        'Dossierep.personne_id' => $personne_id
                    ),
                    'contain' => false,
                    'order' => array(  )
                )
            );

            $decisionEP = array();
            if( !empty( $tdossierEp ) ) {
                $themeEP = Set::extract( $tdossierEp, '/Dossierep/themeep' );
                foreach( $themeEP as $key => $theme ) {
                    $modelTheme = Inflector::classify( Inflector::singularize( $theme ) );
                    $modelDecision = 'Decision'.Inflector::singularize( $theme );
                }
                
//                 debug($tdossierEp);
                $commissionep_id = Set::classicExtract( $tdossierEp, 'Passagecommissionep.commissionep_id' );
                $niveauDecision = Set::classicExtract( $tdossierEp, 'Passagecommissionep.etatdossierep' );
                //FIXME: voir si en utilisant ceci cela ne fonctionne pas mieux
// debug( $this->Dossier->Foyer->Personne->Dossierep->Passagecommissionep->Commissionep->dossiersParListe( $commissionep_id, $niveauDecision ) );


                foreach( $tdossierEp as $key => $dossierep ){

                    $decisionEP = $this->Dossier->Foyer->Personne->Dossierep->Passagecommissionep->{$modelDecision}->find(
                        'all',
                        array(
                            'conditions' => array(
                                "{$modelDecision}.passagecommissionep_id" => $dossierep['Passagecommissionep']['id']
                            ),
                            'contain' => false,
                            'order' => array( "{$modelDecision}.etape ASC" )
                        )
                    );

                    $tdossierEp[$key][$modelDecision] = Set::classicExtract(  $decisionEP, "{n}.{$modelDecision}");
                }
 
//                 $decisions = Set::merge( $decisionEP, $tdossierEp );
                $this->set( 'decisions', $tdossierEp );
            }

            $this->set( 'personne_id', $personne_id );
            $this->_setOptions();

            $this->set( 'dossier_id', $dossier_id );
            $this->set( 'tdossierEp', $tdossierEp );

        }

    }
?>
