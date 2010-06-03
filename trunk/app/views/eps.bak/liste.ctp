<?php
    echo $html->tag(
        'h1',
        $this->pageTitle = __d( 'ep', "Eps::{$this->action}", true )
    )
?>

<?php
    if( $permissions->check( 'eps', 'add' ) ) {
        echo $html->tag(
            'ul',
            $html->tag(
                'li',
                $html->addEquipeLink(
                    'Ajouter une équipe pluridisciplinaire',
                    array( 'controller' => 'eps', 'action' => 'add' )
                )
            ),
            array( 'class' => 'actionMenu' )
        );
    }
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

    echo $xform->create( 'Eps', array( 'type' => 'post', 'action' => '/liste/', 'id' => 'Search', 'class' => ( ( is_array( $this->data ) && !empty( $this->data ) ) ? 'folded' : 'unfolded' ) ) );

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
<?php
    if( isset( $eps ) ) {
        $paginator->options( array( 'url' => $this->params['named'] ) );
        $params = array( 'format' => 'Résultats %start% - %end% sur un total de %count%.' );
        $pagination = $html->tag( 'p', $paginator->counter( $params ) );

        $pages = $paginator->first( '<< ' );
        $pages .= $paginator->prev( ' < ' );
        $pages .= $paginator->numbers();
        $pages .= $paginator->next( ' > ' );
        $pages .= $paginator->last( ' >>' );

        $pagination .= $html->tag( 'p', $pages );
    }
    else {
        $pagination = '';
    }
?>


<div id="tabbedWrapper" class="tabs">
    <?php if( isset( $eps ) ):?>
    <br />
    <h2 class="noprint aere">Résultats de la recherche</h2>

        <div id="demandesreorient">
            <h2 class="title">Demandes de réorientation</h2>
    <?php if( is_array( $eps ) && count( $eps ) > 0  ):?>

                <?php echo $pagination;?>
                <table class="tooltips">
                    <thead>
                        <tr>
                            <th>Nom de l'équipe pluridisciplinaire</th>
                            <th>Date</th>
                            <th>Lieu</th>
                            <th colspan="4" class="action">Actions</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th colspan="2">Traitement des demandes de réorientation</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach( $eps as $ep ) {
                                $isValidordre = false;

                                $valid = Set::classicExtract( $ep, 'Ep.validordre' );
                                if( $valid == 1 ){
                                    $isValidordre = true;
                                }

                                echo $html->tableCells(
                                    array(
                                        h( Set::classicExtract( $ep, 'Ep.name' ) ),
                                        h( $locale->date( 'Locale->datetime',  Set::classicExtract( $ep, 'Ep.date' ) ) ),
                                        h( Set::classicExtract( $ep, 'Ep.localisation' ) ),
                                        $html->ordreLink(
                                            'Voir l\'équipe',
                                            array( 'controller' => 'eps', 'action' => 'ordre', Set::classicExtract( $ep, 'Ep.id' ) ),
                                            !$isValidordre && $permissions->check( 'eps', 'ordre' )
                                        ),
                                        $html->equipeLink(
                                            'Traitement par équipe',
                                            array( 'controller' => 'precosreorients', 'action' => 'index', Set::classicExtract( $ep, 'Ep.id' ) ),
                                            $isValidordre && $permissions->check( 'precosreorients', 'index' )
                                        ),
                                        $html->conseilLink(
                                            'Traitement par CG',
                                            array( 'controller' => 'precosreorients', 'action' => 'conseil', Set::classicExtract( $ep, 'Ep.id' ) ),
                                            $isValidordre && $permissions->check( 'precosreorients', 'conseil' )
                                        ),
                                        $html->decisionLink(
                                            'Décisions',
                                            array( 'controller' => 'precosreorients', 'action' => 'decision', Set::classicExtract( $ep, 'Ep.id' ) ),
                                            false
        //                                     $isValidordre && $permissions->check( 'precosreorients', 'conseil' )
                                        ),
                                    ),
                                    array( 'class' => 'odd' ),
                                    array( 'class' => 'even' )
                                );
                            }
                        ?>
                    </tbody>
                </table>
                <?php echo $pagination;?>
        <?php else:?>
            <p>Vos critères n'ont retourné aucune demande de réorientation.</p>
        <?php endif?>
    </div>


    <div id="parcoursdetectes">
            <h2 class="title">Parcours détectés</h2>

            <?php if( is_array( $eps ) && count( $eps ) > 0  ):?>
                <?php echo $pagination;?>
                <table id="searchResults" class="tooltips">
                    <thead>
                        <tr>
                            <th>Nom de l'équipe pluridisciplinaire</th>
                            <th>Date</th>
                            <th>Lieu</th>
                            <th colspan="4" class="action">Actions</th>
                        </tr>
                        <tr>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th></th>
                            <th colspan="2">Traitement des parcours détectés</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                            foreach( $eps as $ep ) {
                                $isValidordre = false;

                                $valid = Set::classicExtract( $ep, 'Ep.validordre' );
                                if( $valid == 1 ){
                                    $isValidordre = true;
                                }

                                echo $html->tableCells(
                                    array(
                                        h( Set::classicExtract( $ep, 'Ep.name' ) ),
                                        h( $locale->date( 'Locale->datetime',  Set::classicExtract( $ep, 'Ep.date' ) ) ),
                                        h( Set::classicExtract( $ep, 'Ep.localisation' ) ),
                                        $html->ordreLink(
                                            'Voir l\'équipe',
                                            array( 'controller' => 'eps', 'action' => 'ordre', Set::classicExtract( $ep, 'Ep.id' ) ),
                                            !$isValidordre && $permissions->check( 'eps', 'ordre' )
                                        ),
                                        $html->equipeLink(
                                            'Traitement par équipe',
                                            array( 'controller' => 'parcoursdetectes', 'action' => 'index', Set::classicExtract( $ep, 'Ep.id' ) ),
                                            $isValidordre && $permissions->check( 'precosreorients', 'index' )
                                        ),
                                        $html->conseilLink(
                                            'Traitement par CG',
                                            array( 'controller' => 'parcoursdetectes', 'action' => 'conseil', Set::classicExtract( $ep, 'Ep.id' ) ),
                                            $isValidordre && $permissions->check( 'precosreorients', 'conseil' )
                                        ),
                                        $html->decisionLink(
                                            'Décisions',
                                            array( 'controller' => 'parcoursdetectes', 'action' => 'decision', Set::classicExtract( $ep, 'Ep.id' ) ),
                                            false
        //                                     $isValidordre && $permissions->check( 'precosreorients', 'conseil' )
                                        ),
                                    ),
                                    array( 'class' => 'odd' ),
                                    array( 'class' => 'even' )
                                );
                            }
                        ?>
                    </tbody>
                </table>
                <?php echo $pagination;?>

            </div>
        </div>
        <?php else:?>
            <p>Vos critères n'ont retourné aucun parcours.</p>
        <?php endif?>
<?php endif?>

<!-- *********************************************************************** -->

<?php
    echo $javascript->link( 'prototype.livepipe.js' );
    echo $javascript->link( 'prototype.tabs.js' );
?>

<script type="text/javascript">
    makeTabbed( 'tabbedWrapper', 2 );
</script>