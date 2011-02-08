<?php
    /**
    * Séance d'équipe pluridisciplinaire.
    *
    * PHP versions 5
    *
    * @package       app
    * @subpackage    app.app.models
    */

    class Criterebilanparcours66 extends AppModel
    {
        var $name = 'Criterebilanparcours66';
        var $useTable = false;

        public function search( $criteresbilansparcours66 ) {
            /// Conditions de base

            $conditions = array();

            if ( isset($criteresbilansparcours66['Bilanparcours66']['choixparcours']) && !empty($criteresbilansparcours66['Bilanparcours66']['choixparcours']) ) {
                $conditions[] = array('Bilanparcours66.choixparcours'=>$criteresbilansparcours66['Bilanparcours66']['choixparcours']);
            }

            if ( isset($criteresbilansparcours66['Bilanparcours66']['proposition']) && !empty($criteresbilansparcours66['Bilanparcours66']['proposition']) ) {
                $conditions[] = array('Bilanparcours66.proposition'=>$criteresbilansparcours66['Bilanparcours66']['proposition']);
            }

            if ( isset($criteresbilansparcours66['Bilanparcours66']['examenaudition']) && !empty($criteresbilansparcours66['Bilanparcours66']['examenaudition']) ) {
                $conditions[] = array('Bilanparcours66.examenaudition'=>$criteresbilansparcours66['Bilanparcours66']['examenaudition']);
            }

            if ( isset($criteresbilansparcours66['Bilanparcours66']['maintienorientation']) && is_numeric($criteresbilansparcours66['Bilanparcours66']['maintienorientation']) ) {
                $conditions[] = array('Bilanparcours66.maintienorientation'=>$criteresbilansparcours66['Bilanparcours66']['maintienorientation']);
            }

            if ( isset($criteresbilansparcours66['Bilanparcours66']['referent_id']) && !empty($criteresbilansparcours66['Bilanparcours66']['referent_id']) ) {
                $conditions[] = array('Bilanparcours66.referent_id'=>$criteresbilansparcours66['Bilanparcours66']['referent_id']);
            }

            /// Critères sur le Bilan - date du bilan
            if( isset( $criteresbilansparcours66['Bilanparcours66']['datebilan'] ) && !empty( $criteresbilansparcours66['Bilanparcours66']['datebilan'] ) ) {
                $valid_from = ( valid_int( $criteresbilansparcours66['Bilanparcours66']['datebilan_from']['year'] ) && valid_int( $criteresbilansparcours66['Bilanparcours66']['datebilan_from']['month'] ) && valid_int( $criteresbilansparcours66['Bilanparcours66']['datebilan_from']['day'] ) );
                $valid_to = ( valid_int( $criteresbilansparcours66['Bilanparcours66']['datebilan_to']['year'] ) && valid_int( $criteresbilansparcours66['Bilanparcours66']['datebilan_to']['month'] ) && valid_int( $criteresbilansparcours66['Bilanparcours66']['datebilan_to']['day'] ) );
                if( $valid_from && $valid_to ) {
                    $conditions[] = 'Bilanparcours66.datebilan BETWEEN \''.implode( '-', array( $criteresbilansparcours66['Bilanparcours66']['datebilan_from']['year'], $criteresbilansparcours66['Bilanparcours66']['datebilan_from']['month'], $criteresbilansparcours66['Bilanparcours66']['datebilan_from']['day'] ) ).'\' AND \''.implode( '-', array( $criteresbilansparcours66['Bilanparcours66']['datebilan_to']['year'], $criteresbilansparcours66['Bilanparcours66']['datebilan_to']['month'], $criteresbilansparcours66['Bilanparcours66']['datebilan_to']['day'] ) ).'\'';
                }
            }

            $query = array(
                'fields' => array(
                    'Bilanparcours66.id',
                    'Bilanparcours66.orientstruct_id',
                    'Bilanparcours66.referent_id',
                    'Bilanparcours66.datebilan',
                    'Bilanparcours66.choixparcours',
                    'Bilanparcours66.proposition',
                    'Bilanparcours66.examenaudition',
                    'Bilanparcours66.maintienorientation',
                    'Bilanparcours66.saisineepparcours'
                ),
                'contain'=>array(
                    'Orientstruct' => array(
                         'Personne' => array(
                            'fields' => array( 'qual', 'nom', 'prenom' ),
                        )
                    ),
                    'Referent' => array(
                        'Structurereferente'
                    )
                ),
                'order' => array( '"Bilanparcours66"."datebilan" ASC' ),
                'conditions' => $conditions
            );

            return $query;
        }
    }
?>