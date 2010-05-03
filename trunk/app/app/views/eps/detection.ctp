<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'ep', "Eps::{$this->action}", true )
    )
?>
<?php
    if( is_array( $this->data ) ) {
        echo '<ul class="actionMenu"><li>'.$html->link(
            $html->image(
                'icons/application_form_magnify.png',
                array( 'alt' => '' )
            ).' Formulaire',
            '#',
            array( 'escape' => false, 'title' => 'Visibilité formulaire', 'onclick' => "$( 'Search' ).toggle(); return false;" )
        ).'</li></ul>';
    }

    echo $xform->create( 'Eps', array( 'type' => 'post', 'action' => '/detection/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );

    ///Formulaire de recherche pour les EPs
    echo $default->search(
        array(
            'Ep.name',
            'Ep.date' => array( 'type' => 'date', 'dateFormat' => 'DMY', 'minYear' => date( 'Y' ) - 1, 'maxYear' => date( 'Y' ) + 1 ),
            'Ep.localisation'
        ),
        array(
            'options' => $options
        )
    );

    echo $xform->end();
?>
<?php if( isset( $eps ) ):?>
    <h2 class="noprint">Résultats de la recherche</h2>
        <?php if( is_array( $eps ) && count( $eps ) > 0  ):?>
            <?php
                /// Résultat de la recherche

                $isValidordre = false;

                foreach( $eps as $i => $ep ){
                    $valid = Set::classicExtract( $ep, 'Ep.validordre' );
                    if( $valid == 1 ){
                        $isValidordre = true;
                    }
                }

                echo $default->index(
                    $eps,
                    array(
                        'Ep.name',
                        'Ep.date',
                        'Ep.localisation',
//                         'Ep.nbrdemandesreorient',
//                         'Ep.nbrparcoursdetectes',
                    ),
                    array(
                        'actions' => array(
                            'Ep.ordre' => array( 'controller' => 'eps', 'action' => 'ordre' ),
                            'Ep.equipe' => array( 'controller' => 'parcoursdetectes', 'action' => 'equipe' ),
                            'Ep.conseil' => array( 'controller' => 'parcoursdetectes', 'action' => 'conseil' ),
                            'Ep.decision' /*=> array( 'controller' => 'parcoursdetectes', 'action' => 'decision'  )*/
                        ),
                        'add' => 'Ep.add',
                        'options' => array(
                            'Ep' => array(
                                'equipe' => array( 'enabled' => $isValidordre ),
                                'conseil' => array( 1 => 'Enabled', 0 => 'Disabled' ),
                                'decision' => array( 1 => 'Enabled', 0 => 'Disabled' ),
                            )
                        )
                    )
                );
            ?>
        <?php else:?>
            <p>Vos critères n'ont retourné aucun dossier.</p>
        <?php endif?>
<?php endif?>
